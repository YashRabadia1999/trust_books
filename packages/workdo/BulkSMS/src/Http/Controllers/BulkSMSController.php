<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\BulkSMS\DataTables\BulksmsSendDatatable;
use Workdo\BulkSMS\Entities\BulksmsContact;
use Workdo\BulkSMS\Entities\BulksmsGroup;
use Workdo\BulkSMS\Entities\BulksmsSend;
use Workdo\BulkSMS\Entities\BulksmsSendMessage;
use Workdo\BulkSMS\Entities\SendMsg;
use Workdo\SmsCredit\Helpers\SmsCreditHelper;

class BulkSMSController extends Controller
{
    public function index(BulksmsSendDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('bulksms_send manage')) {
            return $dataTable->render('bulk-sms::bulksms.index');
        }
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    public function create()
    {
        if (!Auth::user()->isAbleTo('bulksms_send create')) {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }

        $groups = BulksmsGroup::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get();

        // Get users
        $users = \App\Models\User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->whereNotIn('type', ['super admin'])
            ->get(['id', 'name', 'mobile_no']);

        // Get customers if Account module is active
        $customers = collect();
        if (module_is_active('Account')) {
            $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['id', 'name', 'contact as phone']);
        }

        // Get custom message templates
        $customMessages = \Workdo\BulkSMS\Entities\CustomerMessage::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get(['id', 'name', 'message']);

        // Get Sender IDs from settings
        $settings = getCompanyAllSetting();
        $senderIds = [];

        if (!empty($settings['bulksms_sender_ids'])) {
            $senderIds = array_map('trim', explode(',', $settings['bulksms_sender_ids']));
        } elseif (!empty($settings['sender_ids'])) {
            $senderIds = array_map('trim', explode(',', $settings['sender_ids']));
        }

        if (empty($senderIds)) {
            $senderIds = ['DEFAULT'];
        }

