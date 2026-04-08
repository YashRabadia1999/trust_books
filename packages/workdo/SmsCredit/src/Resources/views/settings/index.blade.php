<div class="card" id="sms-credit-settings">
    {{ Form::open(['route' => 'sms-credit.settings.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5>{{ __('SMS Credit Settings') }}</h5>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('sms_rate_per_credit', __('Rate Per SMS Credit (GHS)'), ['class' => 'form-label']) }}
                    {{ Form::number('sms_rate_per_credit', $settings['sms_rate_per_credit'] ?? 0.07, [
                        'class' => 'form-control',
                        'placeholder' => '0.07',
                        'step' => '0.01',
                        'min' => '0.01',
                        'required' => true,
                    ]) }}
                    <small class="text-muted">{{ __('Cost per SMS credit in GHS (e.g., 0.07)') }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('sms_min_purchase_amount', __('Minimum Purchase Amount (GHS)'), ['class' => 'form-label']) }}
                    {{ Form::number('sms_min_purchase_amount', $settings['sms_min_purchase_amount'] ?? 10, [
                        'class' => 'form-control',
                        'placeholder' => '10',
                        'step' => '1',
                        'min' => '1',
                        'required' => true,
                    ]) }}
                    <small class="text-muted">{{ __('Minimum amount for credit purchase (e.g., 10)') }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hubtel_api_key', __('Hubtel API Key'), ['class' => 'form-label']) }}
                    {{ Form::text('hubtel_api_key', $settings['hubtel_api_key'] ?? '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Hubtel API Key',
                    ]) }}
                    <small class="text-muted">{{ __('Your Hubtel API Key from merchant account') }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('hubtel_api_secret', __('Hubtel API Secret'), ['class' => 'form-label']) }}
                    {{ Form::password('hubtel_api_secret', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Hubtel API Secret',
                        'value' => $settings['hubtel_api_secret'] ?? '',
                    ]) }}
                    <small class="text-muted">{{ __('Your Hubtel API Secret from merchant account') }}</small>
                </div>
            </div>

            <div class="col-md-12">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="ti ti-info-circle"></i> {{ __('Credit Calculation') }}</h6>
                    <p class="mb-0">
                        {{ __('Credits are calculated based on the formula:') }}
                        <strong>Credits = Amount ÷ Rate Per Credit</strong>
                    </p>
                    <p class="mb-0 mt-2">
                        {{ __('Example: If rate is GHS 0.07 and user pays GHS 10, they will receive') }}
                        <strong>{{ floor(10 / ($settings['sms_rate_per_credit'] ?? 0.07)) }} credits</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
    </div>
    {{ Form::close() }}
</div>
