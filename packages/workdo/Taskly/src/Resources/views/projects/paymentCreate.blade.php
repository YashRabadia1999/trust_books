<form class="needs-validation" method="post" action="{{ route('projects.payment.store', [$project->id]) }}" novalidate>
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Amount') }}</label><x-required></x-required>
                <div class="input-group">
                    <span class="input-group-text">{{ company_setting('defult_currancy') }}</span>
                    <input type="number" class="form-control" name="amount" placeholder="{{ __('Enter Amount') }}" step="0.01" min="1" required>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Date') }}</label><x-required></x-required>
                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Notes') }}</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Enter Notes') }}"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
    </div>
</form>
