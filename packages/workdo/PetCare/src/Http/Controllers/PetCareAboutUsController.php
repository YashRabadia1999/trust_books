<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\Entities\PetCareSystemSetup;

class PetCareAboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('petcare_about_us manage')) {
            $petcare_settings = PetCareSystemSetup::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();

            $settings = [];

            foreach ($petcare_settings as $setting) {
                $settings[$setting->key] = $setting->value;
            }

            return view('pet-care::system_setup.about_us.index', ['petcare_system_setup'  => $settings]);
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
        if (\Auth::user()->isAbleTo('petcare_about_us manage')) {

            $validator = \Validator::make($request->all(), [
                'about_us_title'            => 'required|string|max:255',
                'about_us_description'      => 'required|string',
                'milestones_tagline_label'     =>  'required|string|max:255',
                'milestones_title'             =>  'required|string|max:255',
                'team_member_tagline_label'    =>  'required|string|max:255',
                'team_member_title'            =>  'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->getMessageBag()->first());
            }

            if ($request->hasFile('about_us_image')) {

                $existingImage = PetCareSystemSetup::where('key', 'about_us_image')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                if (!empty($existingImage->value) && check_file($existingImage->value)) {
                    delete_file($existingImage->value);
                }

                $filenameWithExt = $request->file('about_us_image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('about_us_image')->getClientOriginalExtension();
                $fileName = creatorId() . '_' . $filename . '_' . uniqid() . '.' . $extension;
                $upload = upload_file($request, 'about_us_image', $fileName, 'petcare_about_us_image');
                if ($upload['flag'] == 1) {
                    $about_us_image_path = $upload['url'];
                } else {
                    return redirect()->back()->with('error', $upload['msg']);
                }

                PetCareSystemSetup::updateOrCreate(
                    [
                        'key' => 'about_us_image',
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ],
                    [
                        'value' => !empty($request->about_us_image) ? $about_us_image_path : '',
                    ]
                );
            }

            $textFields = [
                'about_us_title' => $request->input('about_us_title'),
                'about_us_description' => $request->input('about_us_description'),
                'milestones_tagline_label' => $request->input('milestones_tagline_label'),
                'milestones_title' => $request->input('milestones_title'),
                'team_member_tagline_label' => $request->input('team_member_tagline_label'),
                'team_member_title' => $request->input('team_member_title'),
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

            return redirect()->back()->with('success', __('The about us details have been saved successfully.'));
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
