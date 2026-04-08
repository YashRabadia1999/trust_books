@extends('layouts.main')
@section('page-title', __('Manage Terms'))
@section('title', __('Terms'))
@section('page-breadcrumb', __('Terms'))

@section('page-action')
<div>
    @permission('school_term create')
    <a data-size="lg" data-url="{{ route('school.term.create') }}" data-ajax-popup="true" 
       data-bs-toggle="tooltip" data-title="{{ __('Create Term') }}" 
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
                    <table class="table table-bordered" id="term-table">
                        <thead>
                            <tr>
                                <th>Academic Year</th>
                                <th>Term Name</th>
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
    $('#term-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('school.term.index') !!}',
        columns: [
            { data: 'academic_year', name: 'academic_years.name' }, // ✅ works now
            { data: 'name', name: 'terms.name' },
            { data: 'start_date', name: 'terms.start_date' },
            { data: 'end_date', name: 'terms.end_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>

@endpush
