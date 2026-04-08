@extends('layouts.main')
@section('page-title')
    {{ __('Manage Pet Appointments') }}
@endsection
@section('page-breadcrumb')
    {{ __('Pet Appointments') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-action')
    <div class="d-flex">
        @permission('pet_appointments create')
            <a class="btn btn-sm btn-primary" data-title="{{ __('Create Appointment') }}"
                href="{{ route('pet.appointments.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}    
@endpush

