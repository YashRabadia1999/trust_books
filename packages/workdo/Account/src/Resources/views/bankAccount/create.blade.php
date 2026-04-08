{{ Form::open(['url' => 'bank-account', 'class' => 'needs-validation', 'novalidate']) }}
<div class=modal-body>
    <div class="row">
        <div class="form-group col-md-12">
            <label class="require form-label">{{ __('Bank Type') }}</label><x-required></x-required>
            <select class="form-control" name="bank_type" id="bank_type">
                <option value="">{{ __('Select Type') }}</option>
                <option value="bank">{{ __('Bank') }}</option>
                <option value="wallet">{{ __('Wallet') }}</option>
            </select>
        </div>

    </div>
    <div class="row bank_type_wallet d-none">
        <div class="form-group col-md-12">
            <label class="require form-label">{{ __('Wallet') }}</label><x-required></x-required>
            <select class="form-control" name="wallet_type" id="wallet_type">
                <option value="">{{ __('Select Type') }}</option>
                <option value="" disabled selected>{{ __('Select Type') }}</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="MTN MOMO">MTN MOMO</option>
                <option value="TELECEL CASH">TELECEL CASH</option>
                <option value="AT CASH">AT CASH</option>
                <option value="Skrill">Skrill</option>
                <option value="Binance">Binance</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('chart_account_id', __('Account'), ['class' => 'form-label']) }}<x-required></x-required>
            <select name="chart_account_id" class="form-control" required="required">
                @foreach ($chartAccounts as $key => $chartAccount)
                    <option value="{{ $key }}" class="subAccount">{{ $chartAccount }}</option>
                    @foreach ($subAccounts as $subAccount)
                        @if ($key == $subAccount['account'])
                            <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp;
                                {{ $subAccount['code'] }} - {{ $subAccount['name'] }}</option>
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('payment_name', __('Payment Gateway'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="payment_name" class="form-control" required="required">
                    <option value="" disabled selected>{{ __('Select Type') }}</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="MTN MOMO">MTN MOMO</option>
                    <option value="TELECEL CASH">TELECEL CASH</option>
                    <option value="AT CASH">AT CASH</option>
                    <option value="Skrill">Skrill</option>
                    <option value="Binance">Binance</option>
                    @stack('bank_payments')
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('holder_name', __('Bank Holder Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('holder_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Bank Holder Name')]) }}
            </div>
        </div>
        <div class="col-md-6 bank">
            <div class="form-group">
                {{ Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) }}
                {{ Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Bank Name'), 'id' => 'bank_name']) }}
            </div>
        </div>
        <div class="col-md-6 bank">
            <div class="form-group">
                {{ Form::label('account_number', __('Account Number'), ['class' => 'form-label']) }}
                {{ Form::text('account_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Account Number'), 'id' => 'account_number']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('opening_balance', __('Opening Balance'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('opening_balance', '', ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'step' => '0.1', 'placeholder' => __('Enter Opening Balance')]) }}
            </div>
        </div>

        <x-mobile divClass="col-lg-6 col-md-6 col-sm-6 bank" name="contact_number" label="{{ __('Contact Number') }}"
            placeholder="{{ __('Enter Contact Number') }}" id="contact_number">
        </x-mobile>

        <div class="col-md-6 bank">
            <div class="form-group">
                {{ Form::label('bank_branch', __('Bank Branch'), ['class' => 'form-label']) }}
                {{ Form::text('bank_branch', '', ['class' => 'form-control', 'min' => '0', 'placeholder' => __('Enter Bank Branch'), 'id' => 'bank_branch']) }}
            </div>
        </div>
        <div class="col-md-6 bank">
            <div class="form-group">
                {{ Form::label('swift', __('SWIFT'), ['class' => 'form-label']) }}
                {{ Form::text('swift', '', ['class' => 'form-control', 'id' => 'swift', 'placeholder' => __('Enter Swift Number')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('bank_address', __('Bank Address'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('bank_address', null, ['class' => 'form-control', 'placeholder' => __('Enter Bank Address'), 'rows' => '3', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {
        // Initialize bank fields as hidden
        $('.bank').addClass('d-none');

        $('#bank_type').on('change', function() {
            if ($(this).val() === 'bank') {
                $('.bank_type_wallet').addClass('d-none');
                $('.bank').removeClass('d-none');
                $('#bank_name').prop('required', true);
                $('#account_number').prop('required', true);
                $('#bank_branch').prop('required', true);
                $('#contact_number').prop('required', true);
                $('#wallet_type').prop('required', false);
            } else if ($(this).val() === 'wallet') {
                $('.bank').addClass('d-none');
                $('.bank_type_wallet').removeClass('d-none');
                $('#bank_name').prop('required', false);
                $('#account_number').prop('required', false);
                $('#bank_branch').prop('required', false);
                $('#contact_number').prop('required', false);
                $('#swift').prop('required', false);
                $('#wallet_type').prop('required', true);
            } else {
                $('.bank').addClass('d-none');
                $('.bank_type_wallet').addClass('d-none');
                $('#bank_name').prop('required', false);
                $('#account_number').prop('required', false);
                $('#bank_branch').prop('required', false);
                $('#contact_number').prop('required', false);
                $('#swift').prop('required', false);
                $('#wallet_type').prop('required', false);
            }
        });
    });
</script>
