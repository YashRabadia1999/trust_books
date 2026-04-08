@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Services') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if (isset($petServices) && $petServices->isNotEmpty())
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">
                        {{ __('Our Services to Your Pet’s Needs') }}
                    </h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="{{ route('petcare.frontend', $workspace->slug) }}">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('Services') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Services Grid -->
        @if (isset($petServices) && $petServices->isNotEmpty())
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <!-- Services Grid -->
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
                                                    href="{{ route('petcare.frontend.service.details.page', [$slug, \Illuminate\Support\Facades\Crypt::encrypt($service->id)]) }}">{{ $service->service_name ?? '' }}</a>
                                            </h3>
                                            <p class="text-gray-600 mb-5 line-clamp-3">
                                                {{ $service->description ? $service->description : '' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-2xl font-bold text-primary">
                                                {{ currency_format_with_sym($service->price ?? '0.00' , $service->created_by, $service->workspace) }}</div>
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
                    <!-- Pagination -->
                        {!! $petServices->links('pet-care::frontend.global-pagination') !!}
                    <!-- Pagination End -->
                </div>
            </section>
        @endif
        
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
