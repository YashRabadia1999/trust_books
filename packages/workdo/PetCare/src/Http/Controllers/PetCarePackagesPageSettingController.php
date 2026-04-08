<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareSystemSetup;

class PetCarePackagesPageSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_packages_page_setting manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('key', ['payment_policies_tagline_label', 'payment_policies_heading_title'])->pluck('value', 'key')->toArray();
            $paymentPoliciesRecord = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('key', 'payment_policies')->first();
            $paymentPolicies = $paymentPoliciesRecord ? json_decode($paymentPoliciesRecord->value, true) : [];

            return view('pet-care::system_setup.packages-page-setting.index', [
                'petcare_system_setup'  => $petcare_settings,
                'paymentPolicies' => $paymentPolicies
            ]);
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
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('petcare_packages_page_setting manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'payment_policies_tagline_label'   => 'required|string|max:255',
                    'payment_policies_heading_title'   => 'required|string|max:255',
                    'policy_icon'                      => 'required|array',
                    'policy_icon.*'                    => 'required|string',
                    'policy_title'                     => 'required|array',
                    'policy_title.*'                   => 'required|string',
                    'policy_description'               => 'required|array',
                    'policy_description.*'             => 'required|string',
                ],
                [
                    'policy_icon.*.required'        => __('The icon field is required for all policies.'),
                    'policy_title.*.required'       => __('The title field is required for all policies.'),
                    'policy_description.*.required' => __('The description field is required for all policies.'),
                ]
            );


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $fields = [
                'payment_policies_tagline_label' => $request->payment_policies_tagline_label,
                'payment_policies_heading_title' => $request->payment_policies_heading_title,
            ];

            foreach ($fields as $key => $value) {
                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId()
                    ],
                    [
                        'value' => $value
                    ]
                );
            }

            $policies = [];

            foreach ($request->policy_title as $index => $title) {
                $policies[] = [
                    'policy_icon'        => $request->policy_icon[$index] ?? '',
                    'policy_title'       => $title,
                    'policy_tag'         => $request->policy_tag[$index] ?? '',
                    'policy_description' => $request->policy_description[$index] ?? '',
                ];
            }

            PetCareSystemSetup::updateOrCreate(
                [
                    'key' => 'payment_policies',
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId()
                ],
                [
                    'value' => json_encode($policies)
                ]
            );

            return redirect()->back()->with('success', __('The packages page setting have been saved successfully.'));
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
        return redirect()->back();
        return view('pet-care::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return redirect()->back();
        return view('pet-care::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return redirect()->back();
    }
}
