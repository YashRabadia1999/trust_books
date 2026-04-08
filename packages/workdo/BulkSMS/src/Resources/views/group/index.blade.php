@extends('layouts.main')

@section('page-title')
    {{ __('Manage Group') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Group') }}
@endsection

@section('page-action')   
    @permission('group_contact create')
    <a data-size="md" data-url="{{ route('bulksms-group.create',) }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Group') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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