{{ Form::open(['route' => 'bulksms.contact.load.customers.users', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <p class="text-muted mb-3">
                {{ __('Select which data you want to load into your contacts list. Only new contacts will be imported (duplicates will be skipped).') }}
            </p>
        </div>

        @if (module_is_active('Account'))
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" name="load_customers" id="load_customers"
                                checked>
                            <label class="form-check-label mb-0" for="load_customers">
                                <strong>{{ __('Load Customers') }}</strong>
                                <small
                                    class="d-block text-muted">{{ __('Import customer names and phone numbers from Account module') }}</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-check form-switch d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-2" name="load_users" id="load_users" checked>
                        <label class="form-check-label mb-0" for="load_users">
                            <strong>{{ __('Load Users') }}</strong>
                            <small
                                class="d-block text-muted">{{ __('Import user names and phone numbers from your workspace') }}</small>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="alert alert-info mb-0">
                <i class="ti ti-info-circle me-1"></i>
                {{ __('Contacts with duplicate phone numbers will be automatically skipped.') }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-primary">
        <i class="ti ti-download me-1"></i>{{ __('Load Data') }}
    </button>
</div>
{{ Form::close() }}
