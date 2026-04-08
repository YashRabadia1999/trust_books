@extends('layouts.main')

@section('page-title')
    {{ __('Purchase Details') }}
@endsection

@section('page-breadcrumb')
    {{ __('SMS Credits') }},
    {{ __('Purchase Details') }}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Purchase Details #') }}{{ $purchase->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Transaction ID:') }}</th>
                                    <td>{{ $purchase->transaction_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Credits Purchased:') }}</th>
                                    <td><strong>{{ number_format($purchase->credits_purchased) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Amount Paid:') }}</th>
                                    <td><strong>GHS {{ number_format($purchase->amount_paid, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Mobile Number:') }}</th>
                                    <td>{{ $purchase->mobile_number }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">{{ __('Payment Method:') }}</th>
                                    <td>{{ ucfirst($purchase->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Status:') }}</th>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'completed' => 'success',
                                                'failed' => 'danger',
                                                'cancelled' => 'secondary',
                                            ];
                                            $color = $statusColors[$purchase->status] ?? 'info';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($purchase->status) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Date:') }}</th>
                                    <td>{{ company_date_formate($purchase->created_at) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Created By:') }}</th>
                                    <td>{{ $purchase->creator->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($purchase->status === 'pending')
                        <div class="alert alert-warning mt-3">
                            <i class="ti ti-clock"></i>
                            {{ __('Payment is pending. Please check your phone to complete the transaction.') }}
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('sms-credit.check-status', $purchase->id) }}" class="btn btn-primary">
                                <i class="ti ti-refresh"></i> {{ __('Check Payment Status') }}
                            </a>
                        </div>
                    @elseif ($purchase->status === 'completed')
                        <div class="alert alert-success mt-3">
                            <i class="ti ti-check"></i>
                            {{ __('Payment completed successfully. Credits have been added to your account.') }}
                        </div>
                    @elseif ($purchase->status === 'failed')
                        <div class="alert alert-danger mt-3">
                            <i class="ti ti-x"></i>
                            {{ __('Payment failed. Please try again or contact support.') }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('sms-credit.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
