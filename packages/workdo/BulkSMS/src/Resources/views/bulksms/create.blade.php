{{ Form::open(['route' => 'bulksms-send-sms.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success w-100">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger w-100">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger w-100">{{ $errors->first() }}</div>
        @endif

        <!-- Recipient Type -->
        <div class="col-12 mb-3">
            <label class="form-label">{{ __('Select Recipient Type') }}<x-required></x-required></label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="group"
                    checked>
                <label class="form-check-label">{{ __('Group') }}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="customers">
                <label class="form-check-label">{{ __('Customers') }}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="users">
                <label class="form-check-label">{{ __('Users') }}</label>
            </div>
        </div>

        <!-- Group Selection -->
        <div class="col-12 recipient-select" id="group_select">
            <div class="form-group">
                {{ Form::label('group_id', __('Select Group'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="group_id" class="form-control">
                    <option value="">{{ __('-- Select Group --') }}</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Customer Selection -->
        <div class="col-12 recipient-select d-none" id="customer_select">
            <div class="form-group">
                {{ Form::label('customer_ids', __('Select Customers'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="customer_ids[]" id="customer_ids" class="form-control select2" multiple>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('You can select multiple customers') }}</small>
            </div>
        </div>

        <!-- User Selection -->
        <div class="col-12 recipient-select d-none" id="user_select">
            <div class="form-group">
                {{ Form::label('user_ids', __('Select Users'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="user_ids[]" id="user_ids" class="form-control select2" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->mobile_no }})</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('You can select multiple users') }}</small>
            </div>
        </div>

        <!-- Message Type -->
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('message_type', __('Message Type'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="message_type" id="message_type" class="form-control">
                    <option value="custom">{{ __('Custom Message') }}</option>
                    <option value="template">{{ __('Use Saved Template') }}</option>
                </select>
            </div>
        </div>

        <!-- Custom Message Template Selection -->
        <div class="col-12 d-none" id="template_select_div">
            <div class="form-group">
                {{ Form::label('custom_message_id', __('Select Message Template'), ['class' => 'form-label']) }}
                <select name="custom_message_id" id="custom_message_id" class="form-control">
                    <option value="">{{ __('-- Select Template --') }}</option>
                    @foreach ($customMessages as $msg)
                        <option value="{{ $msg->id }}" data-message="{{ $msg->message }}">{{ $msg->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Custom SMS Text -->
        <div class="form-group col-12" id="custom_message_div">
            {{ Form::label('sms', __('Message'), ['class' => 'form-label']) }}<x-required></x-required>
            {!! Form::textarea('sms', null, [
                'class' => 'form-control',
                'id' => 'sms_textarea',
                'rows' => '4',
                'placeholder' => 'Enter Message',
            ]) !!}
            <small class="text-muted">
                <span id="char_count">0</span> {{ __('characters') }} |
                <span id="page_count">0</span> {{ __('page(s)') }}
            </small>
        </div>

        <!-- Sender ID -->
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('sender_id', __('Sender ID'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('sender_id', array_combine($senderIds, $senderIds), null, ['class' => 'form-control', 'placeholder' => __('Select Sender ID')]) }}
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
        // Initialize Select2 for multiple selections
        $('#customer_ids, #user_ids').select2({
            dropdownParent: $('.modal-body'),
            width: '100%'
        });

        // Handle recipient type change
        $('.recipient-type').on('change', function() {
            $('.recipient-select').addClass('d-none');
            const value = $(this).val();

            if (value === 'group') {
                $('#group_select').removeClass('d-none');
            } else if (value === 'customers') {
                $('#customer_select').removeClass('d-none');
            } else if (value === 'users') {
                $('#user_select').removeClass('d-none');
            }
        });

        // Handle message type change
        $('#message_type').on('change', function() {
            if ($(this).val() === 'template') {
                $('#template_select_div').removeClass('d-none');
                $('#custom_message_div').addClass('d-none');
                $('#sms_textarea').removeAttr('required');
            } else {
                $('#template_select_div').addClass('d-none');
                $('#custom_message_div').removeClass('d-none');
                $('#sms_textarea').attr('required', true);
            }
        });

        // Handle custom message template selection
        $('#custom_message_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const message = selectedOption.data('message');

            if (message) {
                $('#sms_textarea').val(message);
                updateCharCount();
            }
        });

        // Character and page counter
        function updateCharCount() {
            const message = $('#sms_textarea').val();
            const charCount = message.length;
            const pageCount = charCount <= 150 ? 1 : Math.ceil((charCount - 150) / 100) + 1;

            $('#char_count').text(charCount);
            $('#page_count').text(pageCount);
        }

        $('#sms_textarea').on('input', updateCharCount);
        updateCharCount();
    });
</script>
