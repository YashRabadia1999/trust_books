@extends('pet-care::frontend.layouts.main')
@section('page-title')
    {{ __('FAQ') }} - {{ config('APP_NAME', ucfirst($workspace->name)) }}
@endsection
@section('content')

    @if ((isset($faqs) && $faqs->isNotEmpty()) || (!empty($haveQuestionsTitle) && !empty($haveQuestionsDescription)))
        <!-- common banner -->
        <section class="banner-section relative lg:pt-20 pt-10 lg:pb-24 pb-12 bg-cover sm:bg-[right] bg-[80%] rtl:scale-x-[-1]"
            style="background-image: url('{{ asset('packages/workdo/PetCare/src/Resources/assets/image/common-banner.png') }}');">
            <div class="md:container w-full mx-auto px-4 rtl:scale-x-[-1]">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl mb-4 capitalize">{{ __('Frequently Asked Questions') }}</h2>
                    <ul class="flex flex-wrap items-center capitalize">
                        <li class="flex items-center capitalize">
                            <a href="index.html">{{ __('Home') }}</a>
                            <i class="fas fa-chevron-right mx-2 text-xs rtl:scale-x-[-1]"></i>
                        </li>
                        <li class="font-bold capitalize">{{ __('faqs') }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- FAQ Content -->
        @if ((isset($faqs) && $faqs->isNotEmpty()) || (!empty($haveQuestionsTitle) && !empty($haveQuestionsDescription)))
            <section class="lg:py-20 py-10">
                <div class="md:container w-full mx-auto px-4">
                    <div class="max-w-4xl mx-auto">

                        @if (isset($faqs) && $faqs->isNotEmpty())
                            @foreach ($faqs as $faq)
                                <div class="lg:mb-12 mb-8">
                                    <div class="flex items-center lg:mb-8 mb-6">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-primary to-primary/70 rounded-xl flex items-center justify-center me-4 text-white shadow-md">
                                            <i class="{{ $faq->faq_icon ?? '' }} text-base"></i>
                                        </div>
                                        <h2 class="text-2xl font-bold text-dark">{{ $faq->faq_topic ?? '' }}</h2>
                                    </div>

                                    @if (isset($faq->questionAnswers) && $faq->questionAnswers->isNotEmpty())
                                        <div class="space-y-5">
                                            @foreach ($faq->questionAnswers as $QuestionAnswer)
                                                <div
                                                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border overflow-hidden group">
                                                    <div
                                                        class="faq-toggle lg:p-5 p-4 cursor-pointer flex justify-between items-center gap-3">
                                                        <h3
                                                            class="flex-1 md:text-lg text-base font-medium text-dark group-hover:text-primary transition-colors duration-300">
                                                            {{ $QuestionAnswer->question ?? '' }}
                                                        </h3>
                                                        <div
                                                            class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary/20 transition-colors duration-300">
                                                            <i class="fas fa-plus faq-icon text-primary transition-transform"></i>
                                                        </div>
                                                    </div>
                                                    <div class="faq-content lg:p-5 p-4 border-t">
                                                        <p>{{ $QuestionAnswer->answer ?? '' }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        <!-- Still Have Questions -->
                        @if (!empty($haveQuestionsTitle) && !empty($haveQuestionsDescription))
                            <div class="lg:mt-10 mt-6 bg-primary/10 rounded-2xl xl:p-8 lg:p-6 p-4 text-center relative z-[1] overflow-hidden">
                                <div class="absolute z-[-1] top-0 end-0 w-32 h-32 bg-primary/10 rounded-full -me-16 -mt-16"></div>
                                <div class="absolute z-[-1] bottom-0 start-0 w-32 h-32 bg-primary/10 rounded-full -ms-16 -mb-16">
                                </div>

                                <div>
                                    <h3 class="text-2xl mb-4">{{ $haveQuestionsTitle ?? '' }}</h3>
                                    <p class="mb-5 max-w-xl mx-auto">{{ $haveQuestionsDescription ?? '' }}</p>
                                    <div class="flex flex-wrap justify-center gap-4">
                                        <a href="{{ route('petcare.frontend.contact.us.page', $slug) }}" class="btn">
                                            <i class="fas fa-envelope"></i>
                                            {{ __('Contact Us') }}
                                        </a>
                                        <a href="tel:{{ $contactInfoPhoneNo ?? '' }}" class="btn btn-secondary">
                                            <i class="fas fa-phone"></i>
                                            {{ __('Call Us') }}
                                        </a>
                                    </div>
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