{{ Form::open(['route' => 'bulksms-send-sms.store', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <!-- Pre-filled Message -->
        <div class="col-12 mb-3">
            <div class="alert alert-info">
                <strong><i class="ti ti-template"></i> {{ __('Using Template') }}:</strong> {{ $customerMessage->name }}
            </div>
        </div>

        <!-- Select Group -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Select Group') }}<x-required /></label>
            <select name="group_id" class="form-control" required>
                <option value="">{{ __('Select Group') }}</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sender ID -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Select Sender ID') }}<x-required /></label>
            <select name="sender_id" class="form-control" required>
                <option value="">{{ __('Select Sender ID') }}</option>
                @foreach ($senderIds as $id)
                    <option value="{{ $id }}">{{ $id }}</option>
                @endforeach
            </select>
        </div>

        <!-- Message (Pre-filled) -->
        <div class="col-12 mb-3">
            {{ Form::label('sms', __('Message'), ['class' => 'form-label']) }}<x-required />
            {{ Form::textarea('sms', $customerMessage->message, ['class' => 'form-control', 'rows' => 5, 'id' => 'sms_text', 'required']) }}
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">
                    <i class="ti ti-message"></i> <span id="char_number">0</span> {{ __('characters') }}
                </small>
                <small class="badge bg-primary">
                    <i class="ti ti-file"></i> <span id="page_number">0</span> {{ __('page(s)') }}
                </small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Send Bulk SMS'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        // Character counter
        function updateCharCount() {
            var len = $('#sms_text').val().length;
            var pages = len > 0 ? (len <= 150 ? 1 : 1 + Math.ceil((len - 150) / 100)) : 0;
            $('#char_number').text(len);
            $('#page_number').text(pages);
        }

        $('#sms_text').on('input', updateCharCount);
        updateCharCount();
    });
</script>
