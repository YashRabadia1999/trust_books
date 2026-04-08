@php
    $petCareLogo = isset($petCareSystemSetup['petcare_logo']->value)
        ? $petCareSystemSetup['petcare_logo']->value
        : 'packages/workdo/PetCare/src/Resources/assets/image/petcare_logo.png';
    $contactInfoPhoneNo = isset($petCareSystemSetup['contact_info_phone_no']->value)
        ? $petCareSystemSetup['contact_info_phone_no']->value
        : null;
    $contactInfoEmailAddress = isset($petCareSystemSetup['contact_info_email_address']->value)
        ? $petCareSystemSetup['contact_info_email_address']->value
        : null;
    $contactInfoSocialLinks = \Workdo\PetCare\Entities\PetCareSocialLink::where('workspace', $workspace->id)
        ->where('created_by', $workspace->created_by)
        ->get(['social_media_name', 'social_media_icon', 'social_media_link']);
@endphp
<header class="relative sticky top-0 z-10">

    @include('pet-care::frontend.layouts.topbar')

    <!-- Main Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="md:container w-full mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="logo-col lg:max-w-[150px] max-w-[120px] w-full">
                    <h1> 
                        <a href="{{ route('petcare.frontend', $workspace->slug) }}">
                            <img src="{{ check_file($petCareLogo) ? get_file($petCareLogo) : asset('packages/workdo/PetCare/src/Resources/assets/image/petcare_logo.png') }}{{ '?' . time() }}"
                                alt="logo" loading="lazy">
                        </a>
                    </h1>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-6">

                    <a href="{{ route('petcare.frontend', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('Home') }}</span>
                    </a>
                    <a href="{{ route('petcare.frontend.services.page', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('Services') }}</span>
                    </a>
                    <a href="{{ route('petcare.frontend.packages.page', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('Packages') }}</span>
                    </a>
                    <a href="{{ route('petcare.frontend.about.us.page', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('About') }}</span>
                    </a>
                    <a href="{{ route('petcare.frontend.faq.page', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('FAQ') }}</span>
                    </a>
                    <a href="{{ route('petcare.frontend.contact.us.page', $slug) }}"
                        class="font-medium hover:text-primary transition duration-300 flex items-center">
                        <span>{{ __('Contact') }}</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn"
                    class="lg:hidden flex items-center justify-center h-10 w-10 rounded-full bg-primary/10 text-primary">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden">
        <div
            class="bg-white h-full w-full max-w-sm ms-auto transform rtl:-translate-x-full transition-transform duration-300 ease-in-out">
            <div class="p-4 h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-4 pb-4 border-b">
                    <div class="logo-col max-w-[100px] w-full">
                        <a href="{{ route('petcare.frontend', $workspace->slug) }}">
                            <img src="{{ check_file($petCareLogo) ? get_file($petCareLogo) : asset('packages/workdo/PetCare/src/Resources/assets/image/petcare_logo.png') }}{{ '?' . time() }}"
                                alt="logo" loading="lazy">
                        </a>
                    </div>
                    <button id="mobile-menu-close" class="text-gray-500 hover:text-primary transition-all duration-300">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="space-y-4">
                    <a href="{{ route('petcare.frontend', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('Home') }}</a>
                    <a href="{{ route('petcare.frontend.services.page', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('Services') }}</a>
                    <a href="{{ route('petcare.frontend.packages.page', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('Packages') }}</a>
                    <a href="{{ route('petcare.frontend.about.us.page', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('About') }}</a>
                    <a href="{{ route('petcare.frontend.faq.page', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('FAQ') }}</a>
                    <a href="{{ route('petcare.frontend.contact.us.page', $slug) }}"
                        class="block text-base font-medium text-dark hover:text-primary transition-all duration-300">{{ __('Contact') }}</a>

                </nav>

                <!-- Mobile Contact Info -->
                @if (!empty($contactInfoPhoneNo) && !empty($contactInfoEmailAddress))
                    <div class="mt-5 pt-6 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">{{ __('CONTACT INFO') }}</h4>
                        <div class="space-y-3">
                            <a href="tel:{{ $contactInfoPhoneNo ?? '' }}"
                                class="flex items-center text-gray-600 hover:text-primary duration-300">
                                <i class="fas fa-phone text-base me-3 text-primary"></i>
                                {{ $contactInfoPhoneNo ?? '' }}
                            </a>
                            <a href="mailto:{{ $contactInfoEmailAddress ?? '' }}"
                                class="flex items-center text-gray-600 hover:text-primary duration-300">
                                <i class="fas fa-envelope text-base me-3 text-primary"></i>
                                {{ $contactInfoEmailAddress ?? '' }}
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Mobile Social Links -->
                @if (isset($contactInfoSocialLinks) && $contactInfoSocialLinks->isNotEmpty())
                    <div class="mt-6 flex gap-4">
                        @foreach ($contactInfoSocialLinks as $social)
                            <a href="{{ $social['social_media_link'] }}" target="_blank"
                                class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary text-base hover:bg-primary hover:text-white transition-all duration-300"
                                title="{{ $social['social_media_name'] }}">
                                <i class="{{ $social['social_media_icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
