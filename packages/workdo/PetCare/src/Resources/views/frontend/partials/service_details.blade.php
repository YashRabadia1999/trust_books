@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Service Details') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')

    @if ((!empty($petService)) || (!empty($serviceDetailsHeadingTaglineLabel) && !empty($petService)) ||
            (!empty($serviceDetailsFeaturesTaglineLabel) && !empty($serviceDetailsFeaturesHeadingTitle)) ||
            (!empty($serviceDetailsProcessTaglineLabel) && !empty($serviceDetailsProcessHeadingTitle)) ||
            (!empty($serviceReviewHeadingTitle) && !empty($serviceReviewFormHeadingTitle) && !empty($serviceReviewFormSubTitle))
        )
        <!-- common banner -->
        @if (!empty($petService))
            <section
                class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
                style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
                <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                    <div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">
                            {{ !empty($petService->service_name) ? $petService->service_name . ' ' . __('Details') : __('Service Details') }}
                        </h2>
                        <ul class="flex flex-wrap items-center capitalize">
                            <li class="flex items-center capitalize">
                                <a href="{{ route('petcare.frontend', $workspace->slug) }}">{{ __('Home') }}</a>
                                <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                            </li>
                            <li class="font-bold capitalize">
                                {{ !empty($petService->service_name) ? $petService->service_name : __('Service Details') }}</li>
                        </ul>
                    </div>
                </div>
            </section>
        @endif

        <!-- Service Detail -->
        @if (!empty($serviceDetailsHeadingTaglineLabel) && !empty($petService))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-12 gap-6 items-center">
                        <div class="animate-fadeIn">
                            <div
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none mb-4">
                                {{ $serviceDetailsHeadingTaglineLabel ?? '' }}
                            </div>
                            {{-- <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4">
                                {{ !empty($petService->service_name) ? $petService->service_name : 'No Service Name available' }}
                            </h2> --}}
                            <p class="lg:mb-8 mb-5">
                                {{ !empty($petService->description) ? $petService->description : 'No description available' }}
                            </p>
                            <div class="flex flex-wrap gap-5 lg:mb-8 mb-5">
                                <div class="flex items-center bg-white p-4 rounded-xl shadow w-full sm:w-auto">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center me-4">
                                        <i class="fas fa-clock text-xl text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-quicksand font-bold text-primary mb-1">
                                            {{ !empty($petService->duration) ? $petService->duration : '0' }}</div>
                                        <div class="text-sm text-secondary">{{ __('Minutes') }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center bg-white p-4 rounded-xl shadow w-full sm:w-auto">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center me-4">
                                        <i class="fas fa-dollar-sign text-xl text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-quicksand font-bold text-primary mb-1">
                                            {{ !empty($petService->price) ? currency_format_with_sym($petService->price,$petService->created_by, $petService->workspace) : '0' }}
                                        </div>
                                        <div class="text-sm text-secondary">{{ __('Price') }}</div>
                                    </div>
                                </div>
                                @php
                                    $averageRating =
                                        $serviceReviews->count() > 0 ? round($serviceReviews->avg('rating'), 1) : 0;
                                @endphp
                                <div class="flex items-center bg-white p-4 rounded-xl shadow w-full sm:w-auto">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center me-4">
                                        <i class="fas fa-star text-xl text-primary"></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-base font-quicksand font-bold text-primary mb-2 flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($averageRating))
                                                    <i class="fas fa-star text-primary"></i>
                                                @elseif ($i - $averageRating <= 0.5)
                                                    <i class="fas fa-star-half-alt text-primary"></i>
                                                @else
                                                    <i class="far fa-star text-primary"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="text-sm text-secondary mt-4">
                                            {{ __('Customer Rating') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('petcare.frontend.appointment.form.page',[$slug ,\Illuminate\Support\Facades\Crypt::encrypt($petService->id)]) }}" class="btn">
                                <i class="fas fa-calendar-alt"></i>
                                {{ __('Book This Service') }}
                            </a>
                        </div>
                        @php
                            $petImage = isset($petService->service_image)
                                ? $petService->service_image
                                : 'packages/workdo/PetCare/src/Resources/assets/image/default.png';
                        @endphp
                        @if (!empty($petService->service_image))
                            <div class="rounded-xl overflow-hidden relative pt-[75%] w-full shadow-xl">
                                <img src="{{ check_file($petImage) ? get_file($petImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/default.png') }}{{ '?' . time() }}"
                                    alt="Pet bathing service"
                                    class="w-full h-full object-cover absolute inset-0 transform hover:scale-[1.02] transition-transform duration-300">
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- What's Included -->
        @if (!empty($serviceDetailsFeaturesTaglineLabel) && !empty($serviceDetailsFeaturesHeadingTitle))
            <section class="lg:py-20 py-10 bg-gradient-to-b from-white to-primary/5 relative z-[1] overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute z-[-1] top-0 start-0 w-full h-full overflow-hidden pointer-events-none">
                    <div class="absolute z-[-1] top-10 start-10 w-40 h-40 bg-primary/5 rounded-full hidden sm:block"></div>
                    <div class="absolute z-[-1] bottom-10 end-10 w-60 h-60 bg-primary/5 rounded-full"></div>
                    <div class="absolute z-[-1] top-1/4 end-1/4 w-20 h-20 bg-primary/5 rounded-full"></div>

                    <!-- Water droplets -->
                    <div class="water-droplet" style="top: 15%; left: 10%; animation-delay: 0.5s;"></div>
                    <div class="water-droplet" style="top: 25%; right: 15%; animation-delay: 1.2s;"></div>
                    <div class="water-droplet" style="bottom: 20%; left: 20%; animation-delay: 2.1s;"></div>
                    <div class="water-droplet" style="bottom: 30%; right: 25%; animation-delay: 0.8s;"></div>
                </div>

                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $serviceDetailsFeaturesTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $serviceDetailsFeaturesHeadingTitle ?? '' }}
                        </h2>
                    </div>
                    @if (isset($serviceFeatures) && $serviceFeatures->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-5">
                            @foreach ($serviceFeatures as $feature)
                                <div
                                    class="bg-white rounded-xl lg:p-6 p-4 shadow-lg hover:shadow-xl transition-all duration-300 border transform hover:-translate-y-2 group">
                                    <div
                                        class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas {{ $feature->feature_icon ?? 'fa-star' }} text-2xl text-primary"></i>
                                    </div>
                                    <div class="relative pe-8">
                                        <h3 class="text-xl mb-2">{{ $feature->feature_name ?? '' }}</h3>
                                        <span
                                            class="absolute -top-1 -end-1 w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">✓</span>
                                    </div>
                                    <p class="line-clamp-3">{{ $feature->feature_description ?? '' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Process -->
        @if (!empty($serviceDetailsProcessTaglineLabel) && !empty($serviceDetailsProcessHeadingTitle))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $serviceDetailsProcessTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $serviceDetailsProcessHeadingTitle ?? '' }}
                        </h2>
                    </div>

                    @if (isset($serviceProcessSteps) && $serviceProcessSteps->isNotEmpty())
                        <div class="relative">
                            <!-- Desktop Process Line -->
                            {{-- <div class="hidden lg:block absolute top-24 start-0 w-full h-1">
                                <div class="absolute start-[12.5%] top-1/2 -translate-y-1/2 w-[75%] h-1 bg-primary"></div>
                            </div>                 --}}

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8 gap-5">
                                @foreach ($serviceProcessSteps as $index => $step)
                                    <div
                                        class="bg-white lg:p-6 p-4 rounded-xl shadow-lg border text-center relative group hover:shadow-xl transition-all duration-300">
                                        <div
                                            class="lg:w-20 lg:h-20 w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-5 text-2xl font-quicksand font-bold group-hover:scale-110 transition-transform duration-300">
                                            {{ $index + 1 }}</div>
                                        <h3 class="text-xl mb-3">{{ $step->process_name ?? '' }}
                                        </h3>
                                        <p class="line-clamp-3">{{ $step->process_description ?? '' }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Review (Testimonials) Section -->
        @if (!empty($serviceReviewHeadingTitle) && !empty($serviceReviewFormHeadingTitle) && !empty($serviceReviewFormSubTitle))
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
                                        {{ __('Reviews for ') . $petService->service_name }}
                                    </span>
                                </div>
                                <h2 class="text-3xl md:text-4xl lg:text-5xl">
                                    {{ $serviceReviewHeadingTitle ?? '' }}
                                </h2>
                            </div>
                            @if (isset($serviceReviews) && $serviceReviews->isNotEmpty())
                                <div class="swiper testimonial-swiper">
                                    <div class="swiper-wrapper sm:pb-10 pb-6">
                                        @foreach ($serviceReviews as $review)
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
                                    {!! isset($serviceReviewFormHeadingTitle) && !empty($serviceReviewFormHeadingTitle)
                                        ? $serviceReviewFormHeadingTitle
                                        : __('Share Your') . ' <span class="text-primary">' . __('Experience') . '</span>' !!}
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $serviceReviewFormSubTitle ?? __('Help other pet parents') }}
                                </p>
                            </div>

                            <div class="bg-white rounded-2xl shadow-xl p-4 lg:p-6 relative overflow-hidden">
                                <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                                <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                                {{ Form::open(['route' => ['service.frontend.review.store', ['slug' => $slug]], 'method' => 'POST', 'class' => 'space-y-4']) }}
                                {{ Form::hidden('service_id', $petService->id ?? '0') }}
                                <div>
                                    {{ Form::label('service_reviewer_name', __('Your Name *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::text('service_reviewer_name', old('service_reviewer_name'), ['class' => 'form-input !text-sm', 'placeholder' => __('Enter your name'), 'required' => true]) }}
                                </div>
                                <div>
                                    {{ Form::label('service_reviewer_email', __('Email *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::email('service_reviewer_email', old('service_reviewer_email'), ['class' => 'form-input !text-sm', 'placeholder' => __('your@email.com'), 'required' => true]) }}
                                </div>
                                <div>
                                    {{ Form::label('service_rating', __('Rating *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button type="button"
                                                class="text-xl star-rating text-gray-300 hover:text-yellow-400 transition-colors"
                                                data-rating="{{ $i }}">★</button>
                                        @endfor
                                    </div>
                                    <input type="number" name="service_rating" id="rating-input"
                                        value="{{ old('service_rating', 0) }}" required class="sr-only" min="1"
                                        max="5">
                                </div>
                                <div>
                                    {{ Form::label('service_review', __('Your Review *'), ['class' => 'block font-medium mb-2 text-sm']) }}
                                    {{ Form::textarea('service_review', old('service_review'), ['class' => 'form-input !text-sm', 'rows' => 3, 'placeholder' => __('Tell us about your experience...'), 'required' => true]) }}
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
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
