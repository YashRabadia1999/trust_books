<?php

namespace Workdo\DoubleEntry\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\Account\Entities\ChartOfAccount;
use Workdo\Account\Entities\ChartOfAccountSubType;
use Workdo\Account\Entities\ChartOfAccountType;
use Workdo\Account\Entities\Customer;
use Workdo\Account\Entities\Vender;
use Workdo\DoubleEntry\Trait\BalanceSheetReport;
use Workdo\DoubleEntry\Trait\PayablesReport;
use Workdo\DoubleEntry\Trait\ProfitLossReport;
use Workdo\DoubleEntry\Trait\SalesReport;
use Workdo\DoubleEntry\Trait\TrialBalanceReport;
use Workdo\DoubleEntry\Trait\SalesReceivable;

class ReportController extends Controller
{
    use BalanceSheetReport;
    use TrialBalanceReport;
    use ProfitLossReport;
    use SalesReport;
    use SalesReceivable;
    use PayablesReport;

    public function getReportView($request, $view, $defaultView = 'vertical')
    {
        $validViews = ['vertical', 'horizontal'];
        $viewType   = $request->view ?? $view;
        
        if (in_array($viewType, $validViews)) {
            return $viewType;
        }        
        return $defaultView;
    }

    public function ledgerReport(Request $request, $account = '')
    {
        if (Auth::user()->isAbleTo('report ledger')) {
            $dateRange = $this->getDateRange($request);
            $start     = $dateRange['start'];
            $end       = $dateRange['end'];

            if (!empty($request->account)) {
                $chart_accounts = ChartOfAccount::where('id', $request->account)->where('created_by', creatorId())->get();
                $accounts       = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_accounts.parent')
                    ->where('parent', '=', 0)->where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())->get()
                    ->toarray();

            } else {
                $chart_accounts = ChartOfAccount::where('created_by', creatorId())->get();
                $accounts       = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_accounts.parent')
                    ->where('parent', '=', 0)->where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())->get()
                    ->toarray();
            }

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_account_parents.account');
            $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $subAccounts->where('chart_of_accounts.parent', '!=', 0);
            $subAccounts->where('chart_of_accounts.created_by', creatorId());
            $subAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
            $subAccounts = $subAccounts->get()->toArray();

