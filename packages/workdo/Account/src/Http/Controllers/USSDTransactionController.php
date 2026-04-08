<?php

namespace Workdo\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class USSDTransactionController extends Controller
{
    /**
     * Show the customers page (Blade view)
     */
    public function view()
    {
        if (Auth::user()->type != 'company') {
            abort(403, 'Unauthorized');
        }
        return view('account::ussdtransaction.index');
    }


    

   public function getTransactions(Request $request)
{
    try {
        if (Auth::user()->type != 'company') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $draw = intval($request->get('draw', 1));
        $start = intval($request->get('start', 0));
        $length = intval($request->get('length', 50));
        $searchValue = $request->get('search')['value'] ?? '';

        // Fetch a large batch to allow search
        $payload = [
            "user_id" => Auth::user()->encryption_key,
            "page" => 1,
            "per_page" => 1000,
        ];

        $response = Http::timeout(30)->post('https://ussd.atdamss.com/api/datamonk/transactions', $payload);

        if (!$response->successful()) {
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to fetch transactions'
            ]);
        }

        $transactions = $response->json()['transactions'] ?? [];

        // Format data
        $formattedData = [];
        $rowIndex = 1;
        foreach ($transactions as $txn) {
            $formattedData[] = [
                'DT_RowIndex'   => $rowIndex++,
                'name'          => $txn['name'] ?? '-',
                'customer_id'   => $txn['customer_id'] ?? '-',
                'amount'        => number_format($txn['amount'] ?? 0, 2),
                'customer_name' => $txn['customername']['name'] ?? '-',
                'created_at'    => isset($txn['created_at']) ? Carbon::parse($txn['created_at'])->format('d-m-Y') : '-',
                'status'        => $txn['status'] ?? '-',
            ];
        }

        // Apply search
        if (!empty($searchValue)) {
            $formattedData = array_filter($formattedData, function ($item) use ($searchValue) {
                return stripos($item['name'], $searchValue) !== false ||
                    stripos($item['amount'], $searchValue) !== false ||
                    stripos($item['customer_name'], $searchValue) !== false ||
                    stripos($item['status'], $searchValue) !== false ||
                    stripos($item['created_at'], $searchValue) !== false;
            });
            $formattedData = array_values($formattedData);
        }

        $recordsFiltered = count($formattedData);

        // Apply pagination
        $paginatedData = array_slice($formattedData, $start, $length);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => count($transactions),
            'recordsFiltered' => $recordsFiltered,
            'data' => $paginatedData,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'draw' => intval($request->get('draw', 1)),
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => $e->getMessage(),
        ]);
    }
}

}
