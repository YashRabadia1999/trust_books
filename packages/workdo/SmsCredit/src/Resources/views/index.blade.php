@extends('layouts.main')

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-title')
    {{ __('SMS Credit Purchase') }}
@endsection

@section('page-breadcrumb')
    {{ __('SMS Credits') }}
@endsection

@section('page-action')
    <div class="d-flex">
        <a href="{{ route('sms-credit.balance') }}" class="btn btn-sm btn-info me-2">
            <i class="ti ti-wallet"></i> {{ __('My Balance') }}
        </a>
        <a href="{{ route('sms-credit.create') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i> {{ __('Buy Credits') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Balance Card -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="mb-0">{{ __('Total Credits') }}</h6>
                            <h3 class="mb-0 text-primary">{{ number_format($balance->total_credits) }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="mb-0">{{ __('Used Credits') }}</h6>
                            <h3 class="mb-0 text-warning">{{ number_format($balance->used_credits) }}</h3>
                        </div>
                        <div class="col-md-3">
                            <h6 class="mb-0">{{ __('Remaining Credits') }}</h6>
                            <h3 class="mb-0 text-success">{{ number_format($balance->remaining_credits) }}</h3>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('sms-credit.balance') }}" class="btn btn-primary">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- {{ dd($dataTable) }} --}}

            <!-- Purchase History -->
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Purchase History') }}</h5>
                </div>
                <div class="card-body">
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
