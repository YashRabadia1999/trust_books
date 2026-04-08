@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Home') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if (!empty($bannerTagline) ||
            !empty($bannerHeadingTitle) || !empty($bannerSubTitle) ||
            (!empty($serviceTitle) && !empty($serviceTaglineLabel) && isset($petServices) && $petServices->isNotEmpty()) ||
            (!empty($adoptionTitle) && !empty($adoptionTaglineLabel) && isset($petAdoptions) && $petAdoptions->isNotEmpty()) ||
            (!empty($packageTitle) && !empty($packageTaglineLabel) && isset($petGroomingPackage) && $petGroomingPackage->isNotEmpty()) ||
            (!empty($reviewHeadingTitle) && !empty($reviewTaglineLabel) && !empty($reviewFormHeadingTitle) && !empty($reviewFormSubTitle) && isset($petCareReviews) && $petCareReviews->isNotEmpty()) ||
            (!empty($ctaHeadingTitle) && !empty($ctaDescription))
        )
        <!-- Hero Section -->
        @if (!empty($bannerTagline) && !empty($bannerHeadingTitle) && !empty($bannerSubTitle))
            <section class="relative overflow-hidden lg:py-20 py-10 bg-cover"
                style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/home-bg.png') }}');">
                <div class="container-offset offset-left">
                    <div class="flex flex-col lg:flex-row items-center justify-between">
                        <div class="md:max-w-2xl max-w-lg lg:pe-12 mb-8 lg:mb-0 lg:text-start text-center">
                            <span
                                class="inline-block lg:text-lg font-semibold mb-4 border-s-2 border-primary text-primary ps-3">
                                {{ $bannerTagline ?? '' }}
                            </span>
                            <h2 class="text-3xl sm:text-4xl md:text-5xl xl:text-6xl xxl:text-7xl lg:mb-6 mb-4">
                                {{ $bannerHeadingTitle ?? '' }}
                            </h2>
                            <p class="max-w-lg lg:text-xl lg:mb-8 mb-6 lg:mx-0 mx-auto">
                                {{ $bannerSubTitle ?? '' }}
                            </p>
                            <a href="{{ route('petcare.frontend.appointment.form.page', $slug) }}" class="btn btn-secondary">
                                {{ __('Make A Reservation') }}
                            </a>
                        </div>
                        @if (!empty($decodedbannerImages) && count($decodedbannerImages) > 0)
                            <div class="lg:w-1/2 w-full relative">
                                <div class="swiper hero-swiper !p-0 !m-0">
                                    <div class="swiper-wrapper lg:!mb-10 !mb-8">
                                        @foreach ($decodedbannerImages ?? [] as $bannerImage)
                                            <div class="swiper-slide">
                                                <div
                                                    class="overflow-hidden xl:h-[500px] lg:h-[300px] h-[250px] w-full lg:border-[12px] lg:border-e-0 border-4 border-primary lg:rounded-s-full rounded-2xl">
                                                    <img src="{{ !empty($bannerImage) && check_file($bannerImage) ? get_file($bannerImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/default.png') }}{{ '?' . time() }}""
                                                        class="h-full w-full object-cover" alt="banner-image">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                                <div
                                    class="lg:block hidden banner-label xl:h-40 xl:w-40 lg:h-32 lg:w-32 h-24 w-24 absolute z-[1] -top-6 -start-6 lg:top-0 lg:-start-8 animate-spin-slow">
                                    <img src="{{ !empty($bannerDecorativeImage) && check_file($bannerDecorativeImage) ? get_file($bannerDecorativeImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/banner-label.png') }}{{ '?' . time() }}"
                                        alt="banner-label">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Services Section -->
        @if (!empty($serviceTaglineLabel) && !empty($serviceTitle))
            <section class="relative lg:py-20 py-10 overflow-hidden">
                <!-- Background Effects -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-secondary/5"></div>
                <div
                    class="absolute top-0 start-0 w-72 h-72 bg-primary/10 rounded-full filter blur-3xl -translate-x-1/2 rtl:translate-x-1/2 -translate-y-1/2">
                </div>
                <div
                    class="absolute bottom-0 end-0 w-96 h-96 bg-primary/10 rounded-full filter blur-3xl translate-x-1/2 rtl:translate-x-1/2 translate-y-1/2">
                </div>

                <div class="md:container w-full mx-auto px-4 relative">
                    <!-- Section Header -->
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                <i class="fas fa-check text-base me-2"></i>
                                {{ $serviceTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $serviceTitle ?? '' }}
                        </h2>
                    </div>

                    <!-- Services Grid -->
                    @if (isset($petServices) && $petServices->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-5 gap-y-8">
                            @foreach ($petServices as $service)
                                <div class="pt-6">
                                    <div
                                        class="group relative flex flex-col h-full bg-white rounded-3xl xl:p-8 lg:p-6 p-4 shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                                        <div class="absolute -top-6 start-8">
                                            <div
                                                class="md:w-16 md:h-16 w-14 h-14 bg-white rounded-2xl shadow-lg flex items-center justify-center transform group-hover:rotate-12 transition duration-300">
                                                <i
                                                    class="fas {{ $service->service_icon ?? 'fa-paw' }} md:text-2xl text-xl text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex flex-col h-full xl:pt-8 pt-10">
                                            <div class="flex-1">
                                                <h3 class="text-xl lg:text-2xl mb-4">
                                                    <a
                                                        href="{{ route('petcare.frontend.service.details.page', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($service->id)]) }}">
                                                        {{ $service->service_name ?? '' }}
                                                    </a>
                                                </h3>
                                                <p class="text-gray-600 mb-5 line-clamp-3">
                                                    {{ $service->description ? $service->description : '' }}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-2xl font-bold text-primary">
                                                    {{ currency_format_with_sym($service->price ?? '0.00' , $service->created_by, $service->workspace) }}
                                                </div>
                                                <a href="{{ route('petcare.frontend.appointment.form.page', ['slug' => $slug,'serviceId' => \Illuminate\Support\Facades\Crypt::encrypt($service->id),'packageId' => 0]) }}"
                                                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 text-primary rounded-full font-semibold hover:bg-primary hover:text-white transition duration-300">
                                                    {{ __('Book Now') }}
                                                    <i class="fas fa-arrow-right rtl:scale-x-[-1]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- View All Services Button -->
                    <div class="text-center lg:mt-8 mt-6">
                        <a href="{{ route('petcare.frontend.services.page', $slug) }}" class="btn btn-secondary group">
                            {{ __('Explore All Services') }} <i class="fas fa-arrow-right rtl:scale-x-[-1]"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Pets for Adoption Section -->
        @if (!empty($adoptionTaglineLabel) && !empty($adoptionTitle))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <!-- Section Header -->
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                <i class="fas fa-heart text-base me-2"></i>
                                {{ $adoptionTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $adoptionTitle ?? '' }}
                        </h2>
                    </div>

                    <!-- Pets Grid -->
                    @if (isset($petAdoptions) && $petAdoptions->isNotEmpty())
                        <div class="swiper pets-swiper pb-8 -mb-8">
                            <div class="swiper-wrapper">
                                @foreach ($petAdoptions as $pet)
                                    @php
                                        $petImage = isset($pet->pet_image)
                                            ? $pet->pet_image
                                            : 'packages/workdo/PetCare/src/Resources/assets/image/default.png';
                                        $tags = array_filter(
                                            array_map('trim', explode(',', $pet->classification_tags)),
                                        );
                                    @endphp
                                    <div class="swiper-slide">
                                        <div
                                            class="flex flex-col h-full bg-white border rounded-2xl overflow-hidden group transition duration-300">
                                            <div class="relative overflow-hidden">

                                                <img src="{{ check_file($petImage) ? get_file($petImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/default.png') }}{{ '?' . time() }}"
                                                    class="w-full h-72 object-cover transform group-hover:scale-105 transition duration-300">

                                                <div class="absolute top-4 end-4 flex gap-2">
                                                    @foreach ($tags as $index => $tag)
                                                        <span
                                                            class="{{ $index % 2 == 0 ? 'bg-primary' : 'bg-green-500' }} text-white px-3 py-1 rounded-full text-sm font-semibold text-xs">
                                                            {{ $tag }}
                                                        </span>
                                                    @endforeach
                                                </div>

                                                <div
                                                    class="absolute bottom-0 start-0 end-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                                    <div class="flex items-center text-white">
                                                        {{-- <i class="fas fa-star me-2"></i>
                                                    <span class="font-semibold">4.9 (120 reviews)</span> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex-1 flex flex-col h-full lg:p-6 p-4">
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <div>
                                                            <h3 class="text-xl lg:text-2xl mb-2">{{ $pet->pet_name }}</h3>
                                                            <div
                                                                class="flex flex-wrap gap-2 gap-y-1 items-center text-gray-600">
                                                                <span>{{ $pet->breed }}</span>
                                                                <span>•</span>
                                                                <span>
                                                                    @php
                                                                        $dob = $pet->date_of_birth ?? null;
                                                                    @endphp
                                                                    @if ($dob)
                                                                        @php
                                                                            $dob = \Carbon\Carbon::parse($dob);
                                                                            $now = \Carbon\Carbon::now();
                                                                            $diffInMonths = $dob->diffInMonths($now);
                                                                            $diff = $dob->diff($now); // Get y, m, d

                                                                            $years = floor($diffInMonths / 12);
                                                                            $remainingMonths = $diffInMonths % 12;
                                                                            $days = $diff->d;
                                                                        @endphp

                                                                        @if ($years > 0 || $remainingMonths > 0)
                                                                            @if ($years > 0)
                                                                                {{ $years }}{{ $remainingMonths > 0 ? '.' . $remainingMonths : '' }}
                                                                                {{ __('year') }}{{ $years > 1 ? 's' : '' }}
                                                                            @else
                                                                                {{ $remainingMonths }}
                                                                                {{ __('month') }}{{ $remainingMonths > 1 ? 's' : '' }}
                                                                            @endif
                                                                            {{ __(' old') }}
                                                                        @elseif ($days > 0)
                                                                            {{ $days }} {{ __('day') }}{{ $days > 1 ? 's' : '' }} {{ __('old') }}
                                                                        @else
                                                                            {{ __('0') }}
                                                                        @endif
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="text-xl lg:text-2xl font-bold text-primary">
                                                            {{ currency_format_with_sym($pet->adoption_amount, $pet->created_by, $pet->workspace) }}</div>
                                                    </div>

                                                    <div class="flex flex-wrap justify-between gap-2 mb-4">
                                                        <div class="flex items-center text-gray-600">
                                                            <i class="fa-regular fa-clock me-2 text-primary"></i>
                                                            <span>{{ ucwords(str_replace('_', ' ', $pet->availability)) }}</span>
                                                        </div>
                                                        <div class="flex items-center text-gray-600">
                                                            <i class="fa-regular fa-circle-check me-2 text-primary"></i>
                                                            <span>{{ ucwords(str_replace('_', ' ', $pet->health_status)) }}</span>
                                                        </div>
                                                    </div>

                                                    <p class="text-gray-600 mb-6 line-clamp-3">
                                                        {{ $pet->description ?? '' }}
                                                    </p>
                                                </div>

                                                <div class="flex flex-wrap items-center justify-between gap-3">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center me-3">
                                                            <i class="fa-regular fa-clock text-base text-primary"></i>
                                                        </div>
                                                        <div class="text-sm">
                                                            <div class="font-semibold text-dark">{{ __('Last Updated') }}
                                                            </div>
                                                            <div class="text-gray-600">
                                                                {{ \Carbon\Carbon::parse($pet->updated_at)->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('petcare.frontend.adoption.form.page',[$slug ,\Illuminate\Support\Facades\Crypt::encrypt($pet->id)]) }}" class="btn">
                                                        {{ __('Adopt Now') }}
                                                        <i class="fas fa-arrow-right rtl:scale-x-[-1]"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Arrows -->
                            <div class="arrow-wrapper">
                                <div class="swiper-button-next pets-arrow"></div>
                                <div class="swiper-button-prev pets-arrow"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Packages Section -->
        @if (!empty($packageTaglineLabel) && !empty($packageTitle))
            <section class="lg:py-20 py-10 relative overflow-hidden bg-gradient-to-b from-white to-primary/5">
                <!-- Background Elements -->
                <div class="absolute top-0 end-0 w-64 h-64 bg-primary/10 rounded-full filter blur-3xl"></div>
                <div class="absolute bottom-0 start-0 w-64 h-64 bg-primary/10 rounded-full filter blur-3xl"></div>

                <!-- Paw Print Decorations -->
                <div class="absolute top-20 start-10 opacity-10">
                    <svg class="w-12 h-12 text-primary" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12,8.5A1.5,1.5 0 0,1 10.5,7A1.5,1.5 0 0,1 12,5.5A1.5,1.5 0 0,1 13.5,7A1.5,1.5 0 0,1 12,8.5M12,2A5,5 0 0,0 7,7C7,9.97 9.03,12 12,12C14.97,12 17,9.97 17,7A5,5 0 0,0 12,2M21.5,18.5L14.5,13.5V14.5L7.5,12.5L3.5,16.5L2,14L7,9L15,12V11L22,16L21.5,18.5Z" />
                    </svg>
                </div>
                <div class="absolute top-40 end-20 opacity-10 rotate-45">
                    <svg class="w-16 h-16 text-primary" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12,8.5A1.5,1.5 0 0,1 10.5,7A1.5,1.5 0 0,1 12,5.5A1.5,1.5 0 0,1 13.5,7A1.5,1.5 0 0,1 12,8.5M12,2A5,5 0 0,0 7,7C7,9.97 9.03,12 12,12C14.97,12 17,9.97 17,7A5,5 0 0,0 12,2M21.5,18.5L14.5,13.5V14.5L7.5,12.5L3.5,16.5L2,14L7,9L15,12V11L22,16L21.5,18.5Z" />
                    </svg>
                </div>

                <div class="md:container w-full mx-auto px-4">
                    <!-- Section Header -->
                    <div class="text-center lg:mb-14 sm:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                <i class="fas fa-check text-base me-2"></i>
                                {{ $packageTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $packageTitle ?? '' }}
                        </h2>
                    </div>

                    <!-- Packages Cards -->
                    @if(isset($petGroomingPackage) && $petGroomingPackage->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-6 max-w-6xl mx-auto">
                            @foreach ($petGroomingPackage as $package)
                                @php
                                    $packageFeatures = array_filter(
                                        array_map('trim', explode(',', $package->package_features)),
                                    );
                                @endphp
                                <div
                                    class="package-card flex flex-col sm:h-full group bg-white rounded-2xl shadow-lg xl:p-8 lg:p-6 p-4 text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden">
                                    <div class="flex-1">
                                        @if ($loop->iteration === 1)
                                            <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/10 rounded-full"></div>
                                        @endif

                                        @if ($loop->iteration === 3)
                                            <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/10 rounded-full">
                                            </div>
                                        @endif

                                        <div
                                            class="lg:w-20 lg:h-20 w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                            <i
                                                class="fas {{ $package->package_icon ?? 'fa-dollar-sign' }} lg:text-3xl text-2xl text-primary"></i>
                                        </div>

                                        <h3 class="text-xl lg:text-2xl mb-2">{{ $package->package_name }}</h3>
                                        <div class="text-sm text-gray-500 mb-4 line-clamp-3">{{ $package->description }}</div>
                                        <div class="text-3xl lg:text-4xl font-bold text-primary mb-5">
                                            {{ currency_format_with_sym($package->total_package_amount , $package->created_by, $package->workspace) }}</div>

                                        <ul class="text-start space-y-3 lg:mb-8 mb-5">
                                            @foreach ($packageFeatures as $index => $feature)
                                                <li class="flex items-center justify-center sm:justify-start">
                                                    <i class="fas fa-check text-green-500 me-3"></i>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <a href="{{ route('petcare.frontend.appointment.form.page', ['slug' => $slug,'serviceId' => 0,'packageId' => \Illuminate\Support\Facades\Crypt::encrypt($package->id)]) }}"
                                        class="package-btn w-full inline-flex items-center justify-center text-center px-5 py-3 bg-primary/10 border border-primary/10 text-primary rounded-full font-semibold leading-none hover:bg-primary hover:text-white transition duration-300">
                                        {{ __('Choose Package') }}
                                    </a>                                    
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- CTA Button -->
                    <div class="text-center lg:mt-8 mt-6">
                        <a href="{{ route('petcare.frontend.packages.page', $slug) }}" class="btn btn-secondary group">
                            {{ __('Explore all packages') }}
                            <i class="fas fa-arrow-right rtl:scale-x-[-1]"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Review (Testimonials) Section -->
        @if (!empty($reviewTaglineLabel) && !empty($reviewHeadingTitle) && !empty($reviewFormHeadingTitle) && !empty($reviewFormSubTitle))
            <section class="lg:py-20 py-10 bg-cover"
                style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/testimonial-bg.png') }}');">
                <div class="md:container w-full mx-auto px-4">
                    <!-- Review Form Section -->
                    <div class="grid items-center grid-cols-1 lg:grid-cols-3 lg:gap-8 gap-6">
                        <!-- Testimonials Column -->
                        <div class="lg:col-span-2">
                            <!-- Section Header -->
                            <div class="text-center lg:text-start lg:mb-10 mb-6">
                                <div class="inline-block mb-4">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                        <i class="fas fa-heart text-base me-2"></i>
                                        {{ $reviewTaglineLabel ?? '' }}
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl">
                                    {{ $reviewHeadingTitle ?? '' }}
                                </h2>
                            </div>
                            @if(isset($petCareReviews) && $petCareReviews->isNotEmpty())
                                <div class="swiper testimonial-swiper">
                                    <div class="swiper-wrapper sm:pb-10 pb-6">
                                        @foreach ($petCareReviews as $review)
                                            @if ($review->display_status === 'on' && $review->review_status === 'approved')
                                                <div class="swiper-slide">
                                                    <div class="relative z-[1] md:p-12 p-10 !pb-24 text-center">
                                                        <img class="absolute inset-0 h-full w-full z-[-1]"
                                                            src="{{ asset('packages/workdo/PetCare/src/Resources/assets/image/testimonial-card-bg.png') }}">

                                                        <div class="flex items-center justify-center mb-4">
                                                            <div class="flex text-yellow-400">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="fas fa-star text-xl {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </div>

                                                        <p class="lg:text-lg text-gray-600 mb-5 line-clamp-4">
                                                            "{{ $review->review }}"
                                                        </p>

                                                        <div>
                                                            <h3 class="text-xl mb-1">{{ $review->reviewer_name }}</h3>
                                                            {{-- <div class="text-gray-500">{{ $review->reviewer_email }}</div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            @endif
                        </div>

                        <!-- Review Form Column -->
                        <div class="lg:col-span-1">
                            <div class="text-center lg:text-start lg:mb-6 mb-4">
                                <h3 class="text-xl lg:text-2xl mb-2">
                                    {!! isset($reviewFormHeadingTitle) && !empty($reviewFormHeadingTitle) ? $reviewFormHeadingTitle: __('Share Your') . ' <span class="text-primary">' . __('Experience') . '</span>' !!}  
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $reviewFormSubTitle ?? __('Help other pet parents') }}
                                </p>
                            </div>

                            <div class="bg-white rounded-2xl shadow-xl p-4 lg:p-6 relative overflow-hidden">
                                <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                                <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/5 rounded-full"></div>

                                {{ Form::open(['route' => ['petcare.frontend.review.store', ['slug' => $slug]], 'method' => 'POST', 'class' => 'space-y-4']) }}
                                <div>
                                    {{ Form::label('reviewer_name', __('Your Name *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::text('reviewer_name', old('reviewer_name'), [
                                        'class' => 'form-input !text-sm',
                                        'placeholder' => __('Enter your name'),
                                        'required' => true,
                                    ]) }}
                                </div>
                                <div>
                                    {{ Form::label('reviewer_email', __('Email *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::email('reviewer_email', old('reviewer_email'), ['class' => 'form-input !text-sm', 'placeholder' => __('your@email.com'), 'required' => true]) }}
                                </div>
                                <div>
                                    {{ Form::label('rating', __('Rating *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                class="text-xl star-rating text-gray-300 hover:text-yellow-400 transition-colors"
                                                data-rating="{{ $i }}">★</button>
                                        @endfor
                                    </div>
                                    <input type="number" name="rating" id="rating-input"
                                        value="{{ old('rating', 0) }}" required class="sr-only" min="1"
                                        max="5">
                                </div>
                                <div>
                                    {{ Form::label('review', __('Your Review *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::textarea('review', old('review'), ['class' => 'form-input !text-sm', 'rows' => 3, 'placeholder' => __('Tell us about your experience...'), 'required' => true]) }}
                                </div>
                                <button type="submit" class="w-full btn !text-sm !py-2">
                                    {{ __('Submit Review') }}
                                </button>
                                {{ Form::close() }}

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Contact Banner -->
        @if (!empty($ctaHeadingTitle) && !empty($ctaDescription))
            <section class="lg:py-20 py-10 bg-primary relative z-[1] overflow-hidden">
                <img class="rtl:scale-x-[-1] absolute z-[-1] start-0 bottom-0 max-w-[14vw] sm:max-w-[10vw] w-full"
                    src="{{ asset('packages/workdo/PetCare/src/Resources/assets/image/cat.png') }}" alt="cat-image">
                <img class="rtl:scale-x-[-1] absolute z-[-1] sm:end-10 end-0 bottom-0 max-w-[14vw] sm:max-w-[10vw] w-full"
                    src="{{ asset('packages/workdo/PetCare/src/Resources/assets/image/dog.png') }}" alt="dog-image">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center text-white">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 drop-shadow-lg">
                            {{ $ctaHeadingTitle ?? '' }}
                        </h2>
                        <p class="max-w-3xl mx-auto lg:text-lg xl:text-xl lg:mb-8 mb-5">
                            {{ $ctaDescription ?? '' }}
                        </p>
                        <a href="{{ route('petcare.frontend.contact.us.page', $slug) }}" class="btn hover:!border-white focus:!border-white btn-secondary">
                            {{ 'Contact Our Team' }}
                        </a>
                    </div>
                </div>
            </section>
        @endif
    @else
        @include('pet-care::frontend.no-data')    
    @endif
@endsection
