<?php
// This file use for handle company setting page

namespace Workdo\BulkSMS\Http\Controllers\Company;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        return view('bulk-sms::company.settings.index', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('bulksms manage')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'bulksms_username' => 'required',
                    'bulksms_password' => 'required',
                    'bulksms_sender_ids' => 'nullable|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $post = $request->except('_token');
            foreach ($post as $key => $value) {
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                Setting::updateOrCreate($data, ['value' => $value]);
            }

            comapnySettingCacheForget();
            return redirect()->back()->with('success', __('BulkSMS setting save sucessfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
