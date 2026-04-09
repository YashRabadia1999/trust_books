<div class="modal-body">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0 bg-light">
                <h6 class="text-muted mb-1">{{ __('Total Budget') }}</h6>
                <h4 class="mb-0 text-primary">{{ currency_format_with_sym($totalBudget) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0 bg-light">
                <h6 class="text-muted mb-1">{{ __('Total Paid') }}</h6>
                <h4 class="mb-0 text-success">{{ currency_format_with_sym($totalPaid) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0 bg-light">
                <h6 class="text-muted mb-1">{{ __('Remaining') }}</h6>
                <h4 class="mb-0 text-danger">{{ currency_format_with_sym($totalRemaining) }}</h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Source/Notes') }}</th>
                    <th>{{ __('Created By') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ company_date_formate($payment->date) }}</td>
                        <td>
                            @if($payment->type == 'budget_addition')
                                <span class="badge bg-primary">{{ __('Budget Added') }}</span>
                            @else
                                <span class="badge bg-success">{{ __('Payment') }}</span>
                            @endif
                        </td>
                        <td>{{ currency_format_with_sym($payment->amount) }}</td>
                        <td>
                            @if($payment->type == 'budget_addition' && !empty($payment->task))
                                <strong>{{ __('Task') }}:</strong> {{ $payment->task->title }}
                                <br><small class="text-muted">{{ $payment->notes }}</small>
                            @else
                                {{ $payment->notes ?? '-' }}
                            @endif
                        </td>
                        <td>{{ !empty($payment->createdBy) ? $payment->createdBy->name : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('No history recorded yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
