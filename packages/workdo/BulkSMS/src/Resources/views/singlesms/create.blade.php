{{ Form::open(['route' => 'bulksms.single-sms.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">

        <!-- Recipient -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Select Recipient') }}<x-required /></label>
            <select name="contact_id" id="contact_id" class="form-control select2" required
                data-placeholder="{{ __('Search by name or number...') }}">
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

        <!-- Mobile -->
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

        <!-- Message -->
        <div class="form-group col-md-12 mb-3">
            {{ Form::label('sms', __('Message'), ['class' => 'form-label']) }}<x-required></x-required>
            {!! Form::textarea('sms', null, [
                'class' => 'form-control',
                'rows' => '5',
                'id' => 'sms_text',
                'placeholder' => __('Type your message...'),
            ]) !!}
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small id="char_count" class="text-muted">
                    <i class="ti ti-message"></i> <span id="char_number">0</span> {{ __('characters') }}
                </small>
                <small id="page_count" class="badge bg-primary">
                    <i class="ti ti-file"></i> <span id="page_number">0</span> {{ __('page(s)') }}
                </small>
            </div>
            <small class="text-muted d-block mt-1">
                <i class="ti ti-info-circle"></i>
                {{ __('First 150 characters = 1 page, then +1 page per 100 characters') }}
            </small>
        </div>

        <!-- Load from saved templates -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Load Saved Message') }}</label>
            <select id="saved_message" class="form-control">
                <option value="">{{ __('Select Template') }}</option>
                @foreach ($messages as $msg)
                    <option value="{{ $msg->message }}">{{ $msg->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Create New Template -->
        <div class="col-12">
            <a href="#" data-bs-toggle="collapse" data-bs-target="#createTemplate" class="text-primary">
                <i class="ti ti-plus"></i> {{ __('Create New Message Template') }}
            </a>
            <div class="collapse mt-2" id="createTemplate">
                <form action="{{ route('bulksms-single-sms.save-message') }}" method="post">
                    @csrf
                    <input type="text" name="name" class="form-control mb-2"
                        placeholder="{{ __('Template Name') }}" required>
                    <textarea name="message" rows="3" class="form-control mb-2" placeholder="{{ __('Message Content') }}" required></textarea>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="ti ti-device-floppy"></i> {{ __('Save Template') }}
                    </button>
                </form>
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
        // Initialize Select2 for searchable dropdown
        if (typeof $.fn.select2 !== 'undefined') {
            $('#contact_id').select2({
                dropdownParent: $('#contact_id').closest('.modal-body'),
                width: '100%',
                placeholder: '{{ __('Search by name or number...') }}',
                allowClear: true
            });
        }

        // Load mobile number automatically
        $('#contact_id').on('change', function() {
            var number = $(this).find('option:selected').data('number') || '';
            $('#mobile_no_display').val(number);
        });

        // Load template message
        $('#saved_message').on('change', function() {
            $('#sms_text').val($(this).val()).trigger('input');
        });

        // Character count + pages calculation
        function updateCharCount() {
            var len = $('#sms_text').val().length;
            var pages = 0;

            if (len > 0) {
                if (len <= 150) {
                    pages = 1;
                } else {
                    // First 150 chars = 1 page, then every 100 chars = 1 additional page
                    pages = 1 + Math.ceil((len - 150) / 100);
                }
            }

            $('#char_number').text(len);
            $('#page_number').text(pages);

            // Change badge color based on pages
            $('#page_count').removeClass('bg-primary bg-warning bg-danger');
            if (pages === 0) {
                $('#page_count').addClass('bg-primary');
            } else if (pages <= 2) {
                $('#page_count').addClass('bg-primary');
            } else if (pages <= 4) {
                $('#page_count').addClass('bg-warning');
            } else {
                $('#page_count').addClass('bg-danger');
            }
        }

        // Trigger on input
        $('#sms_text').on('input', updateCharCount);

        // Initial count
        updateCharCount();
    });
</script>
