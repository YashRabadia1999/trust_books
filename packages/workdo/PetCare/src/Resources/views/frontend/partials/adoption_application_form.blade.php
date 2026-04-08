@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('Adoption Application') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')
    @if (!empty($adoptionApplicationFormTaglineLabel) && !empty($adoptionApplicationFormHeadingTitle))
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">{{ __('adopt your buddy') }}</h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="index.html">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('Adoption') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        @if (!empty($adoptionApplicationFormTaglineLabel) && !empty($adoptionApplicationFormHeadingTitle))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <!-- Section Header -->
                    <div class="text-center lg:mb-10 mb-6">
                        <div class="inline-block mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 text-primary font-semibold text-sm leading-none">
                                <i class="fa-regular fa-calendar text-base me-2"></i>
                                {{ $adoptionApplicationFormTaglineLabel ?? '' }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl">
                            {{ $adoptionApplicationFormHeadingTitle ?? '' }}
                        </h2>
                    </div>

                    <div class="max-w-3xl mx-auto">
                        <!-- Pet Information Card -->
                        @if(!empty($petAdoption))
                            @php
                                $petImage = isset($petAdoption->pet_image)? $petAdoption->pet_image: 'packages/workdo/PetCare/src/Resources/assets/image/default.png';
                                $dob = $petAdoption->date_of_birth ?? null;
                            @endphp
                            <div class="bg-white rounded-2xl shadow p-4 mb-8 flex sm:flex-row flex-col sm:items-center lg:gap-8 gap-5">
                                <!-- Pet Image -->
                                <div
                                    class="w-40 h-32 sm:w-40 sm:h-40 flex-shrink-0 overflow-hidden rounded-xl shadow-md border border-gray-200">
                                    <img src="{{ check_file($petImage) ? get_file($petImage) : asset('packages/workdo/PetCare/src/Resources/assets/image/default.png') }}{{ '?' . time() }}"
                                        alt="Pet" class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Pet Details -->
                                <div class="flex-1 grid sm:grid-cols-2 gap-3">
                                    <div class="flex flex-col flex-grow text-sm space-y-3">
                                        <div><span class="font-semibold text-gray-800">{{ __('Pet Name  : ') }}</span>{{ isset($petAdoption->pet_name)? $petAdoption->pet_name : '' }}</div>
                                        <div><span class="font-semibold text-gray-800">{{ __('Species : ') }}</span>{{ isset($petAdoption->species)? $petAdoption->species : '' }}</div>
                                        <div><span class="font-semibold text-gray-800">{{ __('Breed : ') }}</span>{{ isset($petAdoption->breed)? $petAdoption->breed : '' }}</div>
                                    </div>
                                    <div class="flex flex-col flex-grow text-sm space-y-3">
                                        <div><span class="font-semibold text-gray-800">{{ __('Age : ') }}</span>
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
                                        </div>
                                        <div><span class="font-semibold text-gray-800">{{ __('Gender : ') }}</span>{{ isset($petAdoption->gender)? $petAdoption->gender : '' }}</div>
                                        <div><span class="font-semibold text-gray-800">{{ __('Adoption Amount : ') }}</span>{{ isset($petAdoption->adoption_amount) ? currency_format_with_sym($petAdoption->adoption_amount ,$petAdoption->created_by, $petAdoption->workspace)  : '' }}</div>
                                    </div>  
                                </div>                  
                            </div>
                        @endif                   

                        <!-- Appointment Form -->
                        <div class="bg-white h-full rounded-2xl shadow-xl xl:p-8 lg:p-6 p-4 relative overflow-hidden">
                            <!-- Corner Decoration -->
                            <div class="absolute -top-10 -start-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                            <div class="absolute -bottom-10 -end-10 w-20 h-20 bg-primary/5 rounded-full"></div>
                        
                            <h3 class="text-2xl mb-6">{{ __('Adoption Application') }}</h3>
                        
                            {!! Form::open(['url' => route('petcare.frontend.adoption.form.store',[$slug,$adoptionId]), 'method' => 'POST', 'class' => 'grid grid-cols-1 sm:grid-cols-2 gap-5']) !!}
                                <div>
                                    {{ Form::label('full_name', __('Full Name *'), ['class' => 'block font-medium mb-2']) }}
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user w-5 h-5 text-gray-400"></i>
                                        </div>
                                        {{ Form::text('full_name', null, ['class' => 'form-input !ps-10', 'placeholder' => __('Enter your full name'), 'required']) }}
                                    </div>
                                </div>
                        
                                <div>
                                    {{ Form::label('email', __('Email Address *'), ['class' => 'block font-medium mb-2']) }}
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope w-5 h-5 text-gray-400"></i>
                                        </div>
                                        {{ Form::email('email', null, ['class' => 'form-input !ps-10', 'placeholder' => __('your@email.com'), 'required']) }}
                                    </div>
                                </div>
                        
                                <div class="sm:col-span-2">
                                    {{ Form::label('phone', __('Phone Number *'), ['class' => 'block font-medium mb-2']) }}
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone w-5 h-5 text-gray-400"></i>
                                        </div>
                                        {{ Form::tel('phone', null, ['class' => 'form-input !ps-10', 'placeholder' => __('Enter Phone Number'), 'required']) }}
                                    </div>
                                    <div class="text-sm text-gray mt-1">
                                        {{ __('Note : Please use with country code. (ex. +91)') }}
                                    </div>                                                      
                                </div>
                        
                                <div class="sm:col-span-2">
                                    {{ Form::label('address', __('Address *'), ['class' => 'block font-medium mb-2']) }}
                                    <div class="relative">
                                        <div class="absolute top-3 start-3 flex items-start pointer-events-none">
                                            <i class="fas fa-map-marker-alt w-5 h-5 text-gray-400"></i>
                                        </div>
                                        {{ Form::textarea('address', null, ['class' => 'form-input !ps-10', 'rows' => 3, 'placeholder' => __('Enter your complete address including city, state, and zip code'), 'required']) }}
                                    </div>
                                </div>
                        
                                <div class="sm:col-span-2">
                                    {{ Form::label('reason', __('Reason for Adoption *'), ['class' => 'block font-medium mb-2']) }}
                                    <div class="relative">
                                        <div class="absolute top-3 start-3 flex items-start pointer-events-none">
                                            <i class="fas fa-heart w-5 h-5 text-gray-400"></i>
                                        </div>
                                        {{ Form::textarea('reason', null, ['class' => 'form-input !ps-10', 'rows' => 3, 'placeholder' => __('Please tell us why you want to adopt a pet and what kind of home environment you can provide...'), 'required']) }}
                                    </div>
                                </div>
                        
                                <div class="sm:col-span-2">
                                    <button type="submit" class="w-full btn"><i class="fas fa-heart w-4 h-4"></i>{{ __('Submit Application') }}</button>
                                </div>
                        
                            {!! Form::close() !!}
                        </div>                    
                    </div>
                </div>
            </section>
        @endif
    @else
        @include('pet-care::frontend.no-data')
    @endif
@endsection
