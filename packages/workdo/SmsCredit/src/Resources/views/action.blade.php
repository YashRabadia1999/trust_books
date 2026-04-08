@if (Auth::user()->isAbleTo('sms_credit manage'))
    <div class="action-btn bg-light-secondary ms-2">
        <a href="{{ route('sms-credit.show', $purchase->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
            data-bs-toggle="tooltip" title="{{ __('View') }}">
            <i class="ti ti-eye"></i>
        </a>
    </div>
@endif

@if ($purchase->status === 'pending')
    <div class="action-btn bg-light-secondary ms-2">
        <a href="{{ route('sms-credit.check-status', $purchase->id) }}"
            class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
            title="{{ __('Check Status') }}">
            <i class="ti ti-refresh"></i>
        </a>
    </div>
@endif
