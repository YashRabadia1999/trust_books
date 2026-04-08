<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\DataTables\DrivingInvoiceDatatable;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Workdo\DrivingSchool\Entities\DrivingInvoice;
use Workdo\DrivingSchool\Entities\DrivingInvoiceItem;
use Workdo\DrivingSchool\Entities\DrivingInvoicePayment;
use Workdo\DrivingSchool\Entities\DrivingLesson;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Events\ChangeStatusDrivingInvoice;
use Workdo\DrivingSchool\Events\CreateDrivingInvoice;
use Workdo\DrivingSchool\Events\DestroyDrivingInvoice;
use Workdo\DrivingSchool\Events\UpdateDrivingInvoice;

class DrivingInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingInvoiceDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('drivinginvoice manage')) {
            return $dataTable->render('driving-school::invoice.index');
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
        $students = DrivingStudent::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        $class = DrivingClass::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

        $invoice_number = DrivingInvoice::invoiceNumberFormat($this->invoiceNumber());
        return view('driving-school::invoice.create', compact('students', 'invoice_number', 'class'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request)
    {

        if (Auth::user()->isAbleTo('drivinginvoice create')) {

            $validator = \Validator::make($request->all(), [
                'issue_date' => 'required',
                'due_date' => 'required',
                'student' => 'required',
                'items' => 'required|array|min:1', 
                'items.*.driving_class_id' => 'required', 
                'items.*.quantity' => 'required|integer|min:1', 
                'items.*.fees' => 'required|numeric|min:0',
            ]);

            // If validation fails, redirect back with error messages
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('drivinginvoice.index')->with('error', $messages->first());
            }

            // Create new driving invoice
            $invoice = new DrivingInvoice();
            $invoice->invoice_id = $this->invoiceNumber();
            $invoice->issue_date = $request->issue_date;
            $invoice->due_date = $request->due_date;
            $invoice->student_id = $request->student;
            $invoice->created_by = Auth::user()->id;
            $invoice->workspace = getActiveWorkSpace();
            $invoice->save();


            // Create invoice items
            foreach ($request->items as $item) {
                $invoiceItem = new DrivingInvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->driving_class_id = $item['driving_class_id'];
                $invoiceItem->quantity = $item['quantity'];
                $invoiceItem->fees = $item['fees'];
                $invoiceItem->created_by = Auth::user()->id;
                $invoiceItem->workspace = getActiveWorkSpace();
                $invoiceItem->save();
            }

            // Trigger event after creating the driving invoice
            event(new CreateDrivingInvoice($request, $invoice));

            // Redirect with success message
            return redirect()->route('drivinginvoice.index')->with('success', __('The invoice has been created successfully'));
        } else {
            // Redirect back with permission denied error
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
        if (Auth::user()->isAbleTo('drivinginvoice show')) {
            $id = decrypt($id);

            $drivinginvoice = DrivingInvoice::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
            $items = DrivingInvoiceItem::where('invoice_id', $drivinginvoice->id)->get();
            $students = DrivingStudent::where('created_by', creatorId())->where('id', $drivinginvoice->student_id)->where('workspace', getActiveWorkSpace())->first();
            $amount = DrivingInvoicePayment::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('invoice_id', $id)->sum('amount');
            $dueAmount = $drivinginvoice->getTotal() - $amount;
            return view('driving-school::invoice.show', compact('drivinginvoice', 'items', 'students', 'amount', 'dueAmount'));
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
        if (Auth::user()->isAbleTo('drivinginvoice edit')) {
            $id = decrypt($id);
            $drivinginvoice = DrivingInvoice::where('id', $id)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
            $students = DrivingStudent::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $invoice_number = DrivingInvoice::invoiceNumberFormat($drivinginvoice->invoice_id);
            $student_class = DrivingClass::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('driving-school::invoice.edit', compact('drivinginvoice', 'students', 'invoice_number', 'student_class'));
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
        if (Auth::user()->isAbleTo('drivinginvoice edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'issue_date' => 'required',
                    'due_date' => 'required',
                    'items' => 'required|array|min:1',
                    'items.*.driving_class_id' => 'required',
                    'items.*.quantity' => 'required|integer|min:1',
                    'items.*.fees' => 'required|numeric|min:0',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('drivinginvoice.index')->with('error', $messages->first());
            }

            $invoice                       = DrivingInvoice::find($id);
            $invoice->invoice_id           = $invoice->invoice_id;
            $invoice->issue_date           = $request->issue_date;
            $invoice->due_date             = $request->due_date;
            $invoice->student_id           = $request->student_id;

            $invoice->save();



            $classes = $request->items;

            for ($i = 0; $i < count($classes); $i++) {
                $invoiceItem = DrivingInvoiceItem::where('id', $classes[$i]['id'])->first();
                if ($invoiceItem == null) {
                    $invoiceItem                  = new DrivingInvoiceItem();
                }
                $invoiceItem->invoice_id          = $invoice->id;
                $invoiceItem->driving_class_id    = isset($classes) ? $classes[$i]['driving_class_id'] : '';
                $invoiceItem->quantity            = $classes[$i]['quantity'];
                $invoiceItem->fees                = $classes[$i]['fees'];
                $invoiceItem->created_by          = Auth::user()->id;
                $invoiceItem->workspace           = getActiveWorkSpace();
                $invoiceItem->save();
            }
            event(new UpdateDrivingInvoice($request, $invoice));

            return redirect()->route('drivinginvoice.index')->with('success', __('The invoice details are updated successfully'));
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
        if (Auth::user()->isAbleTo('drivinginvoice delete')) {
            $drivinginvoice = DrivingInvoice::find($id);
            if ($drivinginvoice) {
                DrivingInvoiceItem::where('invoice_id', $drivinginvoice->id)->delete();
            }
            event(new DestroyDrivingInvoice($drivinginvoice));

            $drivinginvoice->delete();
            return redirect()->route('drivinginvoice.index')->with('success', __('The invoice has been deleted'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = DrivingInvoice::where('workspace', getActiveWorkSpace())->latest()->first();

        if (!$latest) {
            return 1;
        }
        return $latest->invoice_id + 1;
    }

    public function InvoiceSectionGet(Request $request)
    {
        $student = $request->student_id;
        $action = $request->action;

        $invoice = [];
        if ($action == 'edit') {
            $invoice = DrivingInvoice::find($request->invoice_id);
        }

        $student_class = DrivingClass::where('student_id', 'like', '%' . $student . '%')->get()->pluck('name', 'id');

        $returnHTML = view('driving-school::invoice.section', compact('student_class', 'action', 'invoice'))->render();

        $response = [
            'is_success' => true,
            'message' => '', // You can include any messages here if needed
            'html' => $returnHTML,
            'title' => 'Custom Title', // If you want to send any custom title back 
        ];

        return response()->json($response);
    }

    public function StudentGetItem(Request $request)
    {
        $quantity = DrivingLesson::where('class_id', $request->product_type)->count();
        $fees = DrivingClass::where('id', $request->fees)->first();
        if ($fees !== null) {
            $fees = $fees->fees;
        }
        $data = ['quantity' => $quantity, 'fees' => $fees];
        return response()->json($data);
    }

    public function items(Request $request)
    {
        $items = DrivingInvoiceItem::where('invoice_id', $request->invoice_id)->where('driving_class_id', $request->class_id)->first();
        return response()->json($items);
    }

    public function itemDestroy(Request $request)
    {
        $invoiceItem = DrivingInvoiceItem::find($request->id);

        if (!empty($invoiceItem)) {
            $invoiceItem->delete();
        }

        return response()->json(['success' => __('The invoice item has been deleted')]);
    }

    public function invoicePay(Request $request, $id)
    {
        $invoice = DrivingInvoice::find($id);

        $totalAmount = $invoice->getTotal();

        $amount = DrivingInvoicePayment::where('invoice_id', $id)->sum('amount');

        $dueAmount = $totalAmount - $amount;

        return view('driving-school::invoice.payment', compact('invoice', 'dueAmount'));
    }

    public function invoicePayForm(Request $request, $id)
    {
        if ($request->amount > $request->dueAmount) {
            return redirect()->back()->with('error', _('You can not create payment beacause your amount is greter than due amount'));
        }
        $invoice = DrivingInvoice::find($id);
        

        $invoicePayment = new DrivingInvoicePayment();
        $invoicePayment->invoice_id = $invoice->id;
        $invoicePayment->amount = $request->amount;
        $invoicePayment->workspace = getActiveWorkSpace();
        $invoicePayment->created_by =  Auth::user()->id;
        $invoicePayment->save();

        $due     = $invoice->getDue();
        if ($due <= 0) {
            $invoice->status = 2;
            $invoice->save();
        } else {
            $invoice->status = 1;
            $invoice->save();
        }

        return redirect()->route('drivinginvoice.index')->with('success', __('payment successfully pay.'));
    }

    public function invoice($invoice_id)
    {
        try {
            $invoiceId = Crypt::decrypt($invoice_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Invoice Not Found.'));
        }

        $drivinginvoice = DrivingInvoice::where('id', $invoiceId)->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->first();
        $students = DrivingStudent::where('created_by', creatorId())->where('id', $drivinginvoice->student_id)->where('workspace', getActiveWorkSpace())->first();

        $drivinginvoiceitem = DrivingInvoiceItem::where('invoice_id', $drivinginvoice->id)->get();

        $items = DrivingInvoiceItem::where('invoice_id', $drivinginvoice->id)->get();

        $amount = DrivingInvoicePayment::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('invoice_id', $invoiceId)->sum('amount');
        $dueAmount = $drivinginvoice->getTotal() - $amount;

        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting($drivinginvoice->created_by, $drivinginvoice->workspace);
        $invoice_logo = isset($company_settings['invoice_logo']) ? $company_settings['invoice_logo'] : '';
        if (isset($invoice_logo) && !empty($invoice_logo)) {
            $img  = get_file($invoice_logo);
        } else {
            $img  = $company_logo;
        }

        if ($drivinginvoice) {
            $color      = '#' . (!empty($company_settings['invoice_color']) ? $company_settings['invoice_color'] : 'ffffff');
            $font_color = User::getFontColor($color);
            $invoice_template  = (!empty($company_settings['invoice_template']) ? $company_settings['invoice_template'] : 'template1');
            $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
            $settings['footer_title'] = isset($company_settings['invoice_footer_title']) ? $company_settings['invoice_footer_title'] : '';
            $settings['footer_notes'] = isset($company_settings['invoice_footer_notes']) ? $company_settings['invoice_footer_notes'] : '';
            $settings['invoice_template'] = isset($company_settings['invoice_template']) ? $company_settings['invoice_template'] : '';
            $settings['invoice_color'] = isset($company_settings['invoice_color']) ? $company_settings['invoice_color'] : '';

            return view('driving-school::templates.template1', compact('drivinginvoice', 'students', 'settings', 'color', 'font_color', 'img', 'items', 'dueAmount', 'amount'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function chnageStatus(Request $request)
    {
        if (Auth::user()->isAbleTo('drivinginvoice status change')) {
            if (isset($request->id)) {
                $drivinginvoice         = DrivingInvoice::where('id', $request->id)->first();
                $drivinginvoice->status = $request->value;
                $drivinginvoice->save();
                event(new ChangeStatusDrivingInvoice($drivinginvoice));

                $msg = [
                    'status' => 'success',
                    'msg' => 'The status has been changed successfully',
                ];

                return $msg;
            } else {
                $msg = [
                    'status' => 'error',
                    'msg' => 'Permission denied',
                ];

                return $msg;
            }
        } else {
            $msg = [
                'status' => 'error',
                'msg' => 'Permission denied',
            ];

            return $msg;
        }
    }
}
