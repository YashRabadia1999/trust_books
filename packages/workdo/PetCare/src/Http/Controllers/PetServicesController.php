<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\DataTables\PetServiceDatatable;
use Workdo\PetCare\Entities\PetService;
use Workdo\PetCare\Entities\PetServiceIncludedFeatures;
use Workdo\PetCare\Entities\PetServiceProcessSteps;
use Workdo\PetCare\Events\CreatePetService;
use Workdo\PetCare\Events\DestroyPetService;
use Workdo\PetCare\Events\UpdatePetService;

class PetServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetServiceDatatable $dataTable)
    {
        if (\Auth::user()->isAbleTo('pet_services manage')) {
            return $dataTable->render('pet-care::pet_services.index');
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
        if (\Auth::user()->isAbleTo('pet_services create')) {
            return view('pet-care::pet_services.create');
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
        if (\Auth::user()->isAbleTo('pet_services create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'service_icon' => 'required|string|max:255',
                    'service_name' => 'required|max:255',
                    'description'  => 'required|string',
                    'price'        => 'required|numeric|min:0',
                    'duration'     => 'required|numeric|min:1',
                    'service_image'  => 'required|image',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            try {
                if ($request->hasFile('service_image')) {
                    $filenameWithExt = $request->file('service_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('service_image')->getClientOriginalExtension();
                    $fileName = $filename . '_' . time() . '.' . $extension;
                    $upload = upload_file($request, 'service_image', $fileName, 'pet_services');
                    if ($upload['flag'] == 1) {
                        $service_image_path = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petService = new PetService();
                $petService->service_name   = $request->service_name;
                $petService->service_icon   = $request->service_icon;
                $petService->description    = $request->description;
                $petService->price          = $request->price;
                $petService->duration       = $request->duration;
                $petService->service_image  = !empty($request->service_image) ? $service_image_path : '';
                $petService->workspace      = getActiveWorkSpace();
                $petService->created_by     = creatorId();
                $petService->save();

                event(new CreatePetService($request, $petService));
                return redirect()->back()->with('success', __('The pet service has been created successfully.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to submit pet service: ') . $e->getMessage());
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
        if (\Auth::user()->isAbleTo('pet_services edit')) {
            $petService = \Workdo\PetCare\Entities\PetService::find($id);
            if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet Service not found.')], 401);
            }
            return view('pet-care::pet_services.edit', compact('petService'));
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
        if (\Auth::user()->isAbleTo('pet_services edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'service_icon' => 'required|string|max:255',
                    'service_name' => 'required|max:255',
                    'description'  => 'required|string',
                    'price'        => 'required|numeric|min:0',
                    'duration'     => 'required|numeric|min:1',
                    'service_image'  => 'nullable|image',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $petService = PetService::find($id);
            if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet Service not found.'));
            }

            try {
                if ($request->hasFile('service_image')) {
                    $filenameWithExt = $request->file('service_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('service_image')->getClientOriginalExtension();
                    $fileName = $filename . '_' . time() . '.' . $extension;
                    $upload = upload_file($request, 'service_image', $fileName, 'pet_services');
                    if ($upload['flag'] == 1) {
                        $service_image_path = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petService->service_name  = $request->service_name;
                $petService->service_icon  = $request->service_icon;
                $petService->description   = $request->description;
                $petService->price         = $request->price;
                $petService->duration      = $request->duration;
                if ($request->hasFile('service_image')) {
                    $petService->service_image = !empty($request->service_image) ? $service_image_path : '';
                }
                $petService->save();

                event(new UpdatePetService($request, $petService));
                return redirect()->back()->with('success', __('The pet service details are updated successfully.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to submit pet service: ') . $e->getMessage());
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
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('pet_services delete')) {
            $petService = \Workdo\PetCare\Entities\PetService::find($id);
            if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet Service not found.'));
            }
            event(new DestroyPetService($petService));

            if (!empty($petService->service_image) && check_file($petService->service_image)) {
                delete_file($petService->service_image);
            }

            $petService->delete();
            return redirect()->back()->with('success', __('The pet service has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function description($id)
    {
        $petService = PetService::find($id);
        if (!$petService || $petService->created_by != creatorId() || $petService->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Pet Service not found.')], 401);
        }
        return view('pet-care::pet_services.description', compact('petService'));
    }
}
