@extends('layouts.main')
@section('page-title')
    {{ __('Manage Lessons') }}
@endsection
@section('page-breadcrumb')
    {{ __('Lessons') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
    </div>
@endsection
@push('css')
    <style>
        .status {
            min-width: 94px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('start_month', __('Start Month'), ['class' => 'form-label']) }}
                                    {{ Form::month('start_month', isset($_GET['start_month']) ? $_GET['start_month'] : '', ['class' => 'month-btn form-control']) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('end_month', __('End Month'), ['class' => 'form-label']) }}
                                    {{ Form::month('end_month', isset($_GET['end_month']) ? $_GET['end_month'] : '', ['class' => 'month-btn form-control']) }}
                                </div>
                            </div>
                            <div class="col-auto d-flex float-end mt-4">
                                <a class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    id="applyfilter" data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                @if (\Auth::user()->type == 'company')
                                    <a href="#!" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                        title="{{ __('Reset') }}" id="clearfilter"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
