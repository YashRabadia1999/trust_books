@extends('layouts.main')

@section('page-title')
    {{ __('SMS Credit Balance') }}
@endsection

@section('page-breadcrumb')
    {{ __('SMS Credits') }},
    {{ __('Balance & Transactions') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!-- Balance Summary -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="mb-2">{{ __('Total Credits Purchased') }}</h6>
                            <h2 class="mb-0">{{ number_format($balance->total_credits) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="mb-2">{{ __('Credits Used') }}</h6>
                            <h2 class="mb-0">{{ number_format($balance->used_credits) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="mb-2">{{ __('Remaining Credits') }}</h6>
                            <h2 class="mb-0">{{ number_format($balance->remaining_credits) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Transaction History') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Credits') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ company_date_formate($transaction->created_at) }}</td>
                                        <td>
                                            @php
                                                $typeColors = [
                                                    'purchase' => 'success',
                                                    'usage' => 'warning',
                                                    'refund' => 'info',
                                                    'adjustment' => 'secondary',
                                                ];
                                                $color = $typeColors[$transaction->type] ?? 'primary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $color }}">{{ ucfirst($transaction->type) }}</span>
                                        </td>
                                        <td>
                                            @if ($transaction->credits > 0)
                                                <span
                                                    class="text-success">+{{ number_format($transaction->credits) }}</span>
                                            @else
                                                <span class="text-danger">{{ number_format($transaction->credits) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->description ?? '-' }}</td>
                                        <td>{{ $transaction->reference ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('No transactions found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
