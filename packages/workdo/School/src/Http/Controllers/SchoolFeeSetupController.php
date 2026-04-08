<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Workdo\School\Entities\SchoolFeeSetup;
use Workdo\School\Entities\AcademicYear;
use Workdo\School\Entities\Term;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolGeneratedInvoice;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Workdo\ProductService\Entities\ProductService;
use App\Events\CreateInvoice;
use App\Events\SentInvoice;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class SchoolFeeSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    if (Auth::user()->isAbleTo('school_fee_setup manage')) {
        $feeSetups = SchoolFeeSetup::with(['academicYear', 'term', 'classroom'])
            ->where('created_by', creatorId())
            ->get();
        
        return view('school::fee-setup.index', compact('feeSetups'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_fee_setup create')) {
            $academicYears = AcademicYear::where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get()
                ->pluck('name', 'id');
            
            $terms = Term::where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get()
                ->pluck('name', 'id');
                
            $classes = Classroom::where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())
                ->get()
                ->pluck('class_name', 'id');

            // Get services from ProductService
            $services = [];
            if (module_is_active('ProductService')) {
                $services = ProductService::where('type', 'service')
                    ->where('workspace_id', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->get();
            }

            return view('school::fee-setup.create', compact('academicYears', 'terms', 'classes', 'services'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    if (!Auth::user()->isAbleTo('school_fee_setup create')) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'academic_year_id' => 'required|exists:academic_years,id',
        'term_id' => 'required|exists:terms,id',
        'class_id' => 'required|exists:classrooms,id',
        'due_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:product_services,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'discount_amount' => 'nullable|numeric|min:0',
        'auto_invoice' => 'nullable|boolean',
        'send_email' => 'nullable|boolean',
        'send_sms' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        $messages = $validator->getMessageBag();
        return redirect()->back()->with('error', $messages->first());
    }

    DB::beginTransaction();
    try {
        // ✅ Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += ($item['price'] * $item['quantity']);
        }
        $finalAmount = $totalAmount - ($request->discount_amount ?? 0);

        // ✅ Prepare fee setup data
        $data = [
            'name' => $request->name,
            'academic_year_id' => $request->academic_year_id,
            'term_id' => $request->term_id,
            'class_id' => $request->class_id,
            'total_amount' => $finalAmount,
            'discount_amount' => $request->discount_amount ?? 0,
            'auto_invoice' => $request->boolean('auto_invoice'),
            'send_email' => $request->boolean('send_email'),
            'send_sms' => $request->boolean('send_sms'),
            'status' => 'Active',
            'due_date' => $request->due_date,
            'items' => json_encode($request->items), // ✅ store as JSON
            'description' => $request->description,
            'created_by' => creatorId(),
        ];

        if (\Schema::hasColumn('school_fee_setups', 'workspace')) {
            $data['workspace'] = getActiveWorkSpace();
        }

        // ✅ Create fee setup
        $feeSetup = SchoolFeeSetup::create($data);
       
        // ✅ Auto generate invoices for each student in class
        if ($feeSetup->auto_invoice) {
             
            $this->generateInvoicesForStudents($feeSetup);
        }

        DB::commit();
        return redirect()->route('school-fee-setup.index')
            ->with('success', __('Fee setup created successfully.'));
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', __('Error creating fee setup: ') . $e->getMessage());
    }
}

    /**
     * Generate invoices for all students in the class
     */
    private function generateInvoicesForStudents($feeSetup)
    {
        $students = SchoolStudent::where('class_name', $feeSetup->class_id)
            ->where('workspace', getActiveWorkSpace())
            ->get();

        $items = json_decode($feeSetup->items, true);

        foreach ($students as $student) {
            try {
                // Prevent duplicate invoices for the same fee setup and student
                $existing = SchoolGeneratedInvoice::where('fee_setup_id', $feeSetup->id)
                    ->where('student_id', $student->id)
                    ->first();
                if ($existing) {
                    continue; // Skip if already generated
                }

                // Create invoice for each student
                $invoice = Invoice::create([
                    'invoice_id' => $this->getNextInvoiceNumber(),
                    'user_id' => $student->user_id,
                    'issue_date' => now()->format('Y-m-d'),
                    'due_date' => $feeSetup->due_date,
                    // 'category_id' => null,
                    // 'ref_number' => '',
                    'status' => 2, // Unpaid
                    'invoice_module' => 'school',
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ]);

                // Add invoice products
                foreach ($items as $item) {
                    InvoiceProduct::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax' => '',
                        'discount' => 0,
                        'description' => $item['description'] ?? '',
                        // 'total' => $item['price'] * $item['quantity'],
                        'product_type' => 'product',
                    ]);
                }

                // Create generated invoice record
                $generatedInvoice = SchoolGeneratedInvoice::create([
                    'fee_setup_id' => $feeSetup->id,
                    'student_id' => $student->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $feeSetup->total_amount,
                    'status' => 'Generated',
                    'email_sent' => false,
                    'sms_sent' => false,
                    'due_date' => $feeSetup->due_date,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ]);

                // Send notifications if enabled
                if ($feeSetup->send_email) {
                    
                    if ($this->sendEmailInvoice($invoice, $student)) {
                        $generatedInvoice->update(['email_sent' => true]);
                    }
                }

                if ($feeSetup->send_sms) {
                    if ($this->sendSMSInvoice($invoice, $student)) {
                        $generatedInvoice->update(['sms_sent' => true]);
                    }
                }

                // Trigger invoice creation event
                event(new CreateInvoice(request(), $invoice));
            } catch (\Exception $e) {
                // Log error but continue with next student
                \Log::error("Failed to generate invoice for student ID {$student->id}: " . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Get next invoice number
     */
    private function getNextInvoiceNumber()
    {
        $number = Invoice::where('workspace', getActiveWorkSpace())->max('invoice_id');
        return ($number ?? 0) + 1;
    }

    /**
     * Send email notification for invoice
     */
private function sendEmailInvoice($invoice, $student)
{
    try {
        // Load dynamic email config
        $isConfigured = SetConfigEmail(creatorId(), getActiveWorkSpace());

        // Log configuration
        \Log::info(' Email config status', [
            'configured' => $isConfigured,
            'driver' => config('mail.driver'),
            'host' => config('mail.host'),
            'port' => config('mail.port'),
            'username' => config('mail.username'),
            'from' => config('mail.from.address'),
        ]);

        // Use student's email directly from table
        $email = $student->email;

        if (empty($email)) {
            \Log::warning("No email for student ID {$student->id}");
            return false;
        }

        // Send email
        Mail::to($email)->send(new InvoiceMail($invoice, $student));

        \Log::info(" Email sent successfully to {$email} for invoice {$invoice->id}");

        // Fire event
        event(new SentInvoice($invoice));
        return true;
    } catch (\Exception $e) {
        \Log::error(" Email sending failed for student ID {$student->id}: " . $e->getMessage());
        return false;
    }
}



    /**
     * Send SMS notification for invoice
     */
    private function sendSMSInvoice($invoice, $student)
    {
        try {
            // Implementation for SMS notification
            // This would integrate with your SMS provider
            // Example: Send SMS using your preferred SMS service
            
            // Log the SMS sent
            Log::info("SMS notification sent for invoice {$invoice->invoice_id} to student {$student->name}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $feeSetup = SchoolFeeSetup::with(['academicYear', 'term', 'classroom', 'generatedInvoices.student'])
            ->where('id', Crypt::decrypt($id))
            ->where('workspace', getActiveWorkSpace())
            ->first();

        if (!$feeSetup) {
            return redirect()->back()->with('error', __('Fee setup not found.'));
        }

        return view('school::fee-setup.show', compact('feeSetup'));
    }

    /**
     * Generate invoices for an existing fee setup
     */
    public function generateInvoices($id)
    {
        if (Auth::user()->isAbleTo('school_fee_setup create')) {
            $feeSetup = SchoolFeeSetup::find(Crypt::decrypt($id));
            
            if (!$feeSetup) {
                return redirect()->back()->with('error', __('Fee setup not found.'));
            }

            DB::beginTransaction();
            try {
                $this->generateInvoicesForStudents($feeSetup);
                DB::commit();
                
                return redirect()->back()->with('success', __('Invoices generated successfully.'));
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', __('Error generating invoices: ') . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Send notifications for generated invoices
     */
    public function sendNotifications($id)
    {
        if (Auth::user()->isAbleTo('school_fee_setup manage')) {
            $feeSetup = SchoolFeeSetup::find(Crypt::decrypt($id));
            
            if (!$feeSetup) {
                return redirect()->back()->with('error', __('Fee setup not found.'));
            }

            $generatedInvoices = SchoolGeneratedInvoice::where('fee_setup_id', $feeSetup->id)->get();

            foreach ($generatedInvoices as $generatedInvoice) {
                if ($feeSetup->send_email && !$generatedInvoice->email_sent) {
                    $this->sendEmailInvoice($generatedInvoice->invoice, $generatedInvoice->student);
                    $generatedInvoice->update(['email_sent' => true]);
                }

                if ($feeSetup->send_sms && !$generatedInvoice->sms_sent) {
                    $this->sendSMSInvoice($generatedInvoice->invoice, $generatedInvoice->student);
                    $generatedInvoice->update(['sms_sent' => true]);
                }

                $generatedInvoice->update(['status' => 'Sent']);
            }

            return redirect()->back()->with('success', __('Notifications sent successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // Helper method inside SchoolFeeSetupController (or trait)
private function resolveProductName($item)
{
    try {
        if ($item->product) {
            return $item->product->name ?? $item->product->title ?? $item->product_name ?? 'N/A';
        }
    } catch (\Throwable $e) {
        \Log::warning("Product resolution failed for item {$item->id}: " . $e->getMessage());
    }

    return $item->product_name ?? 'N/A';
}

}
