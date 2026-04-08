@extends('layouts.main')
@section('page-title')
    {{__('Fee Setup Management')}}
@endsection
@section('page-breadcrumb')
    {{__('Fee Setup')}}
@endsection
@section('page-action')
    <div class="d-flex">
        @permission('school_fee_setup create')
        <a href="{{ route('school-fee-setup.create') }}" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="{{__('Create')}}">
            <i class="ti ti-plus"></i>
        </a>
        @endpermission
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Academic Year')}}</th>
                                <th>{{__('Term')}}</th>
                                <th>{{__('Class')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Students')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($feeSetups) && count($feeSetups) > 0)
                                @foreach($feeSetups as $feeSetup)
                                    <tr>
                                        <td>
                                            <a href="{{ route('school-fee-setup.show', encrypt($feeSetup->id)) }}" class="btn btn-outline-primary">
                                                {{ $feeSetup->name }}
                                            </a>
                                        </td>
                                        <td>{{ $feeSetup->academicYear->name ?? '-' }}</td>
                                        <td>{{ $feeSetup->term->name ?? '-' }}</td>
                                        <td>{{ $feeSetup->classroom->class_name ?? '-' }}</td>
                                        <td>{{ '$' . number_format($feeSetup->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $feeSetup->getTotalActiveStudents() }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $feeSetup->status == 'Active' ? 'success' : 'warning' }}">
                                                {{ $feeSetup->status }}
                                            </span>
                                        </td>
                                        <td>{{ $feeSetup->due_date }}</td>
                                        <td>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('school-fee-setup.show', encrypt($feeSetup->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('View')}}">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>
                                            </div>
                                            
                                            @if($feeSetup->status == 'Active')
                                                <div class="action-btn bg-info ms-2">
                                                    <form method="POST" action="{{ route('school-fee-setup.generate-invoices', encrypt($feeSetup->id)) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Generate Invoices')}}" onclick="return confirm('Generate invoices for all students?')">
                                                            <i class="ti ti-generic text-white"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                                <div class="action-btn bg-primary ms-2">
                                                    <form method="POST" action="{{ route('school-fee-setup.send-notifications', encrypt($feeSetup->id)) }}" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Send Notifications')}}">
                                                            <i class="ti ti-send text-white"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p>{{__('No fee setups found')}}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#fee-setup-table').DataTable({
            "pageLength": 10,
            "order": [[0, "desc"]],
            "language": {
                "emptyTable": "No fee setups found",
                "zeroRecords": "No fee setups found"
            }
        });
    });
</script>
@endpush
