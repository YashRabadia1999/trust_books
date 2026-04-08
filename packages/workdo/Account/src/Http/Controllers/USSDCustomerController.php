<?php

namespace Workdo\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class USSDCustomerController extends Controller
{
    /**
     * Show the customers page (Blade view)
     */
    public function view()
    {
        if (Auth::user()->type != 'company') {
            abort(403, 'Unauthorized access');
        }

        // Load the Blade view; table will be populated via AJAX
        return view('account::ussdcustomer.index');
    }

    /**
     * Fetch USSD customers via API for DataTable
     */
    public function data(Request $request)
    {
     
        try {
            if (Auth::user()->type != 'company') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $draw = intval($request->get('draw', 1));
            $start = intval($request->get('start', 0));
            $length = intval($request->get('length', 50));
            $searchValue = $request->get('search')['value'] ?? '';

            $page = ($start / $length) + 1;

            $payload = [
                "user_id" => Auth::user()->encryption_key,
                "page" => $page,
                "per_page" => $length,
            ];

            Log::info('USSD Customers Request Payload:', $payload);

            $response = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/customers-list', $payload);
         
            if (!$response->successful()) {
                Log::error('USSD Customers API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Failed to fetch USSD customers'
                ]);
            }

            $responseData = $response->json();
            $customers = $responseData['customers'] ?? [];
            $pagination = $responseData['pagination'] ?? [];

            $formattedData = [];
            foreach ($customers as $index => $cust) {
                $formattedData[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'id'=> $cust['id'] ?? '',
                    'name' => $cust['name'] ?? '-',
                    'phone' => $cust['phone_number'] ?? '-',
                    'balance' => number_format($cust['balance'] ?? 0, 2),
                    'transaction_count' => $cust['transaction_count'] ?? 0,
                    'dues_balance' => number_format($cust['dues_balance'] ?? 0, 2),
                ];
            }

            // Apply search filter if provided
            if (!empty($searchValue)) {
                $formattedData = array_filter($formattedData, function($item) use ($searchValue) {
                    return stripos($item['name'], $searchValue) !== false ||
                           stripos($item['phone'], $searchValue) !== false;
                });
                $formattedData = array_values($formattedData);
            }

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $pagination['total'] ?? count($customers),
                'recordsFiltered' => $pagination['total'] ?? count($customers),
                'data' => $formattedData
            ]);

        } catch (\Exception $e) {
            Log::error('USSD Customers Exception: ' . $e->getMessage());

            return response()->json([
                'draw' => intval($request->get('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

public function show($id)
{
    if (Auth::user()->type != 'company') {
        abort(403, 'Unauthorized access');
    }

    try {
        $payload = [
            'user_id' => Auth::user()->encryption_key,
            'customer_id' => $id,
        ];

        // Fetch full customer details (includes transactions)
        $customerResponse = Http::timeout(30)
            ->post('https://ussd.atdamss.com/api/datamonk/customer-details', $payload);
        
        if (!$customerResponse->successful()) {
            Log::error('Customer Details API failed', [
                'status' => $customerResponse->status(),
                'body' => $customerResponse->body()
            ]);
            return back()->withErrors(['error' => 'Failed to fetch customer details']);
        }

        $customerData = $customerResponse->json();

        // Defensive checks
        if (!isset($customerData['customer'])) {
            Log::warning('Customer data missing', ['response' => $customerData]);
            return back()->withErrors(['error' => 'Customer data not found']);
        }

        $customer = $customerData['customer'];
        $transactions = isset($customerData['transactions']) ? $customerData['transactions'] : [];

        // Optional log if no transactions found
        if (empty($transactions)) {
            Log::info('Transactions empty from API', ['customer_id' => $id]);
        }

        return view('account::ussdcustomer.show', [
            'customer' => $customer,
            'transactions' => $transactions,
        ]);

    } catch (\Exception $e) {
        Log::error('USSD Customer Detail Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


}
