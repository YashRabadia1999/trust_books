@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Contact Us') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')

    @if ((!empty($contactFormTaglineLabel) && !empty($contactFormTitle) && !empty($contactGoogleMapIframe)) || 
         (!empty($contactInfoTaglineLabel) && !empty($contactInfoTitle))
        )
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">{{ __('Contact Us') }}</h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="index.html">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('Contact Us') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Contact Forms -->
        @if (!empty($contactFormTaglineLabel) && !empty($contactFormTitle) && !empty($contactGoogleMapIframe))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $contactFormTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $contactFormTitle ?? '' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-8 gap-5">
                        <!-- Appointment Booking Form -->
                        <div class="bg-white border rounded-xl xl:p-8 lg:p-6 p-4 shadow-lg relative overflow-hidden group">
                            <div
                                class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                            </div>

                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-primary to-primary/70 rounded-xl flex items-center justify-center me-4 text-white shadow-md">
                                    <i class="fas fa-comments text-xl"></i>
                                </div>
                                <h2 class="flex-1 text-2xl">{{ __('Send Us a Message') }}</h2>
                            </div>

                            {!! Form::open([
                                'route' => ['petcare.frontend.contact.us.store', ['slug' => $slug]],
                                'method' => 'POST',
                                'class' => 'space-y-5',
                            ]) !!}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    {!! Form::label('name', __('Your Name *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::text('name', null, ['class' => 'form-input', 'placeholder' => __('Enter your name'), 'required']) !!}
                                </div>
                                <div>
                                    {!! Form::label('email', __('Email Address *'), ['class' => 'block font-medium mb-2']) !!}
                                    {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => __('your@email.com'), 'required']) !!}
                                </div>
                            </div>

                            <div>
                                {!! Form::label('subject', __('Subject *'), ['class' => 'block font-medium mb-2']) !!}
                                {!! Form::text('subject', null, ['class' => 'form-input', 'placeholder' => __('Enter your subject'), 'required']) !!}
                            </div>

                            <div>
                                {!! Form::label('message', __('Message'), ['class' => 'block font-medium mb-2']) !!}
                                {!! Form::textarea('message', null, [
                                    'class' => 'form-input',
                                    'rows' => 4,
                                    'placeholder' => __('Tell us about any special needs, behavioral concerns, or specific requests...'),
                                ]) !!}
                            </div>

                            {!! Form::button('<i class="fas fa-envelope"></i> ' . __('Send Message'), ['type' => 'submit', 'class' => 'btn w-full']) !!}
                            @if (!empty($contactFormResponseNote))
                                <div class="flex items-center justify-center text-sm text-secondary mt-4">
                                    <i class="fas fa-clock text-base text-primary me-2"></i>
                                    <span class="flex-1">{{ $contactFormResponseNote ?? '' }}</span>
                                </div>
                            @endif
                            {!! Form::close() !!}
                        </div>
                        @php
                            $iframe = $contactGoogleMapIframe ?? '';
                            $cleanedIframe = preg_replace('/\s(width|height)="[^"]*"/i', '', $iframe);
                            $Iframe = str_replace('<iframe', '<iframe width="100%" height="100%"', $cleanedIframe);
                        @endphp
                        <div class="bg-white shadow-lg rounded-xl overflow-hidden lg:h-full h-80">
                            {!! $Iframe !!}
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Contact Information -->
        @if (!empty($contactInfoTaglineLabel) && !empty($contactInfoTitle))
            <section class="lg:py-20 py-10 bg-gradient-to-b from-white to-gray-100">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $contactInfoTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $contactInfoTitle ?? '' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-5">
                        @if (!empty($contactInfoLocationIcon) && !empty($contactInfoLocationTitle) && !empty($contactInfoLocation))
                            <div class="bg-white rounded-xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas {{ $contactInfoLocationIcon ?? '' }} text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-dark mb-4">{{ $contactInfoLocationTitle ?? '' }}</h3>
                                    <p class="text-secondary mb-4">{!! nl2br(e($contactInfoLocation ?? '')) !!}</p>
                                    <a href="https://maps.google.com" target="_blank"
                                        class="inline-flex items-center text-primary font-medium hover:underline">
                                        <span>{{ __('Get Directions') }}</span>
                                        <i class="fas fa-arrow-right rtl:scale-x-[-1] text-base ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @php
                            $startDay = $contactInfoStartDay ? \Carbon\Carbon::parse($contactInfoStartDay)->format('D') : '';
                            $endDay = $contactInfoEndDay ? \Carbon\Carbon::parse($contactInfoEndDay)->format('D') : '';
                            $openTime = isset($contactInfoOpenTime) ? \Carbon\Carbon::parse($contactInfoOpenTime)->format('h:i A') : '';
                            $closeTime = isset($contactInfoCloseTime) ? \Carbon\Carbon::parse($contactInfoCloseTime)->format('h:i A') : '';
                        @endphp
                        @if (!empty($contactInfoPhoneIcon) && !empty($contactInfoPhoneTitle) && !empty($contactInfoPhoneNo))
                            <div class="bg-white rounded-xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas {{ $contactInfoPhoneIcon ?? '' }} text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-dark mb-4">{{ $contactInfoPhoneTitle ?? '' }}</h3>
                                    <a href="tel:{{ $contactInfoPhoneNo ?? '' }}"
                                        class="text-primary hover:underline text-xl font-medium block mb-4">
                                        {{ $contactInfoPhoneNo ?? '' }}
                                    </a>
                                    @if (!empty($startDay) && !empty($endDay) && !empty($openTime) && !empty($closeTime))                                
                                        <div class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $startDay }} - {{ $endDay }} : {{ $openTime }} - {{ $closeTime }}
                                        </div>
                                    @endif
                                    @if (!empty($contactInfoEmergencyNote))
                                        <p class="text-sm text-secondary mt-3">
                                            <span class="font-medium">{{ __('Emergency : ') }}</span>
                                            {{ $contactInfoEmergencyNote ?? '' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if (!empty($contactInfoEmailIcon) && !empty($contactInfoEmailTitle) && !empty($contactInfoEmailAddress))
                            <div class="bg-white rounded-xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas {{ $contactInfoEmailIcon ?? '' }} text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-dark mb-4">{{ $contactInfoEmailTitle ?? '' }}</h3>
                                    <a href="mailto:{{ $contactInfoEmailAddress ?? '' }}"
                                        class="text-primary hover:underline text-xl font-medium block mb-4">{{ $contactInfoEmailAddress ?? '' }}</a>
                                    @if (isset($contactInfoSocialLinks) && $contactInfoSocialLinks->isNotEmpty())
                                        <div class="flex justify-center gap-3">
                                            @foreach ($contactInfoSocialLinks as $social)
                                                <a href="{{ $social['social_media_link'] ?? '#' }}" target="_blank"
                                                    class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl hover:bg-primary hover:text-white transition-all duration-300"
                                                    title="{{ $social['social_media_name'] }}">
                                                    <i class="{{ $social['social_media_icon'] }}"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
