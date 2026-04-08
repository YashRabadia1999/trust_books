@extends('layouts.main')
@section('page-title')
    {{ __('Manage Payment Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Payment Summary') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div class="d-flex">
        <div class="action-btn me-2">
            <a href="{{ route('petcare.billing.payments.index') }}" class="btn-submit btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Back') }}">
                <i class=" ti ti-arrow-back-up"></i>
            </a>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
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
