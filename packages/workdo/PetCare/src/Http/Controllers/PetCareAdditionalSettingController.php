<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\Entities\PetCareSystemSetup;

class PetCareAdditionalSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_additional_setting manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return view('pet-care::system_setup.additional-setting.index', ['petcare_system_setup'  => $settings]);
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
        return view('pet-care::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {   
        if (\Auth::user()->isAbleTo('petcare_additional_setting manage')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'service_tagline_label'                     =>  'required|string|max:255',                    
                    'service_title'                             =>  'required|string|max:255',                    
                    'adoption_tagline_label'                    =>  'required|string|max:255',                    
                    'adoption_title'                            =>  'required|string|max:255',                    
                    'package_tagline_label'                     =>  'required|string|max:255',
                    'package_title'                             =>  'required|string|max:255',
                    'cta_heading_title'                         =>  'required|string|max:255',
                    'cta_description'                           =>  'required|string|max:255',
                    'service_details_heading_tagline_label'     =>  'required|string|max:255',
                    'service_details_features_tagline_label'    =>  'required|string|max:255',
                    'service_details_features_heading_title'    =>  'required|string|max:255',
                    'service_details_process_tagline_label'     =>  'required|string|max:255',
                    'service_details_process_heading_title'     =>  'required|string|max:255',
                    'adoption_application_form_tagline_label'   =>  'required|string|max:255',
                    'adoption_application_form_heading_title'   =>  'required|string|max:255',
                    'appointment_booking_form_tagline_label'    =>  'required|string|max:255',
                    'appointment_booking_form_heading_title'    =>  'required|string|max:255',
                    'appointment_booking_form_response_note'    =>  'nullable|string|max:255',
                    'contact_info_start_day'                    => 'required|string',
                    'contact_info_end_day'                      => 'required|string',
                    'contact_info_open_time'                    => 'required|date_format:H:i',
                    'contact_info_close_time'                   => ['required', 'string', 'date_format:H:i',
                                                                        function ($attribute, $value, $fail) use ($request) {
                                                                            $openTime = strtotime($request->input('contact_info_open_time'));
                                                                            $closeTime = strtotime($value);
                                                                            if ($closeTime <= $openTime) {
                                                                                $fail(__('Close Time must be later than Open Time.'));
                                                                            }
                                                                        },
                                                                    ],
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            
            $Post = [
                'service_tagline_label'                     =>  $request->input('service_tagline_label'),
                'service_tagline_icon'                      =>  $request->input('service_tagline_icon'),
                'service_title'                             =>  $request->input('service_title'),
                'adoption_tagline_label'                    =>  $request->input('adoption_tagline_label'),
                'adoption_title'                            =>  $request->input('adoption_title'),
                'package_tagline_label'                     =>  $request->input('package_tagline_label'),
                'package_title'                             =>  $request->input('package_title'),
                'cta_heading_title'                         =>  $request->input('cta_heading_title'),
                'cta_description'                           =>  $request->input('cta_description'),
                'service_details_heading_tagline_label'     =>  $request->input('service_details_heading_tagline_label'),
                'service_details_features_tagline_label'    =>  $request->input('service_details_features_tagline_label'),
                'service_details_features_heading_title'    =>  $request->input('service_details_features_heading_title'),
                'service_details_process_tagline_label'     =>  $request->input('service_details_process_tagline_label'),
                'service_details_process_heading_title'     =>  $request->input('service_details_process_heading_title'),
                'adoption_application_form_tagline_label'   =>  $request->input('adoption_application_form_tagline_label'),
                'adoption_application_form_heading_title'   =>  $request->input('adoption_application_form_heading_title'),
                'appointment_booking_form_tagline_label'    =>  $request->input('appointment_booking_form_tagline_label'),
                'appointment_booking_form_heading_title'    =>  $request->input('appointment_booking_form_heading_title'),
                'appointment_booking_form_response_note'    =>  $request->input('appointment_booking_form_response_note'),
                'contact_info_start_day'                    =>  $request->input('contact_info_start_day'),
                'contact_info_end_day'                      =>  $request->input('contact_info_end_day'),
                'contact_info_open_time'                    =>  $request->input('contact_info_open_time'),
                'contact_info_close_time'                   =>  $request->input('contact_info_close_time'),
            ];
            
            foreach ($Post as $key => $value) {
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
            return redirect()->back()->with('success', __('The addtional setting have been saved successfully.'));
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
