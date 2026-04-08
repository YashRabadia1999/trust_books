<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\DataTables\PetAdoptionDatatable;
use Workdo\PetCare\DataTables\PetAppointmentDatatable;
use Workdo\PetCare\Entities\PetAdoption;
use Workdo\PetCare\Events\CreatePetAdoption;
use Workdo\PetCare\Events\DestroyPetAdoption;
use Workdo\PetCare\Events\UpdatePetAdoption;

class PetAdoptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetAdoptionDatatable $datatable)
    {
        if (\Auth::user()->isAbleTo('pet_adoption manage')) {
            return $datatable->render('pet-care::pet_adoption.index');
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
        if (\Auth::user()->isAbleTo('pet_adoption create')) {
            $availability_status = PetAdoption::$availability;
            $health_status = PetAdoption::$health_status;
            return view('pet-care::pet_adoption.create', compact('availability_status', 'health_status'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('pet_adoption create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'pet_name'            => 'required|string|max:255',
                    'species'             => 'required|string|max:255',
                    'breed'               => 'required|string|max:255',
                    'adoption_amount'     => 'required|numeric|min:0',
                    'date_of_birth'       => 'required|date',
                    'gender'              => 'required|in:Male,Female',
                    'availability'        => 'required|string',
                    'health_status'       => 'required|string',
                    'classification_tags' => 'required|string',
                    'pet_image'           => 'required|image',
                    'description'         => 'required|string',
                ]
            );

            $tags = array_filter(array_map('trim', explode(',', $request->classification_tags)));
            if (count($tags) > 4) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('classification_tags', __('You can select a maximum of 4 classification tags.'));
                });
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            try {
                if ($request->hasFile('pet_image')) {
                    $filenameWithExt = $request->file('pet_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('pet_image')->getClientOriginalExtension();
                    $fileName = $filename . '_' . time() . '.' . $extension;
                    $upload = upload_file($request, 'pet_image', $fileName, 'pet_adoption');
                    if ($upload['flag'] == 1) {
                        $pet_image_path = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petAdoption = new PetAdoption();
                $petAdoption->adoption_number     = $this->petAdoptionNumber();
                $petAdoption->pet_name            = $request->pet_name;
                $petAdoption->species             = $request->species;
                $petAdoption->breed               = $request->breed;
                $petAdoption->adoption_amount     = $request->adoption_amount;
                $petAdoption->date_of_birth       = $request->date_of_birth ?? null;
                $petAdoption->gender              = $request->gender;
                $petAdoption->availability        = $request->availability;
                $petAdoption->health_status       = $request->health_status;
                $petAdoption->classification_tags = $request->classification_tags;
                $petAdoption->pet_image           = !empty($request->pet_image) ? $pet_image_path : '';
                $petAdoption->description         = $request->description;
                $petAdoption->workspace           = getActiveWorkSpace();
                $petAdoption->created_by          = creatorId();
                $petAdoption->save();

                event(new CreatePetAdoption($request, $petAdoption));

                return redirect()->back()->with('success', __('The pet adoption has been created successfully'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to submit pet adoption: ') . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($AdoptionId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption show')) {

            if (!request()->ajax()) {
                return redirect()->back();
            }

            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($AdoptionId);
            $petAdoption = PetAdoption::find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet adoption not found.')], 401);
            }
            return view('pet-care::pet_adoption.show', compact('AdoptionId', 'petAdoption'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($AdoptionId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption edit')) {
            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($AdoptionId);
            $petAdoption = PetAdoption::find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet adoption not found.')], 401);
            }
            $availability_status = PetAdoption::$availability;
            $health_status = PetAdoption::$health_status;
            return view('pet-care::pet_adoption.edit', compact('AdoptionId', 'petAdoption', 'availability_status', 'health_status'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $AdoptionId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'pet_name'            => 'required|string|max:255',
                    'species'             => 'required|string|max:255',
                    'breed'               => 'required|string|max:255',
                    'adoption_amount'     => 'required|numeric|min:0',
                    'date_of_birth'       => 'required|date',
                    'gender'              => 'required|in:Male,Female',
                    'availability'        => 'required|string',
                    'health_status'       => 'required|string',
                    'classification_tags' => 'required|string',
                    'pet_image'           => 'nullable|image',
                    'description'         => 'required|string',
                ]
            );

            $tags = array_filter(array_map('trim', explode(',', $request->classification_tags)));
            if (count($tags) > 4) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('classification_tags', __('You can select a maximum of 4 classification tags.'));
                });
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($AdoptionId);
            $petAdoption = PetAdoption::where('id', $decryptedAdoptionId)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            try {
                if ($request->hasFile('pet_image')) {
                    $filenameWithExt = $request->file('pet_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('pet_image')->getClientOriginalExtension();
                    $fileName = $filename . '_' . time() . '.' . $extension;
                    $upload = upload_file($request, 'pet_image', $fileName, 'pet_adoption');
                    if ($upload['flag'] == 1) {
                        $pet_image_path = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petAdoption->pet_name            = $request->pet_name;
                $petAdoption->species             = $request->species;
                $petAdoption->breed               = $request->breed;
                $petAdoption->adoption_amount     = $request->adoption_amount;
                $petAdoption->date_of_birth       = $request->date_of_birth ?? null;
                $petAdoption->gender              = $request->gender;
                $petAdoption->availability        = $request->availability;
                $petAdoption->health_status       = $request->health_status;
                $petAdoption->classification_tags = $request->classification_tags;
                $petAdoption->description         = $request->description;
                $petAdoption->save();

                if ($request->hasFile('pet_image')) {
                    $petAdoption->pet_image = !empty($request->pet_image) ? $pet_image_path : '';
                    $petAdoption->save();
                }

                event(new UpdatePetAdoption($request, $petAdoption));

                return redirect()->back()->with('success', __('The pet adoption has been updated successfully'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to submit pet adoption: ') . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($AdoptionId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption delete')) {
            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($AdoptionId);
            $petAdoption = PetAdoption::find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            event(new DestroyPetAdoption($petAdoption));

            if (!empty($petAdoption->pet_image) && check_file($petAdoption->pet_image)) {
                delete_file($petAdoption->pet_image);
            }

            $petAdoption->delete();
            return redirect()->back()->with('success', __('The pet adoption has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function petAdoptionNumber()
    {
        $latest = PetAdoption::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->adoption_number + 1;
    }
}
