@extends('layouts.main')
@section('page-title')
    {{ __('Manage Teachers') }}
@endsection
@section('page-breadcrumb')
    {{ __('Teachers') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_employee create')
    <a href="{{ route('schoolemployee.create') }}" data-title="{{ __('Create New Teacher') }}" data-bs-toggle="tooltip"
        title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
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
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
@endpush