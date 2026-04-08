<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\DataTables\PetVaccineDatatable;
use Workdo\PetCare\Entities\PetVaccine;
use Workdo\PetCare\Events\CreatePetVaccine;
use Workdo\PetCare\Events\DestroyPetVaccine;
use Workdo\PetCare\Events\UpdatePetVaccine;

class PetVaccinesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetVaccineDatatable $dataTable)
    {
        if (\Auth::user()->isAbleTo('pet_vaccines manage')) {
            return $dataTable->render('pet-care::pet_vaccines.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('pet_vaccines create')) {
            return view('pet-care::pet_vaccines.create');
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
        if (\Auth::user()->isAbleTo('pet_vaccines create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'vaccine_name' => 'required|max:255',
                    'description'  => 'required|string',
                    'price'        => 'required|numeric|min:0',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $petVaccine = new PetVaccine();
            $petVaccine->vaccine_name = $request->vaccine_name;
            $petVaccine->description  = $request->description;
            $petVaccine->price        = $request->price;
            $petVaccine->workspace    = getActiveWorkSpace();
            $petVaccine->created_by   = creatorId();
            $petVaccine->save();

            event(new CreatePetVaccine($request, $petVaccine));
            return redirect()->back()->with('success', __('Pet Vaccine created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
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
        if (\Auth::user()->isAbleTo('pet_vaccines edit')) {
            $petVaccine = PetVaccine::find($id);
            if (!$petVaccine || $petVaccine->created_by != creatorId() || $petVaccine->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet Vaccine not found.')], 401);
            }
            return view('pet-care::pet_vaccines.edit', compact('petVaccine'));
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
    public function update(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('pet_vaccines edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'vaccine_name' => 'required|max:255',
                    'description'  => 'required|string',
                    'price'        => 'required|numeric|min:0',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $petVaccine = PetVaccine::find($id);
            if (!$petVaccine || $petVaccine->created_by != creatorId() || $petVaccine->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet Vaccine not found.'));
            }

            $petVaccine->vaccine_name = $request->vaccine_name;
            $petVaccine->description  = $request->description;
            $petVaccine->price        = $request->price;
            $petVaccine->save();

            event(new UpdatePetVaccine($request, $petVaccine));
            return redirect()->back()->with('success', __('Pet Vaccine updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('pet_vaccines delete')) {
            $petVaccine = PetVaccine::find($id);
            if (!$petVaccine || $petVaccine->created_by != creatorId() || $petVaccine->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet Vaccine not found.'));
            }
            event(new DestroyPetVaccine($petVaccine));
            $petVaccine->delete();
            return redirect()->back()->with('success', __('Pet Vaccine deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function description($id)
    {
        $petVaccine = PetVaccine::find($id);
        if (!$petVaccine || $petVaccine->created_by != creatorId() || $petVaccine->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Pet Vaccine not found.')], 401);
        }
        return view('pet-care::pet_vaccines.description', compact('petVaccine'));
    }
}
