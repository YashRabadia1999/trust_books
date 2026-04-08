@extends('layouts.main')
@section('page-title')
    {{ __('Manage Homework') }}
@endsection
@section('title')
    {{ __('Homework') }}
@endsection
@section('page-breadcrumb')
    {{ __('Homework') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush


@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('school_homework create')
            <a data-size="lg" data-url="{{ route('school-homework.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create Homework') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
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