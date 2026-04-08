@extends('layouts.main')
@section('page-title')
    {{ __('Manage Invoice') }}
@endsection
@section('title')
    {{ __('Invoice') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('drivinginvoice create')
            <a href="{{ route('drivinginvoice.create') }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ __('Create') }}"><i
                    class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@push('css')
    <style>
        .fixstatus {
            min-width: 94px;
        }
    </style>
@endpush

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
