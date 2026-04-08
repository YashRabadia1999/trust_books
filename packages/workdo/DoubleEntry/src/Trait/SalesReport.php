<?php

namespace Workdo\DoubleEntry\Trait;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

trait SalesReport
{
    public function getInvoiceItems($start, $end)
    {
        $invoiceItems = InvoiceProduct::select('product_services.name', DB::raw('sum(invoice_products.quantity) as quantity'), DB::raw('sum(invoice_products.price) as price'), DB::raw('sum(invoice_products.price)/sum(invoice_products.quantity) as avg_price'));
        $invoiceItems->leftjoin('product_services', 'product_services.id', 'invoice_products.product_id');
        $invoiceItems->leftJoin('invoices', 'invoice_products.invoice_id', 'invoices.id');
        $invoiceItems->where('invoices.created_by', creatorId());
        $invoiceItems->where('invoices.workspace', getActiveWorkSpace());
        if ($start && $end) {
            $invoiceItems->whereBetween('invoices.issue_date', [$start, $end]);
        }
        $invoiceItems->groupBy('invoice_products.product_id');
        return $invoiceItems->get()->toArray();
    }

    public function getInvoiceCustomers($start, $end)
    {
        $invoiceCustomers = Invoice::select('users.name' , DB::raw('count(DISTINCT invoices.customer_id, invoice_products.invoice_id) as invoice_count'))
            ->selectRaw('sum((invoice_products.price * invoice_products.quantity) - invoice_products.discount) as price')
            ->selectRaw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) FROM invoice_products
             LEFT JOIN taxes ON FIND_IN_SET(taxes.id, invoice_products.tax) > 0
             WHERE invoice_products.invoice_id = invoices.id) as total_tax')
            ->leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('invoice_products', 'invoice_products.invoice_id', 'invoices.id')
        ->where('invoices.created_by', creatorId())
        ->where('invoices.workspace', getActiveWorkSpace())
        ->groupBy('invoices.customer_id');
        if ($start && $end) {
            $invoiceCustomers->whereBetween('invoices.issue_date', [$start, $end]);
        }

        return $invoiceCustomers->get()->toArray();
    }
}