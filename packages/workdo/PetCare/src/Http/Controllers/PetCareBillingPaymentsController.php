<?php

namespace Workdo\PetCare\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\PetCare\DataTables\PetCareBillingPaymentsDatatable;
use Workdo\PetCare\DataTables\PetCarePaymentSummaryDatatable;
use Workdo\PetCare\Entities\PetAppointment;
use Workdo\PetCare\Entities\PetCareBillingPayments;
use Workdo\PetCare\Events\CreatePetCareBillingPayment;

class PetCareBillingPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PetCareBillingPaymentsDatatable $dataTable)
    {
        if (\Auth::user()->isAbleTo('billing_payments manage')) {
            return $dataTable->render('pet-care::billing_payments.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($appointmentId)
    {
        if (\Auth::user()->isAbleTo('billing_payments create')) {
            $payment_method = PetCareBillingPayments::$payment_method;
            $decryptedAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($appointmentId);
            $petAppointment = PetAppointment::find($decryptedAppointmentId);
            $getDueAmount = $petAppointment->getDueAmount();
            return view('pet-care::billing_payments.create', compact('appointmentId', 'payment_method', 'getDueAmount'));
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
        if (\Auth::user()->isAbleTo('billing_payments create')) {
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

            $decryptedAppointmentId = \Illuminate\Support\Facades\Crypt::decrypt($request->appointmentId);
            $petAppointment = PetAppointment::find($decryptedAppointmentId);

            if ($petAppointment->appointment_status != 'approved') {
                return redirect()->back()->with('error', __('Appointment approval is required. Please change the status to "Approved" before proceeding.'));
            }

            if ($petAppointment->getDueAmount() < (float)$request->amount) {
                return redirect()->back()->with('error', __('Please enter valid amount.'));
            }

            try {
                if ($request->add_receipt) {

                    $validation = [
                        'max:' . '20480',
                    ];
                    $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                    $upload = upload_file($request, 'add_receipt', $fileName, 'petcare_billing_payment_receipt', $validation);
                    if ($upload['flag'] == 1) {
                        $payment_receipt = $upload['url'];
                    } else {
                        return redirect()->back()->with('error', $upload['msg']);
                    }
                }

                $petcarePayment = new PetCareBillingPayments();
                $petcarePayment->appointment_id   = $decryptedAppointmentId;
                $petcarePayment->payer_name       = $request->payer_name;
                $petcarePayment->payment_date     = $request->payment_date;
                $petcarePayment->amount           = $request->amount;
                $petcarePayment->description      = $request->description ?? null;
                $petcarePayment->reference        = $request->reference;
                $petcarePayment->payment_status   = 'paid';
                $petcarePayment->payment_method   = $request->payment_method;
                $petcarePayment->payment_receipt  = !empty($payment_receipt) ? $payment_receipt : '';
                $petcarePayment->workspace        = getActiveWorkSpace();
                $petcarePayment->created_by       = creatorId();
                $petcarePayment->save();

                event(new CreatePetCareBillingPayment($request, $petcarePayment));

                return redirect()->route('petcare.billing.payments.index')->with('success', __('The payment has been created successfully.'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Failed to submit payment: ') . $e->getMessage());
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
    public function show(PetCarePaymentSummaryDatatable $dataTable, $pet_appointment_id)
    {   
        if (\Auth::user()->isAbleTo('billing_payments show')) {
            try {
                return $dataTable->with(['pet_appointment_id' => $pet_appointment_id])->render('pet-care::billing_payments.show');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', $th->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function description($id)
    {
        $petcarePayment = PetCareBillingPayments::find($id);
        if (!$petcarePayment || $petcarePayment->created_by != creatorId() || $petcarePayment->workspace != getActiveWorkSpace()) {
            return response()->json(['error' => __('PetCare Payment not found.')], 401);
        }
        return view('pet-care::billing_payments.description', compact('petcarePayment'));
    }
}
