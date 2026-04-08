<div class="modal-body">
    <div class="row">
        <div class="col-12 mb-3">
            <h6 class="text-muted">{{ __('Template Name') }}</h6>
            <p class="h5">{{ $customerMessage->name }}</p>
        </div>

        <div class="col-12 mb-3">
            <h6 class="text-muted">{{ __('Message Content') }}</h6>
            <div class="card bg-light">
                <div class="card-body">
                    <p class="mb-0">{{ $customerMessage->message }}</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">
                        <i class="ti ti-message"></i>
                        <strong>{{ strlen($customerMessage->message) }}</strong> {{ __('characters') }}
                    </small>
                </div>
                <div class="col-6 text-end">
                    @php
                        $len = strlen($customerMessage->message);
                        $pages = 0;
                        if ($len > 0) {
                            if ($len <= 150) {
                                $pages = 1;
                            } else {
                                $pages = 1 + ceil(($len - 150) / 100);
                            }
                        }
                    @endphp
                    <small class="badge bg-primary">
                        <i class="ti ti-file"></i> <strong>{{ $pages }}</strong> {{ __('page(s)') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <small class="text-muted">
                <i class="ti ti-calendar"></i> {{ __('Created') }}:
                {{ company_date_formate($customerMessage->created_at) }}
            </small>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
</div>
