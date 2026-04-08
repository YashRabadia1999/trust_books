<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\DataTables\PetAdoptionRequestPaymentsDatatable;
use Workdo\PetCare\DataTables\PetAdoptionRequestPaymentsSummaryDatatable;
use Workdo\PetCare\Entities\PetAdoptionRequest;
use Workdo\PetCare\Entities\PetAdoptionRequestPayments;
use Workdo\PetCare\Events\CreatePetAdoptionRequestPayment;

class PetAdoptionRequestPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetAdoptionRequestPaymentsDatatable $datatable)
    {
        if (\Auth::user()->isAbleTo('adoption_request_payments manage')) {
            return $datatable->render('pet-care::adoption_request_payments.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('adoption_request_payments create')) {
            $payment_method = PetAdoptionRequestPayments::$payment_method;
            $decryptedAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedAppointmentId);
            $getAdoptionRequestDueAmount = $petAdoptionRequest->getAdoptionRequestDueAmount();
            return view('pet-care::adoption_request_payments.create', compact('adoptionRequestId', 'payment_method', 'getAdoptionRequestDueAmount'));
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
        if (\Auth::user()->isAbleTo('adoption_request_payments create')) {
            $validator = \Validator::make($request->all(), [
                'payer_name' => 'required|string',
                'amount' => 'required|string|numeric',
                'payment_date' => 'required|string',
                'payment_method' => 'required|string',
                'description' => 'required|string',
                'reference' => 'required|string',
                'add_receipt' => 'image|mimes:png|max:20480',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $decryptedAdoptionRequestId = \Illuminate\Support\Facades\Crypt::decrypt($request->adoptionRequestId);
            $petAdoptionRequest = PetAdoptionRequest::find($decryptedAdoptionRequestId);

            if ($petAdoptionRequest->request_status != 'approved') {
                return redirect()->back()->with('error', __('Adoption request approval is required. Please change the status to "Approved" before proceeding.'));
            }

            if ($petAdoptionRequest->getAdoptionRequestDueAmount() < (float)$request->amount) {
                return redirect()->back()->with('error', __('Please enter valid amount.'));
            }

            try {
                if ($request->add_receipt) {

                    $validation = [
                        'max:' . '20480',
                    ];
                    $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                    $upload = upload_file($request, 'add_receipt', $fileName, 'pet_adoption_request_payment_receipt', $validation);
                    if ($upload['flag'] == 1) {
                        $payment_receipt = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petAdoptionRequestPayments                             = new PetAdoptionRequestPayments();
                $petAdoptionRequestPayments->adoption_request_id        = $decryptedAdoptionRequestId;
                $petAdoptionRequestPayments->payer_name                 = $request->payer_name;
                $petAdoptionRequestPayments->payment_date               = $request->payment_date;
                $petAdoptionRequestPayments->amount                     = $request->amount;
                $petAdoptionRequestPayments->reference                  = $request->reference;
                $petAdoptionRequestPayments->description                = $request->description ?? null;
                $petAdoptionRequestPayments->payment_receipt            = !empty($payment_receipt) ? $payment_receipt : '';
                $petAdoptionRequestPayments->payment_method             = $request->payment_method;
                $petAdoptionRequestPayments->payment_status             = 'paid';
                $petAdoptionRequestPayments->workspace                  = getActiveWorkSpace();
                $petAdoptionRequestPayments->created_by                 = creatorId();
                $petAdoptionRequestPayments->save();

                event(new CreatePetAdoptionRequestPayment($request, $petAdoptionRequestPayments));

                return redirect()->back()->with('success', __('Adoption Request Payment created successfully.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(PetAdoptionRequestPaymentsSummaryDatatable $dataTable, $adoptionRequestId)
    {
        if (\Auth::user()->isAbleTo('adoption_request_payments show')) {
            try {
                return $dataTable->with(['adoption_request_id' => $adoptionRequestId])->render('pet-care::adoption_request_payments.show');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($adoptionRequestId)
    {
        $petAdoptionRequestPayment = PetAdoptionRequestPayments::find($adoptionRequestId);
        if (!$petAdoptionRequestPayment || $petAdoptionRequestPayment->created_by != creatorId() || $petAdoptionRequestPayment->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('Adoption Request Payment not found.')], 401);
        }
        return view('pet-care::adoption_request_payments.description', compact('petAdoptionRequestPayment'));
    }
}
