@extends('layouts.main')

@section('page-title')
    {{ __('FAQs') }}
@endsection

@section('page-breadcrumb')
    {{ __('FAQs') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css') }}">
@endpush
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
                                {{ __('FAQs') }}
                            </h5>
                        </div>
                        <div class="col-1 text-end">
                            @permission('petcare_faq create')
                                <a data-url="{{ route('petcare.faq.create') }}" data-size="md" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="{{ __('Create') }}" title="{{ __('Create') }}"
                                    data-title="{{ __('Create FAQs') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('FAQ Icon') }}</th>
                                <th scope="col">{{ __('FAQ Topic') }}</th>

                                @if (Laratrust::hasPermission('petcare_faq action') ||
                                        Laratrust::hasPermission('petcare_faq edit') ||
                                        Laratrust::hasPermission('petcare_faq delete'))
                                    <th width="100px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $faq)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($faq->faq_icon)
                                            <span class="{{ $faq->faq_icon }} fs-3"></span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $faq->faq_topic ?? '-' }}</td>

                                    @if (Laratrust::hasPermission('petcare_faq edit') || Laratrust::hasPermission('petcare_faq delete'))
                                        <td class="Action">
                                            <div class="d-flex gap-2">
                                                @permission('petcare_faq add question & answer')
                                                    <a class="btn btn-sm align-items-center bg-success"
                                                        data-title="{{ __('Add Question & Answer') }}"
                                                        href="{{ route('show.question.answer.page', \Illuminate\Support\Facades\Crypt::encrypt($faq->id)) }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Add Question & Answer') }}">
                                                        <i class="ti ti-square-plus text-white"></i>
                                                    </a>
                                                @endpermission
                                                @permission('petcare_faq edit')
                                                    <a class="bg-info btn btn-sm" href="javascript:void(0);"
                                                        data-url="{{ route('petcare.faq.edit', \Illuminate\Support\Facades\Crypt::encrypt($faq->id)) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}" data-title="{{ __('Edit FAQs') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                @endpermission
                                                @permission('petcare_faq delete')
                                                    {{ Form::open(['route' => ['petcare.faq.destroy', \Illuminate\Support\Facades\Crypt::encrypt($faq->id)], 'class' => 'm-0', 'id' => 'delete-form-' . $faq->id]) }}
                                                    @method('DELETE')
                                                    <a class="bg-danger btn btn-sm show_confirm" href="javascript:void(0);"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action cannot be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $faq->id }}">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {{ Form::close() }}
                                                @endpermission
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                {{ Form::open(['route' => 'faq.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true]) }}
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Have Questions ?') }}</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('have_questions_title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('have_questions_title', $petcare_system_setup['have_questions_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Still Have Questions?'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('have_questions_description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::textarea('have_questions_description', $petcare_system_setup['have_questions_description'] ?? null, ['class' => 'form-control', 'placeholder' => __('Please Enter Description'), 'required','rows' => 3]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
