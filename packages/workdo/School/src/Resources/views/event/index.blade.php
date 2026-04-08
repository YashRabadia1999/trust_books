@extends('layouts.main')
@section('page-title')
    {{ __('Manage Events') }}
@endsection
@section('title')
    {{ __('Events') }}
@endsection
@section('page-breadcrumb')
    {{ __('Events') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/School/src/Resources/assets/css/custom.css') }}">
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_event create')
    <a data-size="" href="{{ route('school.event.calendar') }}" data-bs-toggle="tooltip" title="{{ __('Calendar View') }}"
    class="btn btn-sm btn-primary btn-icon me-1">
    <i class="ti ti-calendar"></i>
</a>
    <a data-size="" data-url="{{ route('school-event.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Event') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
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
<script src="{{ asset('packages/workdo/School/src/Resources/assets/js/custom.js') }}"></script>
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
@endpush
