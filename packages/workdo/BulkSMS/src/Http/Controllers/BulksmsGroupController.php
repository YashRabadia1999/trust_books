<?php

namespace Workdo\BulkSMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\BulkSMS\DataTables\BulksmsGroupDatatable;
use Workdo\BulkSMS\Entities\BulksmsContact;
use Workdo\BulkSMS\Entities\BulksmsGroup;
use Workdo\BulkSMS\Entities\BulksmsSend;
use Workdo\BulkSMS\Events\CreateGroup;
use Workdo\BulkSMS\Events\DestoryGroup;
use Workdo\BulkSMS\Events\UpdateGroup;

class BulksmsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BulksmsGroupDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('group_contact manage')) {

            return $dataTable->render('bulk-sms::group.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('group_contact create')) {
            // Get contacts from BulkSMS contacts
            $contacts = BulksmsContact::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['name', 'mobile_no']);

            // Get users
            $users = \App\Models\User::where('created_by', creatorId())
                ->where('workspace_id', getActiveWorkSpace())
                ->whereNotIn('type', ['super admin'])
                ->whereNotNull('mobile_no')
                ->where('mobile_no', '!=', '')
                ->get(['name', 'mobile_no']);

            // Get customers if Account module is active
            $customers = collect();
            if (module_is_active('Account')) {
                $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->get(['name', 'contact as mobile_no']);
            }

            return view('bulk-sms::group.create', compact('contacts', 'users', 'customers'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('group_contact create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'mobile_no' => 'required|array',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $bulksmsGroup = new BulksmsGroup();
            $bulksmsGroup->name = $request->name;
            $bulksmsGroup->mobile_no = isset($request->mobile_no) ? implode(',', $request->mobile_no) : '';
            $bulksmsGroup->workspace = getActiveWorkSpace();
            $bulksmsGroup->created_by = creatorId();
            $bulksmsGroup->save();
            event(new CreateGroup($request, $bulksmsGroup));

            return redirect()->back()->with('success', __('The group has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('group_contact show')) {
            $id = decrypt($id);
            $bulksmsGroup = BulkSMSGroup::find($id);
            $mobileNumbers = array_filter(explode(',', $bulksmsGroup->mobile_no));
            $contacts = BulkSMSContact::whereIn('mobile_no', $mobileNumbers)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('bulk-sms::group.show', compact('id', 'bulksmsGroup', 'contacts'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('group_contact edit')) {
            $bulksmsGroup = BulksmsGroup::find($id);
            $selectedContacts = explode(',', $bulksmsGroup->mobile_no);

            // Get contacts from BulkSMS contacts
            $contacts = BulksmsContact::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->get(['name', 'mobile_no']);

            // Get users
            $users = \App\Models\User::where('created_by', creatorId())
                ->where('workspace_id', getActiveWorkSpace())
                ->whereNotIn('type', ['super admin'])
                ->whereNotNull('mobile_no')
                ->where('mobile_no', '!=', '')
                ->get(['name', 'mobile_no']);

            // Get customers if Account module is active
            $customers = collect();
            if (module_is_active('Account')) {
                $customers = \Workdo\Account\Entities\Customer::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->whereNotNull('contact')
                    ->where('contact', '!=', '')
                    ->get(['name', 'contact as mobile_no']);
            }

            return view('bulk-sms::group.edit', compact('bulksmsGroup', 'selectedContacts', 'contacts', 'users', 'customers'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('group_contact edit')) {
            $bulksmsGroup = BulksmsGroup::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'mobile_no' => 'required|array',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $bulksmsGroup->name = $request->name;
            $bulksmsGroup->mobile_no = isset($request->mobile_no) ? implode(',', $request->mobile_no) : '';
            $bulksmsGroup->save();
            event(new UpdateGroup($request, $bulksmsGroup));
            return redirect()->back()->with('success', __('The group details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('group_contact delete')) {

            $bulksmsGroup = BulksmsGroup::find($id);
            $group = BulksmsSend::where('group_id', $bulksmsGroup->id)->exists();
            if ($group) {
                return redirect()->back()->with('error', __('Group is in use and cannot be deleted.'));
            }
            event(new DestoryGroup($bulksmsGroup));
            $bulksmsGroup->delete();

            return redirect()->back()->with('success', __('The group has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function removeContact($groupId, $mobileToRemove)
    {
        if (Auth::user()->isAbleTo('group_contact delete')) {
            $group = BulksmsGroup::findOrFail($groupId);

            $mobiles = array_filter(explode(',', $group->mobile_no));

            $mobiles = array_filter($mobiles, function ($mobile) use ($mobileToRemove) {
                return $mobile !== $mobileToRemove;
            });

            $group->mobile_no = implode(',', $mobiles);
            $group->save();

            return redirect()->back()->with('success', __('The contact has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
