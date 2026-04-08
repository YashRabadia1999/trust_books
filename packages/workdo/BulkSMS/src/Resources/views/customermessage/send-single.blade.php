{{ Form::open(['route' => 'bulksms.single-sms.store', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <!-- Pre-filled Message -->
        <div class="col-12 mb-3">
            <div class="alert alert-info">
                <strong><i class="ti ti-template"></i> {{ __('Using Template') }}:</strong> {{ $customerMessage->name }}
            </div>
        </div>

        <!-- Recipient -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Select Recipient') }}<x-required /></label>
            <select name="contact_id" id="contact_id" class="form-control select2" required>
                <option value="">{{ __('Select Name') }}</option>

                @if ($contacts->count() > 0)
                    <optgroup label="{{ __('Contacts') }}">
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->name }}" data-number="{{ $contact->mobile_no }}">
                                {{ $contact->name }} - {{ $contact->mobile_no }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif

                @if ($users->count() > 0)
                    <optgroup label="{{ __('Users') }}">
                        @foreach ($users as $user)
                            <option value="{{ $user->name }}" data-number="{{ $user->mobile }}">
                                {{ $user->name }} - {{ $user->mobile }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif

                @if ($customers->count() > 0)
                    <optgroup label="{{ __('Customers') }}">
                        @foreach ($customers as $cust)
                            <option value="{{ $cust->name }}" data-number="{{ $cust->phone }}">
                                {{ $cust->name }} - {{ $cust->phone }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            </select>
        </div>

        <!-- Mobile Number -->
        <div class="col-12 mb-3">
            {{ Form::label('mobile_no', __('Mobile Number'), ['class' => 'form-label']) }}
            {{ Form::text('mobile_no', null, ['class' => 'form-control', 'id' => 'mobile_no_display', 'readonly']) }}
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
    {{ Form::submit(__('Send SMS'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        // Initialize Select2
        if (typeof $.fn.select2 !== 'undefined') {
            $('#contact_id').select2({
                dropdownParent: $('#contact_id').closest('.modal-body'),
                width: '100%'
            });
        }

        // Load mobile number
        $('#contact_id').on('change', function() {
            var number = $(this).find('option:selected').data('number') || '';
            $('#mobile_no_display').val(number);
        });

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
