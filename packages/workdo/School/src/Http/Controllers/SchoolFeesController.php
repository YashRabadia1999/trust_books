<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\FeesDataTable;
use Workdo\School\Entities\SchoolFees;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolFeePayment;
use Workdo\School\Events\CreateSchoolFees;
use Workdo\School\Events\DestroySchoolFees;
use Workdo\School\Events\UpdateSchoolFees;

class SchoolFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(FeesDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_fee manage')) {
            return $dataTable->render('school::fees.index');
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
        if (Auth::user()->isAbleTo('school_fee create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $status   = SchoolFees::$status;
            return view('school::fees.create' , compact('students' , 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_fee create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id' => 'required',
                    'amount'     => 'required|numeric',
                    'date'       => 'required',
                    'status'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $fees               = new SchoolFees();
            $fees->student_id   = $request->student_id;
            $fees->amount       = $request->amount;
            $fees->date         = $request->date;
            $fees->status       = $request->status;
            $fees->created_by   = creatorId();
            $fees->workspace    = getActiveWorkSpace();
            $fees->save();

            event(new CreateSchoolFees($request, $fees));

            return redirect()->back()->with('success', __('The fee has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_fee show')) {
            $id       = Crypt::decrypt($id);
            $fee      = SchoolFees::find($id);

            return view('school::fees.show', compact('fee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('school_fee edit')) {
            $id       = Crypt::decrypt($id);
            $fee      = SchoolFees::find($id);
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $status   = SchoolFees::$status;

            return view('school::fees.edit', compact('fee','students','status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('school_fee edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'student_id' => 'required',
                    'amount'     => 'required|numeric',
                    'date'       => 'required',
                    'status'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $fees               = SchoolFees::find($id);
            $fees->student_id   = $request->student_id;
            $fees->amount       = $request->amount;
            $fees->date         = $request->date;
            $fees->status       = $request->status;
            $fees->update();
            event(new UpdateSchoolFees($request, $fees));

            return redirect()->back()->with('success', __('The fee details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('school_fee delete')) {
            $fee = SchoolFees::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolFees($fee));
            $fee->delete();
            return redirect()->back()->with('success', __('The fee has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Add fee for specific student.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function addFeeForStudent(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('school_fee create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'amount'     => 'required|numeric',
                    'date'       => 'required',
                    'status'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            
            $fees               = new SchoolFees();
            $fees->student_id   = $id;
            $fees->amount       = $request->amount;
            $fees->date         = $request->date;
            $fees->status       = $request->status;
            $fees->created_by   = creatorId();
            $fees->workspace    = getActiveWorkSpace();
            $fees->save();

            event(new CreateSchoolFees($request, $fees));

            return redirect()->back()->with('success', __('The fee has been added successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Process payment for a fee
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function processPayment(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('school_fee manage')) {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string',
                'payment_date' => 'required|date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $fee = SchoolFees::findOrFail($id);
            
            // Check if payment amount doesn't exceed fee amount
            $totalPaid = $fee->payments()->sum('amount');
            $remainingAmount = $fee->amount - $totalPaid;
            
            if ($request->amount > $remainingAmount) {
                return redirect()->back()->with('error', __('Payment amount cannot exceed remaining amount: $') . number_format($remainingAmount, 2));
            }

            // Create fee payment record
            $payment = SchoolFeePayment::create([
                'fee_id' => $fee->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ]);

            // Update fee status if all paid
            $newTotalPaid = $fee->payments()->sum('amount');
            if ($newTotalPaid >= $fee->amount) {
                $fee->update(['status' => 'Paid']);
            }

            return redirect()->back()->with('success', __('Payment processed successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show payment history for a fee
     * @param int $id
     * @return Renderable
     */
    public function paymentHistory($id)
    {
        if (Auth::user()->isAbleTo('school_fee manage')) {
            $fee = SchoolFees::with('payments')->findOrFail($id);
            return view('school::fees.payment-history', compact('fee'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showInvoice($id)
{
    $invoiceId = decrypt($id);
    $invoice = Invoice::with('student', 'items')->findOrFail($invoiceId);

    return view('school::invoice.show', compact('invoice'));
}
}
