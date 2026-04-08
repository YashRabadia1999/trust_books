<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class USSDController extends Controller
{
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }

    public function ussdindex(Request $request)
    {
        if (Auth::user()->type != 'company') {
            abort(403, 'Unauthorized access');
        }
        
        $filter = $request->get('filter', 'year');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if ($filter === 'custom' && (!$start_date || !$end_date)) {
            return back()->withErrors(['Please select both start and end dates for custom filter.']);
        }

        $payload = [
            "user_id" => Auth::user()->encryption_key,
            "filter" => $filter,
            "start_date" => $start_date ?? now()->subYear()->startOfYear()->format('Y-m-d'),
            "end_date" => $end_date ?? now()->format('Y-m-d'),
            "status" => "success"
        ];

        $data = [
            'customers_data' => [],
            'transactions_data' => [],
            'active_customers_data' => [],
            'error' => null,
        ];

        try {
            //  Keep OLD way for transaction data (for basic stats)
            $summaryResponse = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $payload);
            
            if ($summaryResponse->successful()) {
                $apiData = $summaryResponse->json();
                $data = array_merge($data, $apiData);

                //  OLD way - check for chart data (unchanged)
                if (empty($data['customers_data']) || empty($data['transactions_data'])) {
                    try {
                        $chartPayload = [
                            "user_id" => Auth::user()->encryption_key,
                            "request_type" => "chart_data",

                        ];
                        
                        $chartResponse = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/chart-data', $chartPayload);
                        
                        if ($chartResponse->successful()) {
                            $chartData = $chartResponse->json();
                            $data['customers_data'] = $chartData['customers_data'] ?? [];
                            $data['transactions_data'] = $chartData['transactions_data'] ?? [];
                            $data['active_customers_data'] = $chartData['active_customers_data'] ?? [];
                        } else {
                            $data['customers_data'] = $this->generateSampleChartData('customers');
                            $data['transactions_data'] = $this->generateSampleChartData('transactions');
                            $data['active_customers_data'] = $this->generateSampleChartData('active_customers');
                        }
                    } catch (\Exception $e) {
                        Log::error('USSD Chart API Exception: ' . $e->getMessage());
                        $data['customers_data'] = $this->generateSampleChartData('customers');
                        $data['transactions_data'] = $this->generateSampleChartData('transactions');
                        $data['active_customers_data'] = $this->generateSampleChartData('active_customers');
                    }
                }
                
            } else {
                $data['error'] = 'Failed to fetch transaction summary.';
                Log::error('USSD API Error: ' . $summaryResponse->body());
            }
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
            Log::error('USSD API Exception: ' . $e->getMessage());
        }

        return view('school::dashboard.ussd_index', compact('data', 'filter', 'start_date', 'end_date'));
    }

    private function generateSampleChartData($type)
    {
        $data = [];
        $startDate = Carbon::now()->subDays(30);
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $data[] = [
                'date' => $date,
                'count' => $type === 'transactions' ? 0 : rand(0, 5),
                'total' => $type === 'transactions' ? rand(0, 500) : 0,
            ];
        }
        
        return $data;
    }

    public function getAllTransactions(Request $request)
    {
        if (Auth::user()->type != 'company') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filter = $request->get('filter');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $payload = [
            "user_id" => Auth::user()->encryption_key,
            "filter" => $filter ?? 'today',
        ];

        if ($filter === 'custom' && $startDate && $endDate) {
            $payload['start_date'] = $startDate;
            $payload['end_date'] = $endDate;
        } else {
            switch ($filter) {
                case 'today':
                    $payload['start_date'] = Carbon::today()->format('Y-m-d');
                    $payload['end_date'] = Carbon::today()->format('Y-m-d');
                    break;
                case 'last_7_days':
                    $payload['start_date'] = Carbon::now()->subDays(6)->format('Y-m-d');
                    $payload['end_date'] = Carbon::now()->format('Y-m-d');
                    break;
                case 'last_30_days':
                    $payload['start_date'] = Carbon::now()->subDays(29)->format('Y-m-d');
                    $payload['end_date'] = Carbon::now()->format('Y-m-d');
                    break;
                case 'this_month':
                    $payload['start_date'] = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $payload['end_date'] = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
                case 'last_month':
                    $payload['start_date'] = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                    $payload['end_date'] = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                    break;
                default:
                    $payload['start_date'] = Carbon::today()->format('Y-m-d');
                    $payload['end_date'] = Carbon::today()->format('Y-m-d');
                    break;
            }
        }

        $responseData = [
            'query_for_total_revenue' => 0,
            'total_amount' => 0,
            'total_amount_balance' => 0,
            'top_contributors' => [],
            'transaction_count' => 0,
            'customer_count' => 0,
            'total_customers' => 0,
            'total_revenue' => 0,
            'total_week' => 0,
            'total_today' => 0,
            'total_month' => 0,
            'today_change' => 0,
            'week_change' => 0,
            'month_change' => 0,
            'orgDuesCalculated' => 0,
            'totalServiceCharge' => 0,
            'membersWelfareCalculated' => 0,
            'active_customers' => 0,
            'active_customers_count' => 0,
            'highest_contributor' => null,
            'highest_collection_day' => null,
            'highest_collection_month' => null,
        ];

        try {
            //  Call OLD API for transaction summary (basic stats)
            $response = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $payload);
            
            if ($response->successful()) {
                $apiData = $response->json();
                
                // Map basic transaction data
                $responseData['query_for_total_revenue'] = $apiData['total_revenue'] ?? 0;
                $responseData['total_amount'] = $apiData['balance'] ?? 0;
                $responseData['total_amount_balance'] = $apiData['balance'] ?? 0;
                $responseData['transaction_count'] = $apiData['transaction_count'] ?? 0;
                $responseData['customer_count'] = $apiData['customer_count'] ?? 0;
                $responseData['total_customers'] = $apiData['total_customers'] ?? 0;
                $responseData['total_revenue'] = $apiData['total_revenue'] ?? 0;
                $responseData['total_week'] = $apiData['total_for_this_week'] ?? 0;
                $responseData['total_today'] = $apiData['total_for_today'] ?? 0;
                $responseData['total_month'] = $apiData['total_for_this_month'] ?? 0;
                $responseData['orgDuesCalculated'] = floatval($apiData['org_dues'] ?? 0);
                $responseData['totalServiceCharge'] = floatval($apiData['service_charge'] ?? 0);
                $responseData['membersWelfareCalculated'] = floatval($apiData['members_welfare'] ?? 0);

                //  Call NEW API ONLY for Top Contributors & Highest Collection Stats
                try {
                    $dashboardResponse = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/dashboard-stats', $payload);
                    
                    if ($dashboardResponse->successful()) {
                        $dashboardData = $dashboardResponse->json();
                        
                        //  Only get these specific fields from NEW API
                        $responseData['active_customers'] = $dashboardData['active_customers'] ?? 0;
                        $responseData['active_customers_count'] = $dashboardData['active_customers_count'] ?? 0;
                        $responseData['top_contributors'] = $dashboardData['top_contributors'] ?? [];
                        $responseData['highest_contributor'] = $dashboardData['highest_contributor'] ?? null;
                        $responseData['highest_collection_day'] = $dashboardData['highest_collection_day'] ?? null;
                        $responseData['highest_collection_month'] = $dashboardData['highest_collection_month'] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::error('Error fetching dashboard stats: ' . $e->getMessage());
                }

                // Calculate percentage changes
                try {
                    $todayPayload = array_merge($payload, [
                        'filter' => 'today',
                        'start_date' => Carbon::today()->format('Y-m-d'),
                        'end_date' => Carbon::today()->format('Y-m-d'),
                    ]);
                    
                    $yesterdayPayload = array_merge($payload, [
                        'filter' => 'custom',
                        'start_date' => Carbon::yesterday()->format('Y-m-d'),
                        'end_date' => Carbon::yesterday()->format('Y-m-d'),
                    ]);
                    
                    $thisWeekPayload = array_merge($payload, [
                        'filter' => 'custom',
                        'start_date' => Carbon::now()->startOfWeek()->format('Y-m-d'),
                        'end_date' => Carbon::now()->endOfWeek()->format('Y-m-d'),
                    ]);
                    
                    $lastWeekPayload = array_merge($payload, [
                        'filter' => 'custom',
                        'start_date' => Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d'),
                        'end_date' => Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d'),
                    ]);

                    $thisMonthPayload = array_merge($payload, [
                        'filter' => 'custom',
                        'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                        'end_date' => Carbon::now()->endOfMonth()->format('Y-m-d'),
                    ]);

                    $lastMonthPayload = array_merge($payload, [
                        'filter' => 'custom',
                        'start_date' => Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'),
                        'end_date' => Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d'),
                    ]);

                    $todayData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $todayPayload)->json();
                    $yesterdayData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $yesterdayPayload)->json();
                    $thisWeekData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $thisWeekPayload)->json();
                    $lastWeekData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $lastWeekPayload)->json();
                    $thisMonthData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $thisMonthPayload)->json();
                    $lastMonthData = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transaction-data', $lastMonthPayload)->json();

                    $responseData['total_today'] = $todayData['balance'] ?? 0;
                    $responseData['total_week'] = $thisWeekData['balance'] ?? 0;
                    $responseData['total_month'] = $thisMonthData['balance'] ?? 0;

                    $totalYesterday = $yesterdayData['balance'] ?? 0;
                    $totalLastWeek = $lastWeekData['balance'] ?? 0;
                    $totalLastMonth = $lastMonthData['balance'] ?? 0;

                    $responseData['today_change'] = $totalYesterday > 0 
                        ? (($responseData['total_today'] - $totalYesterday) / $totalYesterday) * 100 
                        : 0;

                    $responseData['week_change'] = $totalLastWeek > 0
                        ? (($responseData['total_week'] - $totalLastWeek) / $totalLastWeek) * 100
                        : 0;

                    $responseData['month_change'] = $totalLastMonth > 0
                        ? (($responseData['total_month'] - $totalLastMonth) / $totalLastMonth) * 100
                        : 0;

                } catch (\Exception $e) {
                    Log::error('Error calculating changes: ' . $e->getMessage());
                }

            } else {
                Log::error('USSD API Error in getAllTransactions: ' . $response->body());
                return response()->json([
                    'error' => 'Failed to fetch data from API',
                    'message' => 'Unable to retrieve transaction data'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('USSD API Exception in getAllTransactions: ' . $e->getMessage());
            return response()->json([
                'error' => 'API Exception',
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json($responseData);
    }

    public function create()
    {
        return view('school::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('school::show');
    }

    public function edit($id)
    {
        return view('school::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}