            $balance                  = 0;
            $debit                    = 0;
            $credit                   = 0;
            $filter['balance']        = $balance;
            $filter['credit']         = $credit;
            $filter['debit']          = $debit;
            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;
            return view('double-entry::report.ledger', compact('filter', 'accounts', 'chart_accounts', 'subAccounts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function balanceSheet(Request $request, $view = '', $collapseview = 'expand')
    {
        if (Auth::user()->isAbleTo('report balance sheet')) {
            $dateRange = $this->getDateRange($request);
            $start     = $dateRange['start'];
            $end       = $dateRange['end'];

            $types         = ChartOfAccountType::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereIn('name', ['Assets', 'Liabilities', 'Equity'])->get();
            $totalAccounts = [];
            foreach ($types as $type) {
                $subTypes     = ChartOfAccountSubType::where('type', $type->id)->get();
                $subTypeArray = $this->buildSubTypeArray($type, $subTypes, $start, $end);
                $totalAccounts[$type->name] = $subTypeArray;
                $mainTypeIds        = $types->pluck('id')->toArray();
                $otherAccounts      = $this->getOtherAccounts($mainTypeIds, $start, $end);
                $balanceTotal       = 0;
                $currentYearEarning = [];
                foreach ($otherAccounts as $account) {
                    $balance       = $account->totalCredit - $account->totalDebit;
                    $balanceTotal += $balance;
                }
                if ($balanceTotal != 0) {
                    $currentYearEarning[] = [[
                        'account_id'   => null,
                        'account_code' => null,
                        'account_name' => 'Current Year Earnings',
                        'account'      => '',
                        'totalCredit'  => 0,
                        'totalDebit'   => 0,
                        'netAmount'    => $balanceTotal,
                    ]];
                    $totalAccounts['Equity'][] = [
                        'account' => $currentYearEarning,
                    ];
                }
            }
            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;
            $viewType                 = $this->getReportView($request, $view);
            return view('double-entry::report.balance_sheet' . ($viewType === 'horizontal' ? '_horizontal' : ''), compact('filter', 'totalAccounts', 'collapseview'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function profitLoss(Request $request, $view = '', $collapseView = 'expand')
    {
        if (Auth::user()->isAbleTo('report profit loss')) {
            $dateRange = $this->getDateRange($request);
            $start = $dateRange['start'];
            $end = $dateRange['end'];

            $types = ChartOfAccountType::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->whereIn('name', ['Income', 'Expenses', 'Costs of Goods Sold'])
                ->get();
            $totalAccounts = $this->processProfitLossData($types, $start, $end);

            $filter = [
                'startDateRange' => $start,
                'endDateRange' => $end
            ];
            
            $viewType = $this->getReportView($request, $view);
            return view('double-entry::report.profit_loss' . ($viewType === 'horizontal' ? '_horizontal' : ''), 
                compact('filter', 'totalAccounts', 'collapseView'));
        }
        else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function trialBalance(Request $request, $view = "expand")
    {
        if (Auth::user()->isAbleTo('report trial balance')) {

            $dateRange = $this->getDateRange($request);
            $start     = $dateRange['start'];
            $end       = $dateRange['end'];

            // Get account types and process them
            $types = $this->getAccountTypes();
            $totalAccounts = $this->processAccountTypes($types, $start, $end);

            $filter = [
                'startDateRange' => $start,
                'endDateRange' => $end
            ];

            return view('double-entry::report.trial_balance', compact('filter', 'totalAccounts', 'view'));
        }

        return redirect()->back()->with('error', __('Permission Denied.'));
        
    }

    public function salesReport(Request $request)
    {
        if (Auth::user()->isAbleTo('report sales')) {
            $dateRange = $this->getDateRange($request);
            $start     = $dateRange['start'];
            $end       = $dateRange['end'];

            $invoiceItems     = $this->getInvoiceItems($start, $end);
            $invoiceCustomers = $this->getInvoiceCustomers($start, $end);

            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;

            return view('double-entry::report.sales_report', compact('filter', 'invoiceItems', 'invoiceCustomers'));
        }
        else {            
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function ReceivablesReport(Request $request)
    {
        if (Auth::user()->isAbleTo('report receivables')) {
            $dateRange  = $this->getDateRange($request);
            $start      = $dateRange['start'];
            $end        = $dateRange['end'];
            $customerId = $request->customer ?? null;

            $customers           = $this->getCustomers();
            $receivableCustomers = $this->getReceivableCustomers($start, $end, $customerId);
            $receivableSummaries = $this->getReceivableSummaries($start, $end, $customerId);
            $receivableDetails   = $this->getReceivableDetails($start, $end, $customerId);
            $agingSummaries      = $this->getAgingSummaries($start, $end, $customerId);
            $agingDetails        = $this->getAgingDetails($start, $end, $customerId);
            
            $moreThan45 = $agingDetails['moreThan45'] ?? [];
            $days31to45 = $agingDetails['days31to45'] ?? [];
            $days16to30 = $agingDetails['days16to30'] ?? [];
            $days1to15  = $agingDetails['days1to15'] ?? [];
            $currents   = $agingDetails['currents'] ?? [];

            $filter = [
                'startDateRange' => $start,
                'endDateRange'   => $end
            ];

            if ($customerId) {
                $customer           = Customer::find($customerId);
                $filter['customer'] = !empty($customer) ? $customer->name : '';
            }

            return view('double-entry::report.receivable_report', compact('filter','receivableCustomers','receivableSummaries','receivableDetails','agingSummaries','agingDetails','customers','moreThan45','days31to45','days16to30','days1to15','currents'));
        }
        else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function PayablesReport(Request $request)
    {
        if (Auth::user()->isAbleTo('report payables')) {
            $dateRange = $this->getDateRange($request);
            $start     = $dateRange['start'];
            $end       = $dateRange['end'];
            $vendorId  = $request->vendor ?? null;

            $vendor           = $this->getVendor();
            $payableVendors   = $this->getPayableVendors($start, $end, $vendorId);
            $payableSummaries = $this->getPayableSummaries($start, $end, $vendorId);
            $payableDetails   = $this->getPayableDetails($start, $end, $vendorId);
            $agingSummaries   = $this->getPayableAgingSummaries($start, $end, $vendorId);
            $agingDetails     = $this->getPayableAgingDetails($start, $end, $vendorId);
            
            $moreThan45 = $agingDetails['moreThan45'] ?? [];
            $days31to45 = $agingDetails['days31to45'] ?? [];
            $days16to30 = $agingDetails['days16to30'] ?? [];
            $days1to15  = $agingDetails['days1to15'] ?? [];
            $currents   = $agingDetails['currents'] ?? [];

            $filter['startDateRange'] = $start;
            $filter['endDateRange']   = $end;

            if ($vendorId) {
                $vendor = Vender::find($vendorId);
                $filter['vendor'] = !empty($vendor) ? $vendor->name : '';
            }

            return view('double-entry::report.payable_report', compact('filter', 'payableVendors','payableSummaries', 'payableDetails', 'agingSummaries', 'moreThan45', 'days31to45', 'days16to30', 'days1to15', 'currents', 'vendor'));
        }
        else {            
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
