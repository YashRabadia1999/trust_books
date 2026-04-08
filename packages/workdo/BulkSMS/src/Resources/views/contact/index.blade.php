@extends('layouts.main')

@section('page-title')
    {{ __('Manage Contact') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Contact') }}
@endsection

@section('page-action')
    @permission('bulksms_contact create')
        <a href="#" class="btn btn-sm btn-primary me-1" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Load Customers & Users') }}" data-url="{{ route('bulksms.contact.load.data') }}"
            data-bs-toggle="tooltip" title="{{ __('Load Customers & Users') }}">
            <i class="ti ti-users"></i> {{ __('Load Data') }}
        </a>
    @endpermission
    @permission('user import')
        <a href="#" data-size="md" class="btn btn-sm btn-primary me-1" data-ajax-popup="true"
            data-title="{{ __('Import Contact') }}" data-url="{{ route('bulksms.contact.file.import') }}"
            data-bs-toggle="tooltip" title="{{ __('Import Contact') }}"><i class="ti ti-file-import"></i>
        </a>
    @endpermission
    @permission('bulksms_contact create')
        <a data-size="lg" data-url="{{ route('bulksms-contacts.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
            data-title="{{ __('Create Contact') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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
