@extends('layouts.main')
@section('page-title')
    {{ __('Message Templates') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('SMS') }},
    {{ __('Message Templates') }}
@endsection

@section('page-action')
    @permission('bulksms_contact create')
        <a data-size="lg" data-url="{{ route('customer-messages.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
            data-title="{{ __('Create Message Template') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
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
