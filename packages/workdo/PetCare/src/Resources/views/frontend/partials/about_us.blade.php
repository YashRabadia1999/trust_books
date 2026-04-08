@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('About Us') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if ((!empty($aboutUsTitle) && !empty($aboutUsDescription)) || 
         (!empty($aboutUsTitle) && !empty($aboutUsDescription)) || 
         (!empty($teamMemberTaglineLabel) && !empty($teamMemberTitle))
        )
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">{{ __('About Us') }}</h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="{{ route('petcare.frontend', $workspace->slug) }}">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('About Us') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Our Story Section -->
        @if (!empty($aboutUsTitle) && !empty($aboutUsDescription))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-12 gap-6">
                        <div>
                            <h2 class="text-3xl md:text-4xl lg:text-5xl mb-5">{{ $aboutUsTitle ?? '' }}</h2>
                            <p>
                                {!! $aboutUsDescription ?? '' !!}
                            </p>
                        </div>
                        @if (!empty($aboutUsImage))
                            <div class="relative">
                                <img src="{{ check_file($aboutUsImage) ? get_file($aboutUsImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/default.png') }}{{ '?' . time() }}"
                                    alt="Pet grooming facility" class="mx-auto">
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Counter Section -->
        @if (!empty($milestonesTaglineLabel) && !empty($milestonesTitle))
            <section class="lg:py-20 py-10 bg-gradient-to-b from-white to-gray-100">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $milestonesTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $milestonesTitle ?? '' }}
                        </h2>
                    </div>

                    @if (
                        ($totalPetServices ?? 0) > 0 ||
                            ($totalPetPackages ?? 0) > 0 ||
                            ($totalPetAdoptions ?? 0) > 0 ||
                            ($totalPetAppointments ?? 0) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8 gap-5">
                            <!-- Services Offered -->
                            <div
                                class="bg-white rounded-2xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas fa-paw lg:text-3xl text-2xl"></i>
                                    </div>
                                    <div class="counter xl:text-5xl md:text-4xl text-3xl font-bold mb-2"
                                        data-count="{{ $totalPetServices ?? 0 }}">{{ $totalPetServices ?? 0 }}</div>
                                    <h3 class="lg:text-xl text-base font-quicksand font-semibold text-primary mb-2">
                                        {{ __('Services') }}</h3>
                                </div>
                            </div>

                            <!-- Packages offered -->
                            <div
                                class="bg-white rounded-2xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas fa-box-open lg:text-3xl text-2xl"></i>
                                    </div>
                                    <div class="counter xl:text-5xl md:text-4xl text-3xl font-bold mb-2"
                                        data-count="{{ $totalPetPackages ?? 0 }}">{{ $totalPetPackages ?? 0 }}</div>
                                    <h3 class="lg:text-xl text-base font-quicksand font-semibold text-primary mb-2">
                                        {{ __('Packages') }}</h3>
                                </div>
                            </div>

                            <!-- Adoptions -->
                            <div
                                class="bg-white rounded-2xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas fa-hand-holding-heart lg:text-3xl text-2xl"></i>
                                    </div>
                                    <div class="counter xl:text-5xl md:text-4xl text-3xl font-bold mb-2"
                                        data-count="{{ $totalPetAdoptions ?? 0 }}">{{ $totalPetAdoptions ?? 0 }}</div>
                                    <h3 class="lg:text-xl text-base font-quicksand font-semibold text-primary mb-2">
                                        {{ __('Adoptions') }}</h3>
                                </div>
                            </div>

                            <!-- Appointments -->
                            <div
                                class="bg-white rounded-2xl xl:p-8 lg:p-6 p-4 shadow-lg text-center transform transition-all duration-500 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden group">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 group-hover:bg-primary/10 transition-colors duration-500">
                                </div>
                                <div class="relative z-10">
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mx-auto mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                        <i class="fas fa-calendar-check lg:text-3xl text-2xl"></i>
                                    </div>
                                    <div class="counter xl:text-5xl md:text-4xl text-3xl font-bold mb-2"
                                        data-count="{{ $totalPetAppointments ?? 0 }}">{{ $totalPetAppointments ?? 0 }}</div>
                                    <h3 class="lg:text-xl text-base font-quicksand font-semibold text-primary mb-2">
                                        {{ __('Appointments') }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Team Section -->
        @if (!empty($teamMemberTaglineLabel) && !empty($teamMemberTitle))
            <section class="lg:py-20 py-10 bg-white">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $teamMemberTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $teamMemberTitle ?? '' }}
                        </h2>
                    </div>
                    @if (isset($staff) && $staff->isNotEmpty())
                        <div class="swiper team-swiper">
                            <div class="swiper-wrapper">
                                @foreach ($staff as $member)
                                    <div class="swiper-slide">
                                        <div
                                            class="flex items-end relative z-[1] sm:h-[550px] h-[450px] rounded-full overflow-hidden group">
                                            <img src="{{ check_file($member->avatar) ? get_file($member->avatar) : get_file('uploads/users-avatar/avatar.png') }}"
                                                alt="{{ $member->name }}-image"
                                                class="absolute z-[-1] inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div
                                                class="text-center flex flex-col items-center justify-center w-full md:pt-8 pt-6 md:px-12 px-8 md:pb-16 pb-10 backdrop-blur-md bg-secondary/80 text-white transform transition-all duration-300">
                                                <h3 class="text-2xl mb-2">{{ $member->name }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="arrow-wrapper">
                                <div class="swiper-button-next team-arrow"></div>
                                <div class="swiper-button-prev team-arrow"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>  
        @endif
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
