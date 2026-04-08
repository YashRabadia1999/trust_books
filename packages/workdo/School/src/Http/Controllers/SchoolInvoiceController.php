<?php

namespace Workdo\School\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Workdo\School\Models\Invoice; 
use Workdo\School\Entities\SchoolGeneratedInvoice;
class SchoolInvoiceController extends Controller
{
   public function show($id)
{
    $invoiceId = decrypt($id);

    // Fetch SchoolGeneratedInvoice by invoice_id, not by id
    $invoice = SchoolGeneratedInvoice::with(['student', 'items','product'])
        ->where('invoice_id', $invoiceId)
        ->firstOrFail();

    return view('school::fee-setup.invoiceshow', compact('invoice'));
}

}
