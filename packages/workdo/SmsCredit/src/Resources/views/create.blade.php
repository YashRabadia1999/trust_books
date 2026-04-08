@extends('layouts.main')

@section('page-title')
    {{ __('Buy SMS Credits') }}
@endsection

@section('page-breadcrumb')
    {{ __('SMS Credits') }},
    {{ __('Buy Credits') }}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Purchase SMS Credits') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Current Balance -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>{{ __('Current Balance:') }}</strong><br>
                                <h4 class="mb-0">{{ number_format($balance->remaining_credits) }} {{ __('Credits') }}</h4>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('Rate Per SMS:') }}</strong><br>
                                <h4 class="mb-0">GHS {{ number_format($ratePerSms, 2) }}</h4>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('Minimum Amount:') }}</strong><br>
                                <h4 class="mb-0">GHS {{ number_format($minAmount, 2) }}</h4>
                            </div>
                        </div>
                    </div>

                    {{ Form::open(['route' => 'sms-credit.store', 'method' => 'POST']) }}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('amount', __('Amount to Pay (GHS)'), ['class' => 'form-label']) }}
                                <x-required></x-required>
                                {{ Form::number('amount', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter amount',
                                    'min' => $minAmount,
                                    'step' => '0.01',
                                    'required' => true,
                                    'id' => 'amount_input',
                                ]) }}
                                <small class="text-muted">{{ __('Minimum amount is GHS') }} {{ $minAmount }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('credits_display', __('Credits to Buy (SMS)'), ['class' => 'form-label']) }}
                                <input type="text" id="credits_display" class="form-control bg-light" value="0"
                                    readonly>
                                <small class="text-muted">{{ __('Credits will be calculated automatically') }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('mobile_number', __('Mobile Number for Hubtel Payment'), ['class' => 'form-label']) }}
                                <x-required></x-required>
                                {{ Form::text('mobile_number', null, [
                                    'class' => 'form-control',
                                    'placeholder' => '0501234567',
                                    'required' => true,
                                    'pattern' => '[0-9]{10,15}',
                                ]) }}
                                <small
                                    class="text-muted">{{ __('Enter your mobile money number (e.g., 0501234567)') }}</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="ti ti-info-circle"></i>
                                <strong>{{ __('Payment Information:') }}</strong>
                                <ul class="mb-0 mt-2">
                                    <li>{{ __('You will receive a prompt on your phone to complete the payment') }}</li>
                                    <li>{{ __('Credits will be added to your account once payment is confirmed') }}</li>
                                    <li>{{ __('Please ensure your mobile money wallet has sufficient balance') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <a href="{{ route('sms-credit.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-credit-card"></i> {{ __('Proceed to Payment') }}
                            </button>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const ratePerSms = {{ $ratePerSms }};
            const minAmount = {{ $minAmount }};

            $('#amount_input').on('input', function() {
                const amount = parseFloat($(this).val()) || 0;

                if (amount >= minAmount) {
                    const credits = Math.floor(amount / ratePerSms);
                    $('#credits_display').val(credits.toLocaleString() + ' SMS');
                } else {
                    $('#credits_display').val('0 SMS');
                }
            });

            // Initialize on page load
            $('#amount_input').trigger('input');
        });
    </script>
@endpush
