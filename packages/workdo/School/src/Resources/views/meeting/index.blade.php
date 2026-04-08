@extends('layouts.main')
@section('page-title')
    {{ __('Manage Meetings') }}
@endsection
@section('title')
    {{ __('Meetings') }}
@endsection
@section('page-breadcrumb')
    {{ __('Meetings') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_meeting create')
    <a data-size="" data-url="{{ route('school-meeting.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Meeting') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
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
