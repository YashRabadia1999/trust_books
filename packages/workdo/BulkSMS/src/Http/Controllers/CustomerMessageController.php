<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Workdo\BulkSMS\DataTables\CustomerMessageDatatable;
use Workdo\BulkSMS\Entities\CustomerMessage;
use Workdo\BulkSMS\Events\CreateCustomerMessage;
use Workdo\BulkSMS\Events\UpdateCustomerMessage;
use Workdo\BulkSMS\Events\DestroyCustomerMessage;

class CustomerMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerMessageDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('bulksms_contact manage')) {
            return $dataTable->render('bulk-sms::customermessage.index');
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            return view('bulk-sms::customermessage.create');
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $customerMessage = new CustomerMessage();
            $customerMessage->name = $request->name;
            $customerMessage->message = $request->message;
            $customerMessage->created_by = creatorId();
            $customerMessage->workspace = getActiveWorkSpace();
            $customerMessage->save();

            event(new CreateCustomerMessage($request, $customerMessage));

            return redirect()->route('customer-messages.index')
                ->with('success', __('Message template created successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact edit')) {
            $customerMessage = CustomerMessage::find($id);
            return view('bulk-sms::customermessage.edit', compact('customerMessage'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact edit')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $customerMessage = CustomerMessage::find($id);
            $customerMessage->name = $request->name;
            $customerMessage->message = $request->message;
            $customerMessage->save();

            event(new UpdateCustomerMessage($request, $customerMessage));

            return redirect()->route('customer-messages.index')
                ->with('success', __('Message template updated successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact delete')) {
            $customerMessage = CustomerMessage::find($id);

            if (!$customerMessage) {
                return redirect()->back()->with('error', __('Message template not found.'));
            }

            event(new DestroyCustomerMessage($customerMessage));
            $customerMessage->delete();

            return redirect()->back()->with('success', __('Message template deleted successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the message content.
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact manage')) {
            $customerMessage = CustomerMessage::find($id);
            return view('bulk-sms::customermessage.show', compact('customerMessage'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Send as single SMS
     */
    public function sendSingle($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $customerMessage = CustomerMessage::find($id);

            $contacts = \Workdo\BulkSMS\Entities\BulksmsContact::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['id', 'name', 'mobile_no']);

            $users = \App\Models\User::where('created_by', creatorId())
                ->where('workspace_id', getActiveWorkSpace())
                ->whereNotIn('type', ['super admin'])
                ->get(['id', 'name', 'mobile_no as mobile']);

            $customers = collect();
            if (module_is_active('Account')) {
                $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->get(['id', 'name', 'contact as phone']);
            }

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

            return view('bulk-sms::customermessage.send-single', compact('customerMessage', 'contacts', 'users', 'customers', 'senderIds'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }

    /**
     * Send as bulk SMS
     */
    public function sendBulk($id)
    {
        if (Auth::user()->isAbleTo('bulksms_contact create')) {
            $customerMessage = CustomerMessage::find($id);

            $groups = \Workdo\BulkSMS\Entities\BulksmsGroup::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get();

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

            return view('bulk-sms::customermessage.send-bulk', compact('customerMessage', 'groups', 'senderIds'));
        }

        return response()->json(['error' => __('Permission denied.')], 401);
    }
}
