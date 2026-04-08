@extends('layouts.main')
@section('page-title', __('Manage Exams'))
@section('title', __('Exams'))
@section('page-breadcrumb', __('Exams'))

@section('page-action')
<div>
    <a href="{{ route('school.exam.create') }}" class="btn btn-sm btn-primary btn-icon">
        <i class="ti ti-plus"></i>
    </a>
    <a href="{{ route('school.exam.bulk.form') }}" class="btn btn-sm btn-secondary btn-icon">
        <i class="ti ti-upload"></i> {{ __('Upload Marks') }}
    </a>
    <a href="{{ route('school.exam.report.index') }}" class="btn btn-sm btn-info btn-icon">
        <i class="ti ti-report"></i> {{ __('Exam Report') }}
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-bordered" id="exam-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Exam</th>
                                <th>Year</th>
                                <th>Term</th>
                                <th>Classroom</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('layouts.includes.datatable-js')

{{-- Add SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    let table = $('#exam-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('school.exam.index') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false },
            { data: 'exam_name', name: 'name' },
            { data: 'academic_year', name: 'academic_year' },
            { data: 'term', name: 'term' },
            { data: 'classroom', name: 'classroom' },
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ]
    });

    // SweetAlert2 Delete Confirmation
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: "{{ __('Are you sure?') }}",
            text: "{{ __('This action cannot be undone!') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('Yes, delete it!') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
