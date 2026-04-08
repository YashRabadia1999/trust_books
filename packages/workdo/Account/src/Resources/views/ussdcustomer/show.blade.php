@extends('layouts.main')

@section('page-title', 'Customer Details')

{{-- Include DataTables CSS --}}
@section('head')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h4 class="mb-2">{{ $customer['name'] ?? '-' }}</h4>
        <p><strong>Phone:</strong> {{ $customer['phone_number'] ?? '-' }}</p>
        <p><strong>Total Balance:</strong> {{ number_format($customer['balance'] ?? 0, 2) }} GHS</p>
        <p><strong>Dues Balance:</strong> {{ number_format($customer['dues_balance'] ?? 0, 2) }} GHS</p>
        <p><strong>Loan Balance:</strong> {{ number_format($customer['loan_balance'] ?? 0, 2) }} GHS</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Transactions</h5>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="customerTransactionsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Amount (GHS)</th>
                        <th>Date & Time</th>
                        <th>Reference</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    @if(!empty($transactions) && count($transactions) > 0)
                        @foreach($transactions as $index => $txn)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $txn['name'] ?? '-' }}</td>
                                <td>{{ $txn['description'] ?? '-' }}</td>
                                <td>{{ number_format($txn['amount'] ?? 0, 2) }}</td>
                               
                                <td>{{ isset($txn['datetime']) ? \Carbon\Carbon::parse($txn['datetime'])->format('d M Y, h:i A') : '-' }}</td>
                                <td>{{ $txn['client_reference'] ?? '-' }}</td>
                                 <td>
                                    @if(($txn['status'] ?? '') === 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif(($txn['status'] ?? '') === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($txn['status'] ?? '-') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No transactions found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

{{-- Include DataTables JS --}}
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#customerTransactionsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            ordering: true,
            order: [[5, 'desc']], // sort by Date & Time
            language: {
                search: "Search Transactions:",
                lengthMenu: "Show _MENU_ records per page",
                zeroRecords: "No matching records found",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                infoEmpty: "No transactions available",
                infoFiltered: "(filtered from _MAX_ total records)"
            }
        });
    });
</script>
@endsection
