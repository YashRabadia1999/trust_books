@extends('layouts.main')

@section('page-title')
    {{ __('Contact Us') }}
@endsection
@section('page-breadcrumb')
    {{ __('Contact Us') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                {{ Form::open(['route' => 'petcare.contact.us.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true, 'enctype' => 'multipart/form-data']) }}
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Contact Us') }}</h5>
                </div>
                <div class="card-body pb-0">
                    <h5 class="mb-4">{{ __('Section 1 : Contact Form') }}</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('contact_form_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_form_tagline_label', $petcare_system_setup['contact_form_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Reach Out'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('contact_form_title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_form_title', $petcare_system_setup['contact_form_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Get In Touch With Us'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('contact_form_response_note', __('Response Note'), ['class' => 'form-label']) }}
                                {{ Form::text('contact_form_response_note', $petcare_system_setup['contact_form_response_note'] ?? null, ['class' => 'form-control', 'placeholder' => __('We typically respond to inquiries within 24 hours.')]) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('contact_google_map_iframe', __('Google Map Iframe'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::textarea('contact_google_map_iframe', $petcare_system_setup['contact_google_map_iframe'] ?? '', ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Google Map Iframe'), 'required' => 'required']) }}
                                <small class="text-danger d-block mt-2">
                                    {{ __('Note: Please paste the entire iframe code copied from Google Maps (e.g., <iframe src="..." ...></iframe>).') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <h5 class="mb-4">{{ __('Section 2 : Contact Information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_tagline_label', __('Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_tagline_label', $petcare_system_setup['contact_info_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Get In Touch'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_title', $petcare_system_setup['contact_info_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Contact Information'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_location_title', __('Location Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_location_title', $petcare_system_setup['contact_info_location_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Visit Our Location'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_location', __('Location'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_location', $petcare_system_setup['contact_info_location'] ?? null, ['class' => 'form-control', 'placeholder' => __('Enter Location'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('contact_info_phone_title', __('Phone Number Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_phone_title', $petcare_system_setup['contact_info_phone_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Call Us'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('contact_info_emergency_note', __('Emergency Contact Note'), ['class' => 'form-label']) }}
                                {{ Form::text('contact_info_emergency_note', $petcare_system_setup['contact_info_emergency_note'] ?? null, ['class' => 'form-control', 'placeholder' => __('24/7 Support Available')]) }}
                            </div>
                        </div>
                        <x-mobile divClass="col-md-4" name="contact_info_phone_no" label="{{ __('Phone Number') }}"
                            placeholder="{{ __('Enter Phone Number') }}" id="phone_number"
                            value="{{ $petcare_system_setup['contact_info_phone_no'] ?? '' }}" required>
                        </x-mobile>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_email_title', __('Email Address Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('contact_info_email_title', $petcare_system_setup['contact_info_email_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Email Us'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('contact_info_email_address', __('Email Address'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::email('contact_info_email_address', $petcare_system_setup['contact_info_email_address'] ?? null, ['class' => 'form-control', 'placeholder' => __('info@inkmaster.com'), 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row w-100 mt-3">
                        <div class="form-group col-md-4">
                            {{ Form::label('contact_info_location_icon', __('Choose icon for location'), ['class' => 'form-label']) }}
                            <div id="location-icon-wrapper" class="d-flex flex-wrap gap-2 overflow-auto border"
                                style="max-height: 100px; overflow-y: auto;"></div>
                            <input type="text" id="location-icon-input" name="contact_info_location_icon"
                                class="form-control mt-2" placeholder="{{ __('Selected Icon') }}" readonly
                                value="{{ $petcare_system_setup['contact_info_location_icon'] ?? '' }}">
                        </div>
                        <div class="form-group col-md-4">
                            {{ Form::label('contact_info_phone_icon', __('Choose icon for phone'), ['class' => 'form-label']) }}
                            <div id="phone-icon-wrapper" class="d-flex flex-wrap gap-2 overflow-auto border"
                                style="max-height: 100px;"></div>
                            <input type="text" id="phone-icon-input" name="contact_info_phone_icon"
                                class="form-control mt-2" placeholder="{{ __('Selected Icon') }}" readonly
                                value="{{ $petcare_system_setup['contact_info_phone_icon'] ?? '' }}">
                        </div>

                        <div class="form-group col-md-4">
                            {{ Form::label('contact_info_email_icon', __('Choose icon for email'), ['class' => 'form-label']) }}
                            <div id="email-icon-wrapper" class="d-flex flex-wrap gap-2 overflow-auto border"
                                style="max-height: 100px;"></div>
                            <input type="text" id="email-icon-input" name="contact_info_email_icon"
                                class="form-control mt-2" placeholder="{{ __('Selected Icon') }}" readonly
                                value="{{ $petcare_system_setup['contact_info_email_icon'] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    const iconGroups = {
                        location: [
                            'fa-map-marker-alt', 'fa-map-pin', 'fa-location-arrow', 'fa-compass', 'fa-globe',
                            'fa-street-view', 'fa-map-marked', 'fa-map-signs', 'fa-directions', 'fa-route',
                            'fa-crosshairs', 'fa-bullseye', 'fa-location-crosshairs', 'fa-map', 'fa-mountain',
                            'fa-satellite', 'fa-search-location', 'fa-map-marked-alt', 'fa-flag', 'fa-thumbtack',
                            'fa-landmark', 'fa-location-dot', 'fa-earth-asia', 'fa-compass-drafting', 'fa-location-check'
                        ],
                        phone: [
                            'fa-phone', 'fa-phone-alt', 'fa-phone-square', 'fa-phone-volume', 'fa-headset',
                            'fa-mobile-alt', 'fa-mobile', 'fa-fax', 'fa-phone-slash', 'fa-comment-dots',
                            'fa-volume-control-phone', 'fa-pager', 'fa-tablet-alt', 'fa-signal', 'fa-comment-phone',
                            'fa-tty', 'fa-voicemail', 'fa-circle-phone', 'fa-phone-flip', 'fa-square-phone',
                            'fa-circle-phone-flip', 'fa-phone-arrow-down-left', 'fa-handset'
                        ],
                        email: [
                            'fa-envelope', 'fa-envelope-open', 'fa-paper-plane', 'fa-at', 'fa-inbox',
                            'fa-mail-bulk', 'fa-mailbox', 'fa-envelope-square', 'fa-inbox-in', 'fa-share-square',
                            'fa-paperclip', 'fa-comment-alt', 'fa-comments', 'fa-reply', 'fa-reply-all',
                            'fa-forward', 'fa-dove', 'fa-message', 'fa-envelope-circle-check', 'fa-envelope-open-text',
                            'fa-square-envelope', 'fa-envelope-dot'
                        ],
                    };


    function renderIcons(wrapperId, inputId, iconList) {
        const iconWrapper = document.getElementById(wrapperId);
        const iconInput = document.getElementById(inputId);

        iconWrapper.innerHTML = '';
        iconList.forEach(iconClass => {
            const div = document.createElement('div');
            div.style.maxHeight = '35px';
            div.style.maxWidth = '35px';
            div.style.cursor = 'pointer';
            div.style.display = 'flex';
            div.style.alignItems = 'center';
            div.style.justifyContent = 'center';
            div.style.margin = '2px';

            if (iconInput.value.trim() === `fas ${iconClass}`) {
                div.style.border = '2px solid #007bff';
                div.style.borderRadius = '6px';
                div.style.padding = '2px';
            }

            div.setAttribute('title', iconClass);

            const i = document.createElement('i');
            i.className = `fas ${iconClass}`;
            i.style.fontSize = '24px';

            i.addEventListener('click', () => {
                iconInput.value = `fas ${iconClass}`;
                renderIcons(wrapperId, inputId, iconList);
            });

            div.appendChild(i);
            iconWrapper.appendChild(div);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderIcons('location-icon-wrapper', 'location-icon-input', iconGroups.location);
        renderIcons('phone-icon-wrapper', 'phone-icon-input', iconGroups.phone);
        renderIcons('email-icon-wrapper', 'email-icon-input', iconGroups.email);
    });
</script>
