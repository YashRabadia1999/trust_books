@extends('layouts.main')

@section('page-title')
    {{ __('Send Single SMS') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Single SMS') }}
@endsection

@section('page-action')   
    @permission('singlesms_send create')
    <a data-size="md" data-url="{{ route('bulksms-single-sms.create',) }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Send Single SMS') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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