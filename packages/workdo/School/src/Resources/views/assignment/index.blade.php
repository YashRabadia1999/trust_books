@extends('layouts.main')
@section('page-title', __('Manage Assignments'))
@section('title', __('Assignments'))
@section('page-breadcrumb', __('Assignments'))

@section('page-action')
<div>
    @permission('school_assignment create')
    <a href="{{ route('school.assignment.create') }}" 
       data-bs-toggle="tooltip" data-title="{{ __('Create Assignment') }}" 
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
                    <table class="table table-bordered" id="assignment-table">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Students Count</th>
                                <!-- <th>Created At</th> -->
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
    $('#assignment-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('school.assignment.index') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'class_name', name: 'classrooms.class_name' },
            { data: 'subject_name', name: 'subjects.subject_name' },
            { data: 'students_count', name: 'students_count', orderable: false, searchable: false },
            // { data: 'created_at', name: 'assignment_entries.created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

});
</script>

@endpush
