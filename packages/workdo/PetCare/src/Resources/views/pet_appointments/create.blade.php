@extends('layouts.main')
@section('page-title')
    {{ __('Create Pet Appointment') }}
@endsection
@section('page-breadcrumb')
    {{ __('Pet Appointment') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['pet.appointments.store'], 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'id' => 'pet-appointment-form']) }}
                    <div class="row">
                        <!-- Owner Information Section -->
                        <div class="col-sm-6">
                            <h5 class="mb-3">{{ __('Owner Details') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('owner_name', __('Owner Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('owner_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Owner Name')]) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email Address')]) }}
                                    </div>
                                </div>
                                <x-mobile divClass="col-md-6" name="contact_number" label="{{ __('Contact Number') }}"
                                    placeholder="{{ __('Enter Contact Number') }}" required>
                                </x-mobile>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pet Details Section -->
                        <div class="col-sm-6">
                            <h5>{{ __('Appointment Details') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('assigned_staff_id', __('Assigned Staff'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::select('assigned_staff_id', $staff, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Staff'), 'id' => 'staff_id_select']) }}
                                        @if($staff->isEmpty())
                                            <div class="text-xs mt-1">{{ __('Please add Users. ') }}
                                                <a href="{{ route('users.index') }}"><b>{{ __('Add Users') }}</b></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('appointment_date', __('Date of Appoinment'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::date('appointment_date', date('Y-m-d'), ['class' => 'form-control','required' => 'required']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('appointment_time', __('Time of Appoinment'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::select('appointment_time',$time_options,old('appointment_time'),['class' => 'form-control','required' => 'required'],) }}
                                        @if (count($time_options) <= 1)
                                            <div class="text-xs mt-1">{{ __('Note : Please set valid Open and Close Time.') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('service_id', __('Service'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::select('service_id[]', $services, null, ['class' => 'form-control service-select multi-select choices', 'id' => 'service_id_select','multiple' => 'multiple','data-toggle' => 'select2']) }}
                                        {{ Form::hidden('service_price', 0.0, ['class' => 'form-control service-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                                        @if($services->isEmpty())
                                            <div class="text-xs mt-1">{{ __('Please add Services. ') }}
                                                <a href="{{ route('pet.services.index') }}"><b>{{ __('Add Service') }}</b></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('package_id', __('Package'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::select('package_id[]', $packages, null, ['class' => 'form-control package-select multi-select choices', 'id' => 'package_id_select','multiple' => 'multiple','data-toggle' => 'select2']) }}
                                        {{ Form::hidden('package_price', 0.0, ['class' => 'form-control package-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                                        @if($packages->isEmpty())
                                        <div class="text-xs mt-1">{{ __('Please add Packages. ') }}
                                            <a href="{{ route('pet.grooming.packages.index') }}"><b>{{ __('Add Package') }}</b></a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('total_service_package_amount', __('Total Service/Package Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::number('total_service_package_amount', null, ['class' => 'form-control total-service-package-amount', 'required' => 'required', 'placeholder' => __('Enter Total Service/Package Amount'), 'readonly']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                                        {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Notes')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <h5 class="mb-3">{{ __('Pet Details') }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('pet_name', __('Pet Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('pet_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Pet Name')]) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('species', __('Species'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('species', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter species')]) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('breed', __('Breed'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::text('breed', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Breed')]) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('date_of_birth', __('Date of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                                        {{ Form::date('date_of_birth', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Date of Birth'),'max' => date('Y-m-d')]) }}
                                    </div>
                                </div>                                
                                <div class="form-group col-md-6">
                                    {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required />
                                    <div class="d-flex radio-check">
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('gender', __('Male'), true, ['class' => 'form-check-input', 'id' => 'p_male']) }}
                                            <label class="form-check-label" for="p_male">{{ __('Male') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('gender', __('Female'), false, ['class' => 'form-check-input', 'id' => 'p_female']) }}
                                            <label class="form-check-label" for="p_female">{{ __('Female') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <input type="button" value="{{ __('Cancel') }}"
                            onclick="location.href = '{{ route('pet.appointments.index') }}';" class="btn btn-light me-2">
                        <input type="submit" id="submit" value="{{ __('Create') }}" class="btn  btn-primary">
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const maxField = 100;

        // CSRF Token for all AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // On Service change
        $(document).on('change', '.service-select', function () {
            const serviceIds = $(this).val() || [];

            if (serviceIds.length > 0) {
                $.post("{{ route('get.multipul.service.price') }}", {
                    serviceIds: serviceIds
                }, function (res) {
                    let totalServicePrice = 0;
                    if (res && res.prices) {
                        res.prices.forEach(function (price) {
                            totalServicePrice += parseFloat(price) || 0;
                        });
                    }
                    $('.service-price').val(totalServicePrice.toFixed(2));
                    updateTotalAmount();
                }).fail(function () {
                    console.error('Error: Failed to fetch service prices');
                    $('.service-price').val(0);
                    updateTotalAmount();
                });
            } else {
                $('.service-price').val(0);
                updateTotalAmount();
            }
        });

        // On Package change
        $(document).on('change', '.package-select', function () {
            const packageIds = $(this).val() || [];

            if (packageIds.length > 0) {
                $.post("{{ route('get.multipul.package.price') }}", {
                    packageIds: packageIds
                }, function (res) {
                    let totalPackagePrice = 0;
                    if (res && res.prices) {
                        res.prices.forEach(function (price) {
                            totalPackagePrice += parseFloat(price) || 0;
                        });
                    }
                    $('.package-price').val(totalPackagePrice.toFixed(2));
                    updateTotalAmount();
                }).fail(function () {
                    console.error('Error: Failed to fetch package prices');
                    $('.package-price').val(0);
                    updateTotalAmount();
                });
            } else {
                $('.package-price').val(0);
                updateTotalAmount();
            }
        });

        // Calculate total of service + package
        function updateTotalAmount() {
            const servicePrice = parseFloat($('.service-price').val()) || 0;
            const packagePrice = parseFloat($('.package-price').val()) || 0;
            const total = servicePrice + packagePrice;
            $('.total-service-package-amount').val(total.toFixed(2));
        }
    });
</script>
@endpush