        return view('bulk-sms::bulksms.create', compact('groups', 'users', 'customers', 'customMessages', 'senderIds'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAbleTo('bulksms_send create')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $settings = getCompanyAllSetting();
        if (!module_is_active('BulkSMS') || empty($settings['bulksms_username']) || empty($settings['bulksms_password'])) {
            return redirect()->back()->with('error', __('Please configure the credentials before proceeding.'));
        }

        $validator = \Validator::make($request->all(), [
            'recipient_type' => 'required|in:group,users,customers',
            'sender_id' => 'required',
            'message_type' => 'required|in:custom,template',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Get message content
        $message = '';
        if ($request->message_type === 'template' && $request->custom_message_id) {
            $customMessage = \Workdo\BulkSMS\Entities\CustomerMessage::find($request->custom_message_id);
            if ($customMessage) {
                $message = $customMessage->message;
            }
        } else {
            $message = $request->sms ?? '';
        }

        if (empty($message)) {
            return redirect()->back()->with('error', __('Message content is required.'));
        }

        // Collect phone numbers based on recipient type
        $mobileNumbers = [];
        $recipientNames = [];

        if ($request->recipient_type === 'group') {
            if (empty($request->group_id)) {
                return redirect()->back()->with('error', __('Please select a group.'));
            }

            $group = BulksmsGroup::find($request->group_id);
            if (!$group) {
                return redirect()->back()->with('error', __('Selected group not found.'));
            }

            $mobileNumbers = array_filter(array_map('trim', explode(',', $group->mobile_no ?? '')));

            // Get names for each contact
            foreach ($mobileNumbers as $number) {
                $contact = BulksmsContact::where('mobile_no', $number)
                    ->where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->first();
                $recipientNames[$number] = $contact ? $contact->name : '-';
            }

        } elseif ($request->recipient_type === 'users') {
            if (empty($request->user_ids)) {
                return redirect()->back()->with('error', __('Please select at least one user.'));
            }

            $userIds = is_array($request->user_ids) ? $request->user_ids : [$request->user_ids];
            $users = \App\Models\User::whereIn('id', $userIds)
                ->where('created_by', creatorId())
                ->where('workspace_id', getActiveWorkSpace())
                ->get();

            foreach ($users as $user) {
                if (!empty($user->mobile_no)) {
                    $mobileNumbers[] = $user->mobile_no;
                    $recipientNames[$user->mobile_no] = $user->name;
                }
            }

        } elseif ($request->recipient_type === 'customers') {
            if (!module_is_active('Account')) {
                return redirect()->back()->with('error', __('Account module is not active.'));
            }

            if (empty($request->customer_ids)) {
                return redirect()->back()->with('error', __('Please select at least one customer.'));
            }

            $customerIds = is_array($request->customer_ids) ? $request->customer_ids : [$request->customer_ids];
            $customers = \Workdo\Account\Entities\Customer::whereIn('id', $customerIds)
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get();

            foreach ($customers as $customer) {
                if (!empty($customer->contact)) {
                    $mobileNumbers[] = $customer->contact;
                    $recipientNames[$customer->contact] = $customer->name;
                }
            }
        }

        if (empty($mobileNumbers)) {
            return redirect()->back()->with('error', __('No valid phone numbers found.'));
        }

        // Calculate credits needed (first 150 chars = 1 credit, then 100 chars = 1 credit each)
        $messageLength = strlen($message);
        $creditsPerMessage = SmsCreditHelper::calculateCreditsNeeded($messageLength);
        $recipientCount = count($mobileNumbers);
        $totalCreditsNeeded = SmsCreditHelper::calculateBulkCredits($recipientCount, $messageLength);

        // Check if user has sufficient credits (only if SmsCredit module is active)
        if (module_is_active('SmsCredit')) {
            if (!SmsCreditHelper::hasCredits($totalCreditsNeeded)) {
                return redirect()->back()->with(
                    'error',
                    __('Insufficient SMS credits. Required: :required credits (:recipients recipients × :credits_per_msg credits/msg). Message length: :length chars.', [
                        'required' => $totalCreditsNeeded,
                        'recipients' => $recipientCount,
                        'credits_per_msg' => $creditsPerMessage,
                        'length' => $messageLength
                    ])
                );
            }
        }

        // Save main bulk SMS record
        $bulksmsSendMain = new BulksmsSend();
        $bulksmsSendMain->group_id = $request->group_id ?? 0;
        $bulksmsSendMain->mobile_no = implode(',', $mobileNumbers);
        $bulksmsSendMain->sms = $message;
        $bulksmsSendMain->workspace = getActiveWorkSpace();
        $bulksmsSendMain->created_by = creatorId();
        $bulksmsSendMain->save();

        // Send SMS to each recipient
        $successCount = 0;
        $failedCount = 0;

        foreach ($mobileNumbers as $number) {
            $bulksmsSendMessage = new BulksmsSendMessage();
            $bulksmsSendMessage->name = $recipientNames[$number] ?? '-';
            $bulksmsSendMessage->group_id = $request->group_id ?? 0;
            $bulksmsSendMessage->mobile_no = $number;
            $bulksmsSendMessage->sms = $message;
            $bulksmsSendMessage->workspace = getActiveWorkSpace();
            $bulksmsSendMessage->created_by = creatorId();

            // Send SMS via API
            $response = SendMsg::SendMsgs($number, [], $message);

            $bulksmsSendMessage->status = (!isset($response['error']) || !$response['error']) ? 'success' : 'failed';
            $bulksmsSendMessage->save();

            if ($bulksmsSendMessage->status === 'success') {
                $successCount++;
            } else {
                $failedCount++;
            }

            // Small delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }

        // Deduct credits for successful messages (only if SmsCredit module is active)
        if (module_is_active('SmsCredit') && $successCount > 0) {
            $creditsUsed = $successCount * $creditsPerMessage;
            SmsCreditHelper::useCredits(
                $creditsUsed,
                "Bulk SMS sent to {$successCount} recipients ({$messageLength} chars, {$creditsPerMessage} credits/msg)"
            );
        }

        $resultMessage = "SMS sent to {$successCount} recipient(s).";
        if ($failedCount > 0) {
            $resultMessage .= " {$failedCount} failed.";
        }
        if (module_is_active('SmsCredit') && $successCount > 0) {
            $resultMessage .= " Credits used: " . ($successCount * $creditsPerMessage) . ".";
        }

        return redirect()->back()->with('success', __($resultMessage));
    }

    public function show($id)
    {
        if (!Auth::user()->isAbleTo('bulksms_send manage')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $id = decrypt($id);
        $bulksmsSend = BulksmsSend::find($id);
        if (!$bulksmsSend) {
            return redirect()->back()->with('error', __('SMS record not found.'));
        }

        $group = BulksmsGroup::find($bulksmsSend->group_id);
        $contacts = null;
        if ($group) {
            $contacts = BulksmsSendMessage::where('group_id', $group->id)
                ->where('sms', $bulksmsSend->sms)
                ->where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get();
        }

        return view('bulk-sms::bulksms.show', compact('id', 'bulksmsSend', 'contacts'));
    }

    public function edit($id)
    {
        return view('bulk-sms::edit');
    }

    public function update(Request $request, $id)
    {
        // not implemented
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAbleTo('bulksms_send delete')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $bulksmsSend = BulksmsSend::find($id);
        if (!$bulksmsSend) {
            return redirect()->back()->with('error', __('SMS record not found.'));
        }

        $group = BulksmsGroup::find($bulksmsSend->group_id);

        BulksmsSendMessage::where('group_id', $group->id ?? 0)
            ->where('sms', $bulksmsSend->sms)
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->delete();

        $bulksmsSend->delete();
        return redirect()->back()->with('success', __('The sms has been deleted.'));
    }

    public function message($id)
    {
        if (!Auth::user()->isAbleTo('bulksms_send manage')) {
            return response()->json(['error' => __('Permission denied.')], 401);
        }

        $bulksmsSend = BulksmsSend::find($id);
        return view('bulk-sms::bulksms.message', compact('bulksmsSend'));
    }

    public function removeSms($id)
    {
        if (!Auth::user()->isAbleTo('bulksms_send delete')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $bulksmsMessage = BulksmsSendMessage::find($id);
        if ($bulksmsMessage) {
            $bulksmsMessage->delete();
        }

        return redirect()->back()->with('success', __('The sms has been deleted.'));
    }
}
