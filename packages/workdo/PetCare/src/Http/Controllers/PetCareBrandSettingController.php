<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\Entities\PetCareSystemSetup;

class PetCareBrandSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_brand_setting manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            $petcare_logo = !empty($settings['petcare_logo']) && check_file($settings['petcare_logo']) ? $settings['petcare_logo'] : 'packages/workdo/PetCare/src/Resources/assets/image/petcare_logo.png';
            $petcare_favicon = !empty($settings['petcare_favicon']) && check_file($settings['petcare_favicon']) ? $settings['petcare_favicon'] : 'packages/workdo/PetCare/src/Resources/assets/image/favicon.png';

            return view('pet-care::system_setup.brand-setting.index', [
                'petcare_logo'   => $petcare_logo,
                'petcare_favicon' => $petcare_favicon,
                'petcare_system_setup'  => $settings,
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
        return view('pet-care::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('petcare_brand_setting manage')) {
            $validator = \Validator::make($request->all(), [
                'petcare_footer_title'        => 'required|string|max:55',
                'petcare_footer_text'         => 'required|string',
                'petcare_footer_link_text'    => 'required|string',
                'petcare_footer_link_url'     => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            // Step 1: Save text fields
            $Textfields = [
                'petcare_footer_title'        => $request->input('petcare_footer_title'),
                'petcare_footer_text'         => $request->input('petcare_footer_text'),
                'petcare_footer_link_text'    => $request->input('petcare_footer_link_text'),
                'petcare_footer_link_url'     => $request->input('petcare_footer_link_url'),
            ];

            $imageFields = [
                'petcare_logo'   => '_petcare_logo',
                'petcare_favicon' => '_petcare_favicon',
            ];

            foreach ($imageFields as $inputName => $suffix) {
                if ($request->hasFile($inputName)) {

                    $existing = PetCareSystemSetup::where('key', $inputName)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();

                    if ($existing && !empty($existing->value) && check_file($existing->value)) {
                        delete_file($existing->value);
                    }

                    $file = $request->file($inputName);
                    $fileName = creatorId() . $suffix . '.' . $file->getClientOriginalExtension();

                    $upload = upload_file($request, $inputName, $fileName, 'petcare_brand_setting');

                    if ($upload['flag'] == 1) {
                        $Textfields[$inputName] = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }
            }

            foreach ($Textfields as $key => $value) {
                PetCareSystemSetup::updateOrCreate(
                    [
                        'key'        => $key,
                        'workspace'  => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }

            return redirect()->back()->with('success', __('The brand setting details have been saved successfully.'));
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
