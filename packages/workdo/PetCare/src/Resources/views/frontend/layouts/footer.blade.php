@php
    $petCareFooterTitle = isset($petCareSystemSetup['petcare_footer_title']->value)
        ? $petCareSystemSetup['petcare_footer_title']->value
        : null;
    $petCareFooterText = isset($petCareSystemSetup['petcare_footer_text']->value)
        ? $petCareSystemSetup['petcare_footer_text']->value
        : null;
    $petCareFooterLinkText = isset($petCareSystemSetup['petcare_footer_link_text']->value)
        ? $petCareSystemSetup['petcare_footer_link_text']->value
        : null;
    $petCareFooterLinkUrl = isset($petCareSystemSetup['petcare_footer_link_url']->value)
        ? $petCareSystemSetup['petcare_footer_link_url']->value
        : null;
    $contactInfoPhoneNo = isset($petCareSystemSetup['contact_info_phone_no']->value)
        ? $petCareSystemSetup['contact_info_phone_no']->value
        : null;
    $contactInfoEmailAddress = isset($petCareSystemSetup['contact_info_email_address']->value)
        ? $petCareSystemSetup['contact_info_email_address']->value
        : null;
    $contactInfoLocation = isset($petCareSystemSetup['contact_info_location']->value)
        ? $petCareSystemSetup['contact_info_location']->value
        : null;
    $contactInfoSocialLinks = \Workdo\PetCare\Entities\PetCareSocialLink::where('workspace', $workspace->id)
        ->where('created_by', $workspace->created_by)
        ->get(['social_media_name', 'social_media_icon', 'social_media_link']);

    $contactInfoStartDay = isset($petCareSystemSetup['contact_info_start_day']->value)
        ? $petCareSystemSetup['contact_info_start_day']->value
        : null;
    $contactInfoEndDay = isset($petCareSystemSetup['contact_info_end_day']->value)
        ? $petCareSystemSetup['contact_info_end_day']->value
        : null;
    $contactInfoOpenTime = isset($petCareSystemSetup['contact_info_open_time']->value)
        ? $petCareSystemSetup['contact_info_open_time']->value
        : null;
    $contactInfoCloseTime = isset($petCareSystemSetup['contact_info_close_time']->value)
        ? $petCareSystemSetup['contact_info_close_time']->value
        : null;
