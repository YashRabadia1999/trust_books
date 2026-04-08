@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Packages') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if ((isset($petGroomingPackages) && $petGroomingPackages->isNotEmpty()) 
            || (!empty($paymentPoliciesTaglineLabel) && !empty($paymentPoliciesHeadingTitle) && !empty($decodedPaymentPolicies))
        )
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">
                        {{ __('Exclusive Packages for Happy Pets') }}
                    </h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="{{ route('petcare.frontend', $workspace->slug) }}">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('Pricing Packages') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Pricing Packages -->
        @if (isset($petGroomingPackages) && $petGroomingPackages->isNotEmpty())
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <!-- Packages Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-6 max-w-6xl mx-auto">
                        @foreach ($petGroomingPackages as $package)
                            @php
                                $packageFeatures = array_filter(
                                    array_map('trim', explode(',', $package->package_features)),
                                );
                                $positionInRow = (($loop->iteration - 1) % 3) + 1;
                            @endphp
                            <div
                                class="package-card flex flex-col sm:h-full group bg-white rounded-2xl shadow-lg xl:p-8 lg:p-6 p-4 text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl relative overflow-hidden">
                                <div class="flex-1">
                                    <!-- Corner Decoration -->
                                    @if ($positionInRow === 1)
                                        <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/10 rounded-full"></div>
                                    @elseif ($positionInRow === 3)
                                        <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/10 rounded-full"></div>
                                    @endif

                                    <!-- Package Icon -->
                                    <div
                                        class="lg:w-20 lg:h-20 w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                        <i
                                            class="fas {{ $package->package_icon ?? 'fa-dollar-sign' }} lg:text-3xl text-2xl text-primary"></i>
                                    </div>

                                    <h3 class="text-xl lg:text-2xl mb-2">{{ $package->package_name ?? '' }}</h3>
                                    <div class="text-sm text-gray-500 mb-4 line-clamp-3">
                                        {{ $package->description ?? '' }}
                                    </div>
                                    <div class="text-3xl lg:text-4xl font-bold text-primary mb-5">
                                        {{ currency_format_with_sym($package->total_package_amount,$package->created_by, $package->workspace) }}</div>

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
                    <!-- Pagination -->
                        {!! $petGroomingPackages->links('pet-care::frontend.global-pagination') !!}
                    <!-- Pagination End -->
                </div>
            </section>
        @endif

        

        <!-- Payment & Policies -->
        @if (!empty($paymentPoliciesTaglineLabel) && !empty($paymentPoliciesHeadingTitle) && !empty($decodedPaymentPolicies))
            <section class="lg:py-20 py-10 bg-gradient-to-b from-white to-gray-100">
                <div class="md:container w-full mx-auto px-4">
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                {{ $paymentPoliciesTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $paymentPoliciesHeadingTitle ?? '' }}
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8 gap-5">
                        @foreach ($decodedPaymentPolicies ?? [] as $policy)
                            <div
                                class="bg-white rounded-xl lg:p-6 p-4 border shadow-lg hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
                                <div
                                    class="absolute top-0 end-0 w-32 h-32 bg-primary/5 rounded-full -me-16 -mt-16 transform group-hover:scale-110 transition-transform duration-500">
                                </div>

                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-primary to-primary/70 rounded-2xl flex items-center justify-center mb-5 text-white shadow-md transform group-hover:rotate-6 transition-transform duration-300">
                                    <i
                                        class="{{ !empty($policy['policy_icon']) ? $policy['policy_icon'] : 'fas fa-hand-holding-heart' }} text-2xl"></i>
                                </div>

                                <h3 class="text-xl mb-3">{{ !empty($policy['policy_title']) ? $policy['policy_title'] : '' }}
                                </h3>

                                <p class="mb-4 line-clamp-3">
                                    {{ !empty($policy['policy_description']) ? $policy['policy_description'] : '' }}</p>

                                @if (!empty($policy['policy_tag']))
                                    <div class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-sm">
                                        {{ !empty($policy['policy_tag']) ? $policy['policy_tag'] : '' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif  

    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
