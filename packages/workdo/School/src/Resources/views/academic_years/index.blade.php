@extends('layouts.main')
@section('page-title', __('Manage Academic Year'))
@section('title', __('Academic Year'))
@section('page-breadcrumb', __('Academic Year'))

@section('page-action')
<div>
    @permission('school_academic_year create')
    <a data-size="lg" data-url="{{ route('school.academic-year.create') }}" data-ajax-popup="true" 
       data-bs-toggle="tooltip" data-title="{{ __('Create Academic Year') }}" 
       title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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
                <div class="table-responsive overflow_hidden">
                    <table class="table table-bordered" id="academic-year-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('layouts.includes.datatable-js')
<script>
$(function() {
    $('#academic-year-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('school.academic-year.index') !!}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

</script>
@endpush