@endphp
<!-- Footer -->
<footer class="bg-dark text-white lg:pt-20 pt-10 pb-5">
    <div class="md:container w-full mx-auto px-4">
        <div class="grid xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 lg:gap-8 gap-6 items-center">
            <!-- Contacts -->
            @if (!empty($contactInfoPhoneNo) && !empty($contactInfoEmailAddress) && !empty($contactInfoLocation))
                <div>
                    <h3 class="text-xl lg:mb-6 mb-5 text-primary">{{ __('Contacts') }}</h3>
                    <div class="lg:space-y-4 space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <a href="tel:{{ $contactInfoPhoneNo ?? '' }}"
                                class="flex-1 hover:text-primary transition duration-300">{{ $contactInfoPhoneNo ?? '' }}</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <a href="mailto:{{ $contactInfoEmailAddress ?? '' }}"
                                class="flex-1 hover:text-primary transition duration-300">{{ $contactInfoEmailAddress ?? '' }}</a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <p class="flex-1 max-w-[200px]">{!! nl2br(e($contactInfoLocation ?? '')) !!}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Center CTA -->
            @if (!empty($petCareFooterTitle))
                <div
                    class="xl:col-span-2 lg:col-span-1 sm:col-span-2 lg:order-none -order-1 text-center flex flex-col items-center justify-center h-full">
                    <h2 class="max-w-xl text-2xl sm:text-3xl lg:text-4xl xl:text-5xl lg:mb-8 mb-5 leading-tight">
                        {{ $petCareFooterTitle ?? '' }}
                    </h2>

                    <a href="{{ route('petcare.frontend.appointment.form.page', $slug) }}"
                        class="btn hover:border-white focus:border-white min-w-[150px]">{{ __('Book an Appointment') }}
                    </a>
                </div>
            @endif

            <!-- Opening Hours & Social -->
            @php
                $startDay = $contactInfoStartDay ? \Carbon\Carbon::parse($contactInfoStartDay)->format('D') : '';
                $endDay = $contactInfoEndDay ? \Carbon\Carbon::parse($contactInfoEndDay)->format('D') : '';
                $openTime = isset($contactInfoOpenTime) ? \Carbon\Carbon::parse($contactInfoOpenTime)->format('h:i A') : '';
                $closeTime = isset($contactInfoCloseTime) ? \Carbon\Carbon::parse($contactInfoCloseTime)->format('h:i A') : '';
            @endphp
            @if (!empty($startDay) && !empty($endDay) && !@empty($openTime) && !@empty($closeTime))
                <div class="sm:w-fit sm:ms-auto">
                    <h3 class="text-xl lg:mb-6 mb-5 text-primary">{{ __('Opening Hours') }}</h3>
                    <ul class="lg:space-y-3 space-y-2 lg:mb-4 mb-4">
                        <li class="flex lg:gap-7 gap-4">
                            <span>{{ $startDay }} - {{ $endDay }} :</span>
                            <span>{{ $openTime }} - {{ $closeTime }}</span>
                        </li>
                    </ul>
                    @if (isset($contactInfoSocialLinks) && $contactInfoSocialLinks->isNotEmpty())
                        <div class="flex gap-5 justify-start text-white">
                            @foreach ($contactInfoSocialLinks as $social)
                                <a href="{{ $social['social_media_link'] }}" target="_blank"
                                    class="hover:text-primary transition duration-300"
                                    title="{{ $social['social_media_name'] }}">
                                    <i class="{{ $social['social_media_icon'] }} lg:text-xl text-base"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div
            class="border-t border-gray-600 text-gray-300 lg:mt-10 mt-5 pt-5 flex items-center justify-center text-center">
            <p>
                &copy; <span id="currentYear"></span>
                {{ $petCareFooterText ?? 'Petcare Is Proudly Powered By ' }} <a href="{{ $petCareFooterLinkUrl ?? '' }}" target="_blank"
                    class="text-primary hover:underline">{{ $petCareFooterLinkText ?? '' }}.</a>
            </p>
        </div>
    </div>
</footer>

<div class="loader-wrapper d-none">
    <span class="site-loader"> </span>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/main.js') }}"></script>
<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
@stack('script')

{{-- Set the hidden star rating value in the review form --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingInput = document.getElementById('rating-input');
        let selectedRating = 0; // fallback in case it isn't global in your other script

        document.querySelectorAll('.star-rating').forEach((star, index) => {
            star.addEventListener('click', function() {
                selectedRating = index + 1;
                if (ratingInput) {
                    ratingInput.value = selectedRating;
                }
            });
        });
    });
</script>

<script>
    function show_toastr(title, message, type) {
        var o, i;
        var icon = '';
        var cls = '';

        if (type == 'success') {
            icon = 'fas fa-check-circle';
            cls = 'success';
        } else {
            icon = 'fas fa-times-circle';
            cls = 'danger';
        }

        $.notify({
            icon: icon,
            title: " " + title,
            message: message,
            url: ""
        }, {
            element: "body",
            type: cls,
            allow_dismiss: true,
            placement: {
                from: 'top',
                align: 'right'
            },
            offset: {
                x: 15,
                y: 15
            },
            spacing: 10,
            z_index: 1080,
            delay: 2000,
            timer: 1800,
            url_target: "_blank",
            mouse_over: false,
            animate: {
                enter: o,
                exit: i
            },
            template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert" style="background-color: #e65742; color: white; padding: 20px;">' +
                '<div class="d-flex align-items-center mb-1">' +
                '<i class="me-2" data-notify="icon"></i>' +
                '<strong data-notify="title">{1}</strong>' +
                '</div>' +
                '<div data-notify="message">{2}</div>' +
                '<button type="button" class="close" data-notify="dismiss" aria-label="Close" style="color: white;">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>'

        });
    }
</script>

@if ($message = Session::get('success'))
    <script>
        show_toastr('{{ __('Success ') }}', '{{ __($message) }}', 'success');
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        show_toastr('{{ __('Error ') }}', '{{ __($message) }}', 'error');
    </script>
@endif
