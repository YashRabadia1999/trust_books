@extends('layouts.main')
@section('page-title')
    {{ __('Manage Parents') }}
@endsection
@section('page-breadcrumb')
    {{ __('Parents') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush


@section('page-action')
<div>
    @permission('school_parent create')
    <a href="{{ route('school-parent.create') }}" data-title="{{ __('Create New Parent') }}" data-bs-toggle="tooltip"
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