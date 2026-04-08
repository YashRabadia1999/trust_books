<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\PetCare\DataTables\PetAdoptionRequestDatatable;
use Workdo\PetCare\Entities\PetAdoption;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Workdo\PetCare\Events\CreatePetAdoptionRequest;
use Workdo\PetCare\Events\DestroyPetAdoptionRequest;
use Workdo\PetCare\Events\UpdatePetAdoptionRequest;

class PetAdoptionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetAdoptionRequestDatatable $datatable)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request manage')) {
            return $datatable->render('pet-care::pet_adoption_request.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($addoptionId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request create')) {
            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($addoptionId);
            $petAdoption = PetAdoption::find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet adoption not found.')], 401);
            }
            return view('pet-care::pet_adoption_request.create', compact('petAdoption', 'addoptionId'));
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
        if (\Auth::user()->isAbleTo('pet_adoption_request create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'adopter_name'          => 'required|string|max:255',
                    'email'                 => 'required|email|max:255',
                    'address'               => 'required|string|max:255',
                    'reason_for_adoption'   => 'required|string|max:1000',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('contact_number')) {
                $validator = Validator::make($request->all(), ['contact_number' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $decryptedAdoptionId = \Illuminate\Support\Facades\Crypt::decrypt($request->addoptionId);
            $petAdoption = PetAdoption::find($decryptedAdoptionId);
            if (!$petAdoption || $petAdoption->created_by != creatorId() || $petAdoption->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption not found.'));
            }

            $petAdoptionRequest                             = new PetAdoptionRequest();
            $petAdoptionRequest->adopter_name               = $request->adopter_name;
            $petAdoptionRequest->email                      = $request->email;
            $petAdoptionRequest->contact_number             = $request->contact_number;
            $petAdoptionRequest->address                    = $request->address;
            $petAdoptionRequest->reason_for_adoption        = $request->reason_for_adoption;
            $petAdoptionRequest->pet_adoption_id            = $petAdoption->id;
            $petAdoptionRequest->adoption_request_number    = self::petAdoptionRequestNumber();
            $petAdoptionRequest->request_status             = 'pending';
            $petAdoptionRequest->workspace                  = getActiveWorkSpace();
            $petAdoptionRequest->created_by                 = creatorId();
            $petAdoptionRequest->save();

            event(new CreatePetAdoptionRequest($request, $petAdoptionRequest));

            return redirect()->back()->with('success', __('The adoption request has been created successfully.'));
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
        if (\Auth::user()->isAbleTo('pet_adoption_request show')) {
            try {
                $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($id);
                $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
                if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                    return redirect()->back()->with('error', __('Pet adoption request not found.'));
                }
                $petAdoptionDetails = $petAdoptionRequest->petAdoption;
                return view('pet-care::pet_adoption_request.show', compact('petAdoptionRequest', 'petAdoptionDetails'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request edit')) {
            $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
            if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet adoption request not found.')], 401);
            }
            $petAdoptionDetails = $petAdoptionRequest->petAdoption;
            $petAdoptionAmount = $petAdoptionDetails->adoption_amount;
            return view('pet-care::pet_adoption_request.edit', compact('petAdoptionRequest', 'adoptionRequestId', 'petAdoptionDetails', 'petAdoptionAmount'));
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
    public function update(Request $request, $adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'adopter_name'          => 'required|string|max:255',
                    'email'                 => 'required|email|max:255',
                    'address'               => 'required|string|max:255',
                    'reason_for_adoption'   => 'required|string|max:1000',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('contact_number')) {
                $validator = Validator::make($request->all(), ['contact_number' => 'required|regex:/^\+\d{1,3}\d{9,13}$/']);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
            if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption request not found.'));
            }

            $petAdoptionRequest->adopter_name        = $request->adopter_name;
            $petAdoptionRequest->email               = $request->email;
            $petAdoptionRequest->contact_number      = $request->contact_number;
            $petAdoptionRequest->address             = $request->address;
            $petAdoptionRequest->reason_for_adoption = $request->reason_for_adoption;
            $petAdoptionRequest->save();

            event(new UpdatePetAdoptionRequest($request, $petAdoptionRequest));

            return redirect()->back()->with('success', __('The adoption request are updated successfully.'));
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
        if (\Auth::user()->isAbleTo('pet_adoption_request delete')) {
            $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($id);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
            if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption request not found.'));
            }
            event(new DestroyPetAdoptionRequest($petAdoptionRequest));
            $petAdoptionRequest->delete();
            return redirect()->back()->with('success', __('The adoption request are deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public static function petAdoptionRequestNumber()
    {
        $latest = PetAdoptionRequest::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }
        return $latest->adoption_request_number + 1;
    }

    public function adoptionRequestStatusEdit($adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request status update')) {
            $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
            if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                return response()->json(['error' => __('Pet adoption request not found.')], 401);
            }
            $petAdoptionRequestStatus = PetAdoptionRequest::$adoption_request_status;
            return view('pet-care::pet_adoption_request.status_update', compact('adoptionRequestId', 'petAdoptionRequestStatus', 'petAdoptionRequest'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function adoptionRequestStatusUpdate(Request $request, $adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('pet_adoption_request status update')) {
            $decryptedRequestId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedRequestId);
            if (!$petAdoptionRequest || $petAdoptionRequest->created_by != creatorId() || $petAdoptionRequest->workspace != getActiveWorkSpace()) {
                return redirect()->back()->with('error', __('Pet adoption request not found.'));
            }
            if ($request->adoption_request_status === 'completed') {
                if ($petAdoptionRequest->getAdoptionRequestDueAmount() == 0) {
                    $petAdoptionRequest->request_status = 'completed';
                    $petAdoptionRequest->save();
                } else {
                    return redirect()->back()->with('error', __('Cannot mark the adoption request as completed, Payment is still due remains.'));
                }
            } else {
                $petAdoptionRequest->request_status = $request->adoption_request_status;
                $petAdoptionRequest->save();
            }
            return redirect()->route('pet.adoption.request.index')->with('success', __('The pet adoption request status are updated successfully.'));
        } else {
            return redirect()->back()->with('error', 'Permission denied'); 
        }
    }
}
