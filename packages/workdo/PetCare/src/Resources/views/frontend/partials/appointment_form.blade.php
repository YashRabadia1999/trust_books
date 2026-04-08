@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Service & Package Appointment') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if (!empty($appointmentBookingFormTaglineLabel) && !empty($appointmentBookingFormHeadingTitle))
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">{{ __('Pet Grooming Appointment') }}</h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="index.html">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('Appointment Booking') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- service booking form -->
        @if (!empty($appointmentBookingFormTaglineLabel) && !empty($appointmentBookingFormHeadingTitle))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $appointmentBookingFormTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $appointmentBookingFormHeadingTitle ?? '' }}
                        </h2>
                    </div>

                    <div class="max-w-3xl mx-auto">
                        <div class="bg-white h-full rounded-2xl shadow-xl xl:p-8 lg:p-6 p-4 relative overflow-hidden">
                            <!-- Corner Decoration -->
                            <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                            <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                            {!! Form::open(['url' => route('petcare.frontend.appointment.booking', [$slug]),'method' => 'POST','class' => 'space-y-5',]) !!}

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('name', __('Your Name *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('name', null, ['class' => 'form-input', 'placeholder' => __('Enter your name'), 'required']) !!}
                                </div>
                                <div>
                                    {!! Form::label('contact_number', __('Contact Number *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::tel('contact_number', null, ['class' => 'form-input', 'placeholder' => __('Enter Contact Number'), 'required']) !!}
                                    <div class="text-sm text-gray mt-1">
                                        {{ __('Note : Please use with country code. (ex. +91)') }}
                                    </div> 
                                </div>
                            </div>

                            <div>
                                {!! Form::label('email', __('Email Address *'), ['class' => 'block font-medium mb-2']) !!}
                                {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => __('your@email.com'), 'required']) !!}
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('service_id', __('Service'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::select('service_id[]',$services, old('service_id',$petService->id ?? []),['class' => 'form-input service-select multi-select choices','multiple' => 'multiple','id' => 'service_id_select','data-toggle' => 'select2','placeholder' => __('Select Service'),],) !!}
                                    {{ Form::hidden('service_price',old('service_price', $petService->price ?? 0.0), ['class' => 'form-input service-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                                </div>
                                <div>
                                    {!! Form::label('package_id', __('Package'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::select('package_id[]',$packages,old('package_id',$petPackage->id ?? []),['class' => 'form-input package-select -select choices','multiple' => 'multiple','id' => 'package_id_select','data-toggle' => 'select2','placeholder' => __('Select Package'),],) !!}       
                                    {{ Form::hidden('package_price',old('package_price', $petPackage->total_package_amount ?? 0.0), ['class' => 'form-input package-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}            
                                </div>
                            </div>                    
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('pet_name', __('Pet Name *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('pet_name', null, ['class' => 'form-input', 'placeholder' => __("Pet's name"), 'required']) !!}
                                </div>
                                <div>
                                    {!! Form::label('species', __('Species *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('species', null, ['class' => 'form-input', 'placeholder' => __("Pet's species"), 'required']) !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('breed', __('Breed *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('breed', null, ['class' => 'form-input', 'placeholder' => __("Pet's breed"), 'required']) !!}
                                </div>
                                
                                <div>
                                    {!! Form::label('date_of_birth', __('Date of Birth') . ' *', ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::date('date_of_birth', null, ['class' => 'form-input', 'required' => true, 'placeholder' => __('Select Date of Birth'),'max' => date('Y-m-d')]) !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('gender', __('Gender') . ' *', ['class' => 'block font-medium mb-2']) !!}
                                    <div class="flex items-center space-x-6 gap-3">
                                        <label class="radio-btn inline-flex items-center gap-2">
                                            {!! Form::radio('gender', __('Male'), true, ['class' => 'form-radio text-primary']) !!}
                                            <span class="ml-2">{{ __('Male') }}</span>
                                        </label>
                                        <label class="radio-btn inline-flex items-center gap-2">
                                            {!! Form::radio('gender', __('Female'), false, ['class' => 'form-radio text-primary']) !!}
                                            <span class="ml-2">{{ __('Female') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    {!! Form::label('total_service_package_amount', __('Total Service/Package Amount *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('total_service_package_amount', null, ['class' => 'form-input total-service-package-amount','placeholder' => __('Enter Total Service/Package Amount'),'readonly','required',]) !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('appointment_date', __('Preferred Date *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::date('appointment_date', date('Y-m-d'), ['class' => 'form-input', 'required']) !!}
                                </div>
                                <div>
                                    {!! Form::label('appointment_time', __('Preferred Time *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::select('appointment_time',$time_options,old('appointment_time'),['class' => 'form-input'],) !!}
                                    @if (count($time_options) <= 1)
                                        <div class="text-sm text-gray mt-1">
                                            {{ __('Note : Please set valid Open and Close Time.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                {!! Form::label('address', __('Address'), ['class' => 'block font-medium mb-2']) !!}
                                {!! Form::textarea('address', null, [
                                    'class' => 'form-input',
                                    'rows' => 3,
                                    'placeholder' => __('Enter your complete address including city, state, and zip code...'),
                                ]) !!}
                            </div>

                            <div>
                                {!! Form::label('notes', __('Special Requests or Notes'), ['class' => 'block font-medium mb-2']) !!}
                                {!! Form::textarea('notes', null, [
                                    'class' => 'form-input',
                                    'rows' => 3,
                                    'placeholder' => __('Tell us about any special needs, behavioral concerns, or specific requests...'),
                                ]) !!}
                            </div>

                            <button type="submit" class="btn w-full">
                                <i class="fas fa-calendar-alt"></i> {{ __('Book') }}
                            </button>

                            @if (!empty($appointmentBookingFormResponseNote))
                                <div class="flex items-center justify-center text-sm text-secondary mt-4">
                                    <i class="fas fa-info-circle text-base text-primary me-2"></i>
                                    <span class="flex-1">{{ $appointmentBookingFormResponseNote ?? '' }}</span>
                                </div>
                            @endif

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection


@push('script')
<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/choices.min.js') }}"></script>
<script>
    $(document).ready(function() {
        const maxField = 100;

        // CSRF Token for all AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize total amount on page load
        updateTotalAmount();

        // On Service change
        $(document).on('change', '.service-select', function() {
            const serviceIds = $(this).val() || [];

            if (serviceIds.length > 0) {
                $.post("{{ route('front.get.multipul.service.price',['slug' => $slug]) }}", {
                    serviceIds: serviceIds
                }, function(res) {
                    let totalServicePrice = 0;
                    if (res && res.prices) {
                        res.prices.forEach(function(price) {
                            totalServicePrice += parseFloat(price) || 0;
                        });
                    }
                    $('.service-price').val(totalServicePrice.toFixed(2));
                    updateTotalAmount();
                }).fail(function() {
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
        $(document).on('change', '.package-select', function() {
            const packageIds = $(this).val() || [];

            if (packageIds.length > 0) {
                $.post("{{ route('front.get.multipul.package.price',['slug' => $slug]) }}", {
                    packageIds: packageIds
                }, function(res) {
                    let totalPackagePrice = 0;
                    if (res && res.prices) {
                        res.prices.forEach(function(price) {
                            totalPackagePrice += parseFloat(price) || 0;
                        });
                    }
                    $('.package-price').val(totalPackagePrice.toFixed(2));
                    updateTotalAmount();
                }).fail(function() {
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