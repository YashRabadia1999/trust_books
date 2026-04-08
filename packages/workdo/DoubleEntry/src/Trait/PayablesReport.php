<?php

namespace Workdo\DoubleEntry\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Workdo\Account\Entities\Bill;
use Workdo\Account\Entities\Vender;

trait PayablesReport
{
    private function getVendor()
    {
        return  Vender::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
    }

    private function getPayableVendors($start = null, $end = null, $vendorId = null)
    {
        $payableVendors = Bill::select('vendors.name')
        ->selectRaw('sum((bill_products.price * bill_products.quantity) - bill_products.discount) as price')
        ->selectRaw('sum((bill_payments.amount)) as pay_price')
        ->selectRaw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) FROM bill_products
        LEFT JOIN taxes ON FIND_IN_SET(taxes.id, bill_products.tax) > 0
        WHERE bill_products.bill_id = bills.id) as total_tax')
        ->selectRaw('(SELECT SUM(debit_notes.amount) FROM debit_notes
        WHERE debit_notes.bill = bills.id) as debit_price')
        ->leftJoin('vendors', 'vendors.id', 'bills.vendor_id')
        ->leftJoin('bill_payments', 'bill_payments.bill_id', 'bills.id')
        ->leftJoin('bill_products', 'bill_products.bill_id', 'bills.id');
        $payableVendors->where('bills.created_by', creatorId());
        $payableVendors->where('bills.workspace', getActiveWorkSpace());
        if ($vendorId) {
            $payableVendors->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $payableVendors->whereBetween('bills.bill_date', [$start, $end]);
        }

        $payableVendors->groupBy('bills.id');
        $payableVendors = $payableVendors->get()->toArray();

        return $payableVendors;
    }

    private function getPayableSummaries($start = null, $end = null, $vendorId = null)
    {
        $billSummaries = $this->getBillSummaries($start, $end, $vendorId);
        $debitSummaries = $this->getDebitSummaries($start, $end, $vendorId);

        return array_merge($debitSummaries, $billSummaries);
    }

    private function getBillSummaries($start = null, $end = null, $vendorId = null)
    {
        $payableSummariesBill = Bill::select('vendors.name')
        ->selectRaw('(bills.bill_id) as bill')
        ->selectRaw('sum((bill_products.price * bill_products.quantity) - bill_products.discount) as price')
        ->selectRaw('sum((bill_payments.amount)) as pay_price')
        ->selectRaw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) FROM bill_products
           LEFT JOIN taxes ON FIND_IN_SET(taxes.id, bill_products.tax) > 0
            WHERE bill_products.bill_id = bills.id) as total_tax')
        ->selectRaw('bills.bill_date as bill_date')
        ->selectRaw('bills.status as status')
        ->leftJoin('vendors', 'vendors.id', 'bills.vendor_id')
        ->leftJoin('bill_payments', 'bill_payments.bill_id', 'bills.id')
        ->leftJoin('bill_products', 'bill_products.bill_id', 'bills.id');
        $payableSummariesBill->where('bills.created_by', creatorId());
        $payableSummariesBill->where('bills.workspace', getActiveWorkSpace());
        if ($vendorId) {
            $payableSummariesBill->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $payableSummariesBill->whereBetween('bills.bill_date', [$start, $end]);
        }
        $payableSummariesBill->groupBy('bills.id');
        $payableSummariesBill = $payableSummariesBill->get()->toArray();

        return $payableSummariesBill;
    }

    private function getDebitSummaries($start = null, $end = null, $vendorId = null)
    {
        $payableSummariesDebit = \Workdo\Account\Entities\DebitNote::select('vendors.name')
            ->selectRaw('null as bill')
            ->selectRaw('debit_notes.amount as price')
            ->selectRaw('0 as pay_price')
            ->selectRaw('0 as total_tax')
            ->selectRaw('debit_notes.date as bill_date')
            ->selectRaw('5 as status')
            ->leftJoin('vendors', 'vendors.id', 'debit_notes.vendor')
            ->leftJoin('bill_products', 'bill_products.bill_id', 'debit_notes.bill')
            ->leftJoin('bills', 'bills.id', 'debit_notes.bill');
        if ($vendorId) {
            $payableSummariesDebit->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $payableSummariesDebit->whereBetween('debit_notes.date', [$start, $end]);
        }
        $payableSummariesDebit->where('bills.created_by', creatorId());
        $payableSummariesDebit->where('bills.workspace', getActiveWorkSpace());
        $payableSummariesDebit->groupBy('debit_notes.id');
        $payableSummariesDebit = $payableSummariesDebit->get()->toArray();

        return $payableSummariesDebit;
    }

    private function getPayableDetails($start = null, $end = null, $vendorId = null)
    {
        $billDetails = $this->getBillDetails($start, $end, $vendorId);
        $debitDetails = $this->getDebitDetails($start, $end, $vendorId);

        return array_merge($debitDetails, $billDetails);
    }

    private function getBillDetails($start = null, $end = null, $vendorId = null)
    {
        $payableDetailsBill = Bill::select('vendors.name')
        ->selectRaw('(bills.bill_id) as bill')
        ->selectRaw('sum(bill_products.price) as price')
        ->selectRaw('(bill_products.quantity) as quantity')
        ->selectRaw('(product_services.name) as product_name')
        ->selectRaw('bills.bill_date as bill_date')
        ->selectRaw('bills.status as status')
        ->leftJoin('vendors', 'vendors.id', 'bills.vendor_id')
        ->leftJoin('bill_products', 'bill_products.bill_id', 'bills.id')
        ->leftJoin('product_services', 'product_services.id', 'bill_products.product_id');
        $payableDetailsBill->where('bills.created_by', creatorId());
        $payableDetailsBill->where('bills.workspace', getActiveWorkSpace());
        if ($vendorId) {
            $payableDetailsBill->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $payableDetailsBill->whereBetween('bills.bill_date', [$start, $end]);
        }
        $payableDetailsBill->groupBy('bills.bill_id', 'product_services.name');
        $payableDetailsBill = $payableDetailsBill->get()->toArray();

        return $payableDetailsBill;
    }

    private function getDebitDetails($start = null, $end = null, $vendorId = null)
    {
        $payableDetailsDebit = \Workdo\Account\Entities\DebitNote::select('vendors.name')
        ->selectRaw('null as bill')
        ->selectRaw('(debit_notes.id) as bills')
        ->selectRaw('(debit_notes.amount) as price')
        ->selectRaw('(product_services.name) as product_name')
        ->selectRaw('debit_notes.date as bill_date')
        ->selectRaw('5 as status')
        ->leftJoin('vendors', 'vendors.id', 'debit_notes.vendor')
        ->leftJoin('bill_products', 'bill_products.bill_id', 'debit_notes.bill')
        ->leftJoin('product_services', 'product_services.id', 'bill_products.product_id')
        ->leftJoin('bills', 'bills.id', 'debit_notes.bill');
        $payableDetailsDebit->where('bills.created_by', creatorId());
        $payableDetailsDebit->where('bills.workspace', getActiveWorkSpace());
        if ($vendorId) {
            $payableDetailsDebit->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $payableDetailsDebit->whereBetween('debit_notes.date', [$start, $end]);
        }
        $payableDetailsDebit->groupBy('debit_notes.id', 'product_services.name');
        $payableDetailsDebit = $payableDetailsDebit->get()->toArray();

        return $payableDetailsDebit;
    }

    protected function getBills($start = null, $end = null, $vendorId = null)
    {
        $bills = Bill::select([
            'vendors.name as name',
            'bills.due_date',
            'bills.status',
            'bills.id as bill_id',
    
            DB::raw('(SELECT SUM(price * quantity - discount) 
                      FROM bill_products 
                      WHERE bill_products.bill_id = bills.id) as price'),
    
            DB::raw('(SELECT SUM(amount) 
                      FROM bill_payments 
                      WHERE bill_payments.bill_id = bills.id) as pay_price'),
    
            DB::raw('(SELECT SUM((price * quantity - discount) * (taxes.rate / 100)) 
                      FROM bill_products 
                      LEFT JOIN taxes ON FIND_IN_SET(taxes.id, bill_products.tax) > 0 
                      WHERE bill_products.bill_id = bills.id) as total_tax'),
    
            DB::raw('(SELECT SUM(amount) 
                      FROM debit_notes 
                      WHERE debit_notes.bill = bills.id) as debit_price'),
        ])
        ->leftJoin('vendors', 'vendors.id', '=', 'bills.vendor_id')
        ->where('bills.created_by', creatorId())
        ->where('bills.workspace', getActiveWorkSpace());
        if ($vendorId) {
            $bills->where('bills.vendor_id', $vendorId);
        }
        if ($start && $end) {
            $bills->whereBetween('bills.bill_date', [$start, $end]);
        }
    
        $bills = $bills->get();
        return $bills;
    }

    private function getPayableAgingSummaries($start = null, $end = null, $vendorId = null)
    {
        $agingSummaries = [];
        $bills = $this->getBills($start, $end, $vendorId);

        $today = date("Y-m-d");
        foreach ($bills as $bill) {

            $name    = $bill->name;
            $price   = floatval(($bill->price + $bill->total_tax) - ($bill->pay_price + $bill->debit_price));
            $dueDate = $bill->due_date;

            if (!isset($agingSummaries[$name])) {
                $agingSummaries[$name] = [
                    'current'              => 0.0,
                    "1_15_days"            => 0.0,
                    "16_30_days"           => 0.0,
                    "31_45_days"           => 0.0,
                    "greater_than_45_days" => 0.0,
                    "total_due"            => 0.0,
                ];
            }

            $daysDifference = date_diff(date_create($dueDate), date_create($today));
            $daysDifference = $daysDifference->format("%R%a");

            if ($daysDifference <= 0) {
                $agingSummaries[$name]["current"] += $price;
            } elseif ($daysDifference >= 1 && $daysDifference <= 15) {
                $agingSummaries[$name]["1_15_days"] += $price;
            } elseif ($daysDifference >= 16 && $daysDifference <= 30) {
                $agingSummaries[$name]["16_30_days"] += $price;
            } elseif ($daysDifference >= 31 && $daysDifference <= 45) {
                $agingSummaries[$name]["31_45_days"] += $price;
            } elseif ($daysDifference > 45) {
                $agingSummaries[$name]["greater_than_45_days"] += $price;
            }

            $agingSummaries[$name]["total_due"] += $price;

        }

        return $agingSummaries;
    }

    protected function getPayableAgingDetails($start = null, $end = null, $vendorId = null)
    {
        $bills = $this->getBills($start, $end, $vendorId);
        $agingDetails = [
            'currents' => [],
            'days1to15' => [],
            'days16to30' => [],
            'days31to45' => [],
            'moreThan45' => []
        ];

        foreach ($bills as $bill) {
            $totalPrice     = $bill->price + $bill->total_tax;
            $balanceDue     = floatval(($bill->price + $bill->total_tax) - ($bill->pay_price + $bill->debit_price));
            $dueDate = Carbon::parse($bill->due_date);
            $today = Carbon::now();
            $daysDifference = $dueDate->diffInDays($today);

            $item = [
                'bill_id' => $bill->bill_id,
                'due_date' => $bill->due_date,
                'total_price' => $totalPrice,
                'balance_due' => $balanceDue,
                'age' => intval(str_replace(['+', '-'], '', $daysDifference)),
                'status' => $bill->status,
                'name'=>$bill->name
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