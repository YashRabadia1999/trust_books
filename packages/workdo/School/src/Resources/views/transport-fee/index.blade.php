@extends('layouts.main')
@section('page-title')
    {{ __('Manage Transport Fees') }}
@endsection
@section('title')
    {{ __('Transport Fees') }}
@endsection
@section('page-breadcrumb')
    {{ __('Transport Fees') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_transport_fees create')
    <a data-size="" data-url="{{ route('school-transport-fees.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Transport Fee') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
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
