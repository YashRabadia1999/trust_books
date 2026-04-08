@extends('layouts.main')
@section('page-title')
    {{ __('Manage Fees') }}
@endsection
@section('title')
    {{ __('Fees') }}
@endsection
@section('page-breadcrumb')
    {{ __('Fees') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_fee create')
    <a data-size="" data-url="{{ route('school-fees.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Fee') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
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
