@extends('layouts.main')
@section('page-title')
    {{ __('Manage Students') }}
@endsection
@section('page-breadcrumb')
    {{ __('Students') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('school_student create')
            <a href="{{ route('school-student.create') }}" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip"
                title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>

        
        
        <a href="{{ route('school-student.bulk.sample') }}"
               class="btn btn-sm btn-secondary me-2" data-bs-toggle="tooltip"
               title="{{ __('Download Sample') }}">
                <i class="ti ti-download"></i>
        </a>
        @endpermission
        
        @permission('school_student bulk upload')
            <a data-url="{{ route('school-student.bulk.form') }}" data-ajax-popup="true" data-size="md"
               data-title="{{ __('Bulk Upload Students') }}"
               class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
               title="{{ __('Bulk Upload') }}">
                <i class="ti ti-upload"></i>
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
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
@endpush
