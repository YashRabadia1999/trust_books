<?php

namespace Workdo\DoubleEntry\Trait;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait SalesReceivable
{
    /**
     * Get customers list
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getCustomers()
    {
        return User::where('workspace_id', '=', getActiveWorkSpace())->where('type','client')
            ->get()
            ->pluck('name', 'id');
    }

    /**
     * Get receivable customers data
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getReceivableCustomers($start = null, $end = null, $customerId = null)
    {
        $query = Invoice::select([
            'users.name as name',
            DB::raw('(SELECT SUM((price * quantity - discount)) FROM invoice_products WHERE invoice_products.invoice_id = invoices.id) as price'),
            DB::raw('(SELECT SUM(amount) FROM invoice_payments WHERE invoice_payments.invoice_id = invoices.id) as pay_price'),
            DB::raw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) 
                      FROM invoice_products 
                      LEFT JOIN taxes ON FIND_IN_SET(taxes.id, invoice_products.tax) > 0 
                      WHERE invoice_products.invoice_id = invoices.id) as total_tax'),
            DB::raw('(SELECT SUM(amount) FROM credit_notes WHERE credit_notes.invoice = invoices.id) as credit_price'),
        ])
        ->leftJoin('users', 'users.id', '=', 'invoices.user_id')
        ->where('invoices.created_by', creatorId())
        ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('invoices.issue_date', [$start, $end]);
        }

        return $query->get()->toArray();
    }

    /**
     * Get receivable summaries
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getReceivableSummaries($start = null, $end = null, $customerId = null)
    {
        $invoiceSummaries = $this->getInvoiceSummaries($start, $end, $customerId);
        $creditSummaries = $this->getCreditSummaries($start, $end, $customerId);

        return array_merge($creditSummaries, $invoiceSummaries);
    }

    /**
     * Get invoice summaries
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getInvoiceSummaries($start = null, $end = null, $customerId = null)
    {
        $query = Invoice::select([
            'users.name as name',
            'invoices.id as invoice',
            'invoices.issue_date',
            'invoices.status',
            DB::raw('(SELECT SUM((price * quantity - discount)) 
                      FROM invoice_products 
                      WHERE invoice_products.invoice_id = invoices.id) as price'),
            DB::raw('(SELECT SUM(amount) 
                      FROM invoice_payments 
                      WHERE invoice_payments.invoice_id = invoices.id) as pay_price'),
            DB::raw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) 
                      FROM invoice_products 
                      LEFT JOIN taxes ON FIND_IN_SET(taxes.id, invoice_products.tax) > 0 
                      WHERE invoice_products.invoice_id = invoices.id) as total_tax'),
        ])
        ->leftJoin('users', 'users.id', '=', 'invoices.user_id')
        ->where('invoices.created_by', creatorId())
        ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('invoices.issue_date', [$start, $end]);
        }

        return $query->get()->toArray();
    }

    /**
     * Get credit summaries
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getCreditSummaries($start = null, $end = null, $customerId = null)
    {
        $query = \Workdo\Account\Entities\CreditNote::select([
            'users.name as name',
            DB::raw('null as invoice'),
            'credit_notes.date as issue_date',
            DB::raw('5 as status'),
            'credit_notes.amount as price',
            DB::raw('0 as pay_price'),
            DB::raw('0 as total_tax')
        ])
        ->leftJoin('users', 'users.id', '=', 'credit_notes.customer')
        ->leftJoin('invoices', 'invoices.id', '=', 'credit_notes.invoice')
        ->where('invoices.created_by', creatorId())
        ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('credit_notes.date', [$start, $end]);
        }

        return $query->get()->toArray();
    }

    /**
     * Get receivable details
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getReceivableDetails($start = null, $end = null, $customerId = null)
    {
        $invoiceDetails = $this->getInvoiceDetails($start, $end, $customerId);
        $creditDetails = $this->getCreditDetails($start, $end, $customerId);

        return array_merge($creditDetails, $invoiceDetails);
    }

    /**
     * Get invoice details
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getInvoiceDetails($start = null, $end = null, $customerId = null)
    {
        $query = Invoice::select('users.name')
            ->selectRaw('(invoices.invoice_id) as invoice')
            ->selectRaw('sum(invoice_products.price) as price')
            ->selectRaw('(invoice_products.quantity) as quantity')
            ->selectRaw('(product_services.name) as product_name')
            ->selectRaw('invoices.issue_date as issue_date')
            ->selectRaw('invoices.status as status')
            ->leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('invoice_products', 'invoice_products.invoice_id', 'invoices.id')
            ->leftJoin('product_services', 'product_services.id', 'invoice_products.product_id')
            ->where('invoices.created_by', creatorId())
            ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('invoices.issue_date', [$start, $end]);
        }

        return $query->groupBy('invoices.invoice_id', 'product_services.name')
            ->get()
            ->toArray();
    }

    /**
     * Get credit details
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getCreditDetails($start = null, $end = null, $customerId = null)
    {
        $query = \Workdo\Account\Entities\CreditNote::select('users.name')
            ->selectRaw('null as invoice')
            ->selectRaw('(credit_notes.id) as invoices')
            ->selectRaw('(credit_notes.amount) as price')
            ->selectRaw('(product_services.name) as product_name')
            ->selectRaw('credit_notes.date as issue_date')
            ->selectRaw('5 as status')
            ->leftJoin('users', 'users.id', 'credit_notes.customer')
            ->leftJoin('invoice_products', 'invoice_products.invoice_id', 'credit_notes.invoice')
            ->leftJoin('product_services', 'product_services.id', 'invoice_products.product_id')
            ->leftJoin('invoices', 'invoices.id', 'credit_notes.invoice')
            ->where('invoices.created_by', creatorId())
            ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('credit_notes.date', [$start, $end]);
        }

        return $query->groupBy('credit_notes.id', 'product_services.name')
            ->get()
            ->toArray();
    }

    /**
     * Get aging summaries
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getinvoices($start = null, $end = null, $customerId = null)
    {
        $query = Invoice::select([
            'users.name',
            'invoices.id',
            'invoices.issue_date',
            'invoices.due_date',
            'invoices.status',
            DB::raw('(SELECT SUM((price * quantity - discount)) FROM invoice_products WHERE invoice_products.invoice_id = invoices.id) as total_price'),
            DB::raw('(SELECT SUM(amount) FROM invoice_payments WHERE invoice_payments.invoice_id = invoices.id) as paid_amount'),
            DB::raw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) 
            FROM invoice_products 
            LEFT JOIN taxes ON FIND_IN_SET(taxes.id, invoice_products.tax) > 0 
            WHERE invoice_products.invoice_id = invoices.id) as total_tax'),
            DB::raw('(SELECT SUM(amount) 
            FROM credit_notes 
            WHERE credit_notes.invoice = invoices.id) as credit_price'),
        ])
        ->leftJoin('users', 'users.id', '=', 'invoices.user_id')
        ->where('invoices.created_by', creatorId())
        ->where('invoices.workspace', getActiveWorkSpace());

        if ($customerId) {
            $query->where('invoices.customer_id', $customerId);
        }

        if ($start && $end) {
            $query->whereBetween('invoices.issue_date', [$start, $end]);
        }

        $invoices = $query->get();

        return $invoices;
    }
    protected function getAgingSummaries($start = null, $end = null, $customerId = null)
    {

        $agingSummaries = [];
        $invoices = $this->getinvoices($start , $end , $customerId);
        foreach ($invoices as $invoice) {
            $customerName = $invoice->name;
            $balanceDue   = floatval(($invoice->total_price + $invoice->total_tax) - ($invoice->paid_amount + $invoice->credit_price));
            $dueDate = Carbon::parse($invoice->due_date);
            $today = Carbon::now();
            $daysDifference = $dueDate->diffInDays($today);

            if (!isset($agingSummaries[$customerName])) {
                $agingSummaries[$customerName] = [
                    'current' => 0,
                    '1_15_days' => 0,
                    '16_30_days' => 0,
                    '31_45_days' => 0,
                    'greater_than_45_days' => 0,
                    'total_due' => 0
                ];
            }

            if ($daysDifference <= 0) {
                $agingSummaries[$customerName]['current'] += $balanceDue;
            } elseif ($daysDifference <= 15) {
                $agingSummaries[$customerName]['1_15_days'] += $balanceDue;
            } elseif ($daysDifference <= 30) {
                $agingSummaries[$customerName]['16_30_days'] += $balanceDue;
            } elseif ($daysDifference <= 45) {
                $agingSummaries[$customerName]['31_45_days'] += $balanceDue;
            } else {
                $agingSummaries[$customerName]['greater_than_45_days'] += $balanceDue;
            }

            $agingSummaries[$customerName]['total_due'] += $balanceDue;
        }

        return $agingSummaries;
    }

    /**
     * Get aging details
     *
     * @param string|null $start
     * @param string|null $end
     * @param int|null $customerId
     * @return array
     */
    protected function getAgingDetails($start = null, $end = null, $customerId = null)
    {
        $invoices = $this->getinvoices($start, $end, $customerId);
        $agingDetails = [
            'currents' => [],
            'days1to15' => [],
            'days16to30' => [],
            'days31to45' => [],
            'moreThan45' => []
        ];

        foreach ($invoices as $invoice) {
            $totalPrice     = $invoice->total_price + $invoice->total_tax;
            $balanceDue   = floatval(($invoice->total_price + $invoice->total_tax) - ($invoice->paid_amount + $invoice->credit_price));
            $dueDate = Carbon::parse($invoice->due_date);
            $dueDate = Carbon::parse($invoice->due_date);
            $today = Carbon::now();
            $daysDifference = $dueDate->diffInDays($today);

            $item = [
                'invoice_id' => $invoice->invoice_id,
                'due_date' => $invoice->due_date,
                'total_price' => $totalPrice,
                'balance_due' => $balanceDue,
                'age' => intval(str_replace(['+', '-'], '', $daysDifference)),
                'status' => $invoice->status,
                'name'=>$invoice->name
            ];

            if ($daysDifference <= 0) {
                $agingDetails['currents'][] = $item;
            } elseif ($daysDifference <= 15) {
                $agingDetails['days1to15'][] = $item;
            } elseif ($daysDifference <= 30) {
                $agingDetails['days16to30'][] = $item;
            } elseif ($daysDifference <= 45) {
                $agingDetails['days31to45'][] = $item;
            } else {
                $agingDetails['moreThan45'][] = $item;
            }
        }

        return $agingDetails;
    }
} 