@extends('layouts.main')

@section('page-title')
    {{ __('FAQs Question & Answer') }}
@endsection

@section('page-breadcrumb')
    {{ __('FAQs') }},
    {!! __('Question & Answer') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="">
                                {{ $faq->faq_topic }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['store.question.answer', $faqId], 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'id' => 'faq-question-answer-form']) }}
                    <div class="row px-2">
                        <div class="col-12 border">
                            <div class="row py-3 border-bottom">
                                <div class="col">
                                    <h5>{{ __('Questions & Answers') }}</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <button type="button" id="add-question" class="btn btn-sm btn-primary btn-icon"
                                        title="{{ __('Add Question & Answer') }}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="questions-container">
                                @php
                                    $faqQuestionAnswers = $faqQuestionAnswers ?? [];
                                @endphp
                                @if (count($faqQuestionAnswers) > 0)
                                    @foreach ($faqQuestionAnswers as $index => $item)
                                        <div class="row g-3 py-3 border-bottom repeater-item pb-0">
                                            {{ Form::hidden('que_ans_id[]', $item->id) }}
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label("question[$index]", __('Question'), ['class' => 'form-label']) }}<x-required></x-required>
                                                    {{ Form::text('question[]', $item['question'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Question'), 'required' => 'required']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label("answer[$index]", __('Answer'), ['class' => 'form-label']) }}<x-required></x-required>
                                                    {{ Form::textarea('answer[]', $item['answer'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Answer'), 'required' => 'required', 'rows' => 3]) }}
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                                <button type="button" class="btn btn-danger btn-sm delete-question"
                                                    title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 py-3 border-bottom repeater-item pb-0">
                                        {{ Form::hidden("que_ans_id[]", '') }}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                {{ Form::label('question[0]', __('Question'), ['class' => 'form-label']) }}<x-required></x-required>
                                                {{ Form::text('question[]', '', ['class' => 'form-control', 'placeholder' => __('Enter Question'), 'required' => 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('answer[0]', __('Answer'), ['class' => 'form-label']) }}<x-required></x-required>
                                                {{ Form::textarea('answer[]', '', ['class' => 'form-control', 'placeholder' => __('Enter Answer'), 'required' => 'required', 'rows' => 3]) }}
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-danger btn-sm delete-question"
                                                title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <input type="button" value="{{ __('Cancel') }}"
                            onclick="location.href = '{{ route('petcare.faq.index') }}';" class="btn btn-light me-2">
                        @if (isset($faqQuestionAnswers) && $faqQuestionAnswers->count())
                            <input type="submit" id="submit" value="{{ __('Update') }}" class="btn btn-primary">
                        @else
                            <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
                        @endif
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const container = $('#questions-container');

            $('#add-question').on('click', function(e) {
                e.preventDefault();

                const index = container.children('.repeater-item').length;

                const newItem = `
                    <div class="row g-3 py-3 border-bottom repeater-item pb-0">
                        <input type="hidden" name="que_ans_id[]" value="">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">{{ __('Question') }} <span class="text-danger">*</span></label>
                                <input type="text" name="question[]" class="form-control" placeholder="{{ __('Enter Question') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Answer') }} <span class="text-danger">*</span></label>
                                <textarea name="answer[]" class="form-control" placeholder="{{ __('Enter Answer') }}" required rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                            <button type="button" class="btn btn-danger btn-sm delete-question" title="{{ __('Delete') }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                container.append(newItem);
            });

            $(document).off('click', '.delete-question').on('click', '.delete-question', function(e) {
                e.preventDefault();

                const totalItems = container.children('.repeater-item').length;
                const repeaterItem = $(this).closest('.repeater-item');

                if (totalItems > 1) {
                    repeaterItem.remove();
                } else {
                    alert('At least one question-answer must remain.');
                }
            });
        });
    </script>
@endpush
