<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\BulkSMS\DataTables\SinglesmsSendDatatable;
use Workdo\BulkSMS\Entities\BulksmsContact;
use Workdo\BulkSMS\Entities\SendMsg;
use Workdo\BulkSMS\Entities\SinglesmsSend;
use Workdo\BulkSMS\Entities\CustomerMessage;
use Workdo\SmsCredit\Helpers\SmsCreditHelper;

class SingleSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SinglesmsSendDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('group_contact manage')) {
            return $dataTable->render('bulk-sms::singlesms.index');
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd('create method called');
        // if (Auth::user()->isAbleTo('group_contact create')) {
        $contacts = BulksmsContact::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get(['id', 'name', 'mobile_no']);

        $messages = CustomerMessage::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get();

        // Get users
        $users = \App\Models\User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->whereNotIn('type', ['super admin'])
            ->get(['id', 'name', 'mobile_no as mobile']);

        // Get customers (if Account module is active)
        $customers = collect();
        if (module_is_active('Account')) {
            $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['id', 'name', 'contact as phone']);
        }

        // Fetch sender IDs from settings (first try BulkSMS module settings, then system settings)
        $settings = getCompanyAllSetting();
        $senderIds = [];

        // Check BulkSMS module settings first
        if (!empty($settings['bulksms_sender_ids'])) {
            $senderIds = array_map('trim', explode(',', $settings['bulksms_sender_ids']));
        }
        // Fallback to system settings if BulkSMS settings are empty
        elseif (!empty($settings['sender_ids'])) {
            $senderIds = array_map('trim', explode(',', $settings['sender_ids']));
        }

        // Add default option if no sender IDs configured
        if (empty($senderIds)) {
            $senderIds = ['DEFAULT'];
        }

        return view('bulk-sms::singlesms.create', compact('contacts', 'messages', 'senderIds', 'users', 'customers'));
        // }

        // return response()->json(['error' => __('Permission Denied.')], 401);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        // dd('store method called');
        if (!Auth::user()->isAbleTo('group_contact create')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $settings = getCompanyAllSetting();

        if (
            !module_is_active('BulkSMS') ||
            empty($settings['bulksms_username']) ||
            empty($settings['bulksms_password'])
        ) {
            return redirect()->back()->with('error', __('Please configure the credentials before proceeding.'));
        }

        // ✅ Updated validation
        $validator = Validator::make($request->all(), [
            'contact_id' => 'required',
            'mobile_no' => 'required|string',
            'sender_id' => 'required|string',
            'sms' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Calculate credits needed
        $messageLength = strlen($request->sms);
        $creditsNeeded = SmsCreditHelper::calculateCreditsNeeded($messageLength);
        $mobileNumbers = is_array($request->mobile_no) ? $request->mobile_no : [$request->mobile_no];
        $recipientCount = count($mobileNumbers);
        $totalCreditsNeeded = $creditsNeeded * $recipientCount;

        // Check credits (only if SmsCredit module is active)
        if (module_is_active('SmsCredit')) {
            if (!SmsCreditHelper::hasCredits($totalCreditsNeeded)) {
                return redirect()->back()->with(
                    'error',
                    __('Insufficient SMS credits. Required: :required credits (:recipients recipient(s) × :credits_per_msg credits/msg). Message length: :length chars.', [
                        'required' => $totalCreditsNeeded,
                        'recipients' => $recipientCount,
                        'credits_per_msg' => $creditsNeeded,
                        'length' => $messageLength
                    ])
                );
            }
        }

        // Save SMS
        $sms = new SinglesmsSend();
        $sms->name = $request->contact_id;
        $sms->mobile_no = is_array($request->mobile_no)
            ? implode(',', $request->mobile_no)
            : $request->mobile_no;
        $sms->sms = $request->sms;
        $sms->workspace = getActiveWorkSpace();
        $sms->created_by = creatorId();

        // Prepare data for API
        $uArr = [
            'user_name' => Auth::user()->name ?? '-',
            'sender_id' => $request->sender_id,
        ];

        // Send the message
        $response = SendMsg::SendMsgs($sms->mobile_no, $uArr, $sms->sms);
        // dd($response->data['status']);
        $status = 'failed';
        if (!$response['error']) {
            $status = $response['response']['status'] ?? 'pending';
        }

        $sms->status = $status;
        $sms->save();

        // Deduct credits if successful (only if SmsCredit module is active)
        if (module_is_active('SmsCredit') && $status === 'sent') {
            SmsCreditHelper::useCredits(
                $totalCreditsNeeded,
                "Single SMS to {$recipientCount} recipient(s) ({$messageLength} chars, {$creditsNeeded} credits/msg)"
            );
        }

        $successMessage = __('The SMS was sent successfully.');
        if (module_is_active('SmsCredit') && $status === 'sent') {
            $successMessage .= ' ' . __('Credits used: :credits.', ['credits' => $totalCreditsNeeded]);
        }

        return redirect()->route('bulksms-single-sms.index')
            ->with('success', $successMessage);
    }

    /**
     * Save a custom message template.
     */
    public function storeMessageTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $msg = new CustomerMessage();
        $msg->name = $request->name;
        $msg->message = $request->message;
        $msg->created_by = creatorId();
        $msg->workspace = getActiveWorkSpace();
        $msg->save();

        return redirect()->back()->with('success', __('Message Template Saved Successfully.'));
    }

    /**
     * Display the SMS message content.
     */
    public function message($id)
    {
        if (Auth::user()->isAbleTo('group_contact manage')) {
            $singlesmsSend = SinglesmsSend::find($id);
            return view('bulk-sms::singlesms.message', compact('singlesmsSend'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Delete a message record.
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAbleTo('group_contact delete')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $singlesmsSend = SinglesmsSend::find($id);

        if (!$singlesmsSend) {
            return redirect()->back()->with('error', __('SMS not found.'));
        }

        $singlesmsSend->delete();
        return redirect()->back()->with('success', __('The SMS has been deleted.'));
    }
}
