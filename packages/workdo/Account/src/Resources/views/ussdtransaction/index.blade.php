@extends('layouts.main')

@section('page-title', __('USSD Transactions'))

@section('content')
<div class="card">
    
    <div class="card-body">
        <div class="table-responsive">
            <table id="transactionsTable" class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Product Name</th>
                        <th>Amount (GHS)</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#transactionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('account.ussd.transactions.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'customer_name' },
            { data: 'name' },
            { data: 'amount' },
            { data: 'created_at' },
            {   // ✅ wrap status column correctly
                data: 'status',
                render: function(data, type, row) {
                    let color = '';
                    if (data === 'success') color = 'success';
                    else if (data === 'pending') color = 'warning';
                    else if (data === 'failed') color = 'danger';
                    else color = 'secondary';

                    return `<span class="badge bg-${color} text-uppercase">${data}</span>`;
                }
            }
        ],
        pageLength: 50,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'asc']],
        language: {
            processing: 'Loading...',
            emptyTable: 'No transactions found',
            zeroRecords: 'No matching transactions'
        }
    });
});

</script>
@endpush
