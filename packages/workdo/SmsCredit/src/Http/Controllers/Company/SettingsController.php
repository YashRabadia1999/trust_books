<?php

namespace Workdo\SmsCredit\Http\Controllers\Company;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = getCompanyAllSetting();
        return view('sms-credit::settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('sms credit manage')) {
            $rules = [
                'sms_rate_per_credit' => 'required|numeric|min:0.01',
                'sms_min_purchase_amount' => 'required|numeric|min:1',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $post = $request->all();
            unset($post['_token']);

            foreach ($post as $key => $value) {
                \App\Models\Setting::updateOrCreate(
                    [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    ['value' => $value]
                );
            }

            return redirect()->back()->with('success', __('SMS Credit settings saved successfully.'));
        }

        return redirect()->back()->with('error', __('Permission denied.'));
    }
}
