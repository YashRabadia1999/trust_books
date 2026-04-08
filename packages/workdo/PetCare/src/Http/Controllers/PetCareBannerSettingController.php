<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\Entities\PetCareSystemSetup;

class PetCareBannerSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_banner_setting manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            if (isset($settings['banner_images'])) {
                $decodedbannerImages = json_decode($settings['banner_images'], true) ?? [];
            } else {
                $decodedbannerImages = [];
            }

            return view('pet-care::system_setup.banner-setting.index', [
                'petcare_system_setup'  => $settings,
                'decodedbannerImages' => $decodedbannerImages
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
        if (\Auth::user()->isAbleTo('petcare_banner_setting manage')) {

            $validator = \Validator::make($request->all(), [
                'banner_tagline'           => 'required|string|max:255',
                'banner_heading_title'     => 'required|string|max:255',
                'banner_sub_title'         => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            if ($request->hasFile('banner_decorative_image')) {

                $existingImage = PetCareSystemSetup::where('key', 'banner_decorative_image')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                if (!empty($existingImage->value) && check_file($existingImage->value)) {
                    delete_file($existingImage->value);
                }

                $file = $request->file('banner_decorative_image');
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileName = creatorId() . '_banner_decorative_image' . '_' . uniqid() . '.' . $extension;

                $upload = upload_file($request, "banner_decorative_image", $fileName, 'petcare_banner_images');

                if ($upload['flag'] == 1) {
                    $imagePaths = $upload['url'];
                } else {
                    return redirect()->back()->with('error', $upload['msg']);
                }

                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => 'banner_decorative_image',
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => $imagePaths,
                    ]
                );
            }

            if ($request->hasFile('banner_images')) {
                $uploadedImages = $request->file('banner_images');
                $imagePaths = [];

                $existing = PetCareSystemSetup::where('key', 'banner_images')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();

                $oldImages = [];
                if ($existing && !empty($existing->value)) {
                    $oldImages = json_decode($existing->value, true);
                }

                $imagePaths = $oldImages;

                foreach ($uploadedImages as $index => $file) {
                    
                    if (isset($oldImages[$index]) && check_file($oldImages[$index])) {
                        delete_file($oldImages[$index]);
                    }

                    $filenameWithExt = $file->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileName = creatorId() . '_banner' . ($index + 1) . '_' . uniqid() . '.' . $extension;

                    $upload = upload_file($request, "banner_images.$index", $fileName, 'petcare_banner_images');

                    if ($upload['flag'] == 1) {
                        $imagePaths[$index] = $upload['url']; // Replace at same index
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => 'banner_images',
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => json_encode($imagePaths),
                    ]
                );
            }

            $textFields = [
                'banner_tagline' => $request->input('banner_tagline'),
                'banner_heading_title' => $request->input('banner_heading_title'),
                'banner_sub_title' => $request->input('banner_sub_title'),
            ];

            foreach ($textFields as $key => $value) {
                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }

            return redirect()->back()->with('success', __('The Banner settings details have been saved successfully.'));
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
