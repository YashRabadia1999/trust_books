@extends('layouts.main')
@section('page-title')
    {{ __('Manage Hostel Students') }}
@endsection
@section('title')
    {{ __('Hostel Students') }}
@endsection
@section('page-breadcrumb')
    {{ __('Hostel Students') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
    @permission('school_hostel_student create')
        <div>
            <a data-size="" data-url="{{ route('hostel-student.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create Hostel Student') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
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
