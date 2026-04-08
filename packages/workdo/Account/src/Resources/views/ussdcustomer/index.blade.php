@extends('layouts.main')

@section('page-title', 'USSD Customers')

@section('content')

<div class="table-responsive">
    <table id="customersTable" class="table table-striped table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Total Transactions</th>
                <th>Total Due Balance</th>
                <th>Total Balance</th>
                <th>Action</th> <!-- New column for View button -->
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via AJAX -->
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#customersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('account.ussd.customers.data') }}",
            type: "GET",
            dataSrc: function(json) {
                if(json.error) {
                    console.error('API Error:', json.error);
                    return [];
                }
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', error);
                alert('Failed to load USSD customers. Please refresh.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'phone' },
            { data: 'transaction_count' },
            { data: 'dues_balance' },
            { data: 'balance' },
            { 
                data: 'id', // use customer ID
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<a href="{{ url('/account/ussd-customer') }}/${data}" class="btn btn-sm btn-primary">View</a>`;
                }
            }
        ],
        pageLength: 50,
        lengthMenu: [[10,25,50,100],[10,25,50,100]],
        order: [[1,'asc']],
        language: {
            processing: 'Loading...',
            emptyTable: 'No customers found',
            zeroRecords: 'No matching customers'
        }
    });
});
</script>
@endpush
