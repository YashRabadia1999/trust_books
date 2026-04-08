@extends('layouts.main')

@section('page-title')
{{ __('Service Setting') }}
@endsection

@section('page-breadcrumb')
{{ __('Service Setting') }}
@endsection
@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('pet-care::layouts.system-setup')
    </div>
    <div class="col-sm-9">
        <div class="card">
            {{ Form::open(['route' => 'petcare.additional.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true, 'enctype' => 'multipart/form-data']) }}
            
            <div class="card-header">
                <h5 class="mb-0">{{ __('Additional Setting') }}</h5>
            </div>
        
            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 1 : Service setting for home page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_tagline_label', __('Service Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_tagline_label', $petcare_system_setup['service_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Premium Services'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_title', __('Service Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_title', $petcare_system_setup['service_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Pamper Your Furry Friend'), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 2 : Adoption setting for home page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('adoption_tagline_label', __('Adoption Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('adoption_tagline_label', $petcare_system_setup['adoption_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Adopt a Friend'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('adoption_title', __('Adoption Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('adoption_title', $petcare_system_setup['adoption_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Pets Available for Adoption'), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 3 : Grooming packages setting for home page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('package_tagline_label', __('Package Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('package_tagline_label', $petcare_system_setup['package_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Premium Care Packages'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('package_title', __('Package Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('package_title', $petcare_system_setup['package_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Tailored Grooming Packages'), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 4 : Contact banner (CTA) section for home page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('cta_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('cta_heading_title', $petcare_system_setup['cta_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Give Your Friend the Care They Deserve Connect With Team Today!'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('cta_description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::textarea('cta_description', $petcare_system_setup['cta_description'] ?? null, ['class' => 'form-control', 'placeholder' => __('Please Enter Description'), 'required','rows' => 3]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0 pt-0">
                <h5 class="mb-4">{{ __('Section 5 : Header setting for services details page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_details_heading_tagline_label', __('Heading Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_details_heading_tagline_label', $petcare_system_setup['service_details_heading_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Premium Service'), 'required']) }}
                        </div>
                    </div>                  
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 6 : Features setting for services details page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_details_features_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_details_features_tagline_label', $petcare_system_setup['service_details_features_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Comprehensive Care'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_details_features_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_details_features_heading_title', $petcare_system_setup['service_details_features_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __("What's Included"), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 7 : Process Steps setting for services details page') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_details_process_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_details_process_tagline_label', $petcare_system_setup['service_details_process_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Step by Step'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('service_details_process_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('service_details_process_heading_title', $petcare_system_setup['service_details_process_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Our Bathing Process'), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 8 : Header setting for adoption application form') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('adoption_application_form_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('adoption_application_form_tagline_label', $petcare_system_setup['adoption_application_form_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Book Now'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('adoption_application_form_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('adoption_application_form_heading_title', $petcare_system_setup['adoption_application_form_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __("Find Your Perfect Pet"), 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 9 : Header setting for appointment booking form') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('appointment_booking_form_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('appointment_booking_form_tagline_label', $petcare_system_setup['appointment_booking_form_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Book Now'), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('appointment_booking_form_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('appointment_booking_form_heading_title', $petcare_system_setup['appointment_booking_form_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __("Schedule Your Pet's Service & Package"), 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('appointment_booking_form_response_note', __('Response Note'), ['class' => 'form-label']) }}
                            {{ Form::text('appointment_booking_form_response_note', $petcare_system_setup['appointment_booking_form_response_note'] ?? null, ['class' => 'form-control', 'placeholder' => __("We'll contact you within 24 hours to confirm your appointment.")]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pb-0">
                <h5 class="mb-4">{{ __('Section 10 : Openning & Closing Time') }}</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('contact_info_start_day', __('Start Day'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('contact_info_start_day',[
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday',
                                    'Sunday' => 'Sunday',
                                ],
                                $petcare_system_setup['contact_info_start_day'] ?? null,
                                ['class' => 'form-control', 'required'],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('contact_info_end_day', __('End Day'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select(
                                'contact_info_end_day',
                                [
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday',
                                    'Sunday' => 'Sunday',
                                ],
                                $petcare_system_setup['contact_info_end_day'] ?? null,
                                ['class' => 'form-control', 'required'],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('contact_info_open_time', __('Open Time'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::time('contact_info_open_time', $petcare_system_setup['contact_info_open_time'] ?? null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('contact_info_close_time', __('Close Time'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::time('contact_info_close_time', $petcare_system_setup['contact_info_close_time'] ?? null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                </div>
            </div>
                    
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
            </div>
        
            {{ Form::close() }}
        </div>        
    </div>
</div>
@endsection
