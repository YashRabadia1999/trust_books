@extends('layouts.main')

@section('page-title')
    {{ __('About Us') }}
@endsection

@section('page-breadcrumb')
    {{ __('About Us') }}
@endsection
@push('css')
    <link href="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                {{ Form::open(['route' => 'petcare.about.us.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true, 'enctype' => 'multipart/form-data']) }}
                <div class="card-header">
                    <h5 class="mb-0">{{ __('About Us') }}</h5>
                </div>
                <div class="card-body pb-0">
                    <h5 class="mb-4">{{ __('Section 1 : Our Story') }}</h5>
                    <div class="row mt-2 row-gap">
                        <div class="col-sm-4 col-12">
                            <div class="form-group mb-0">
                                {{ Form::label('about_us_title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('about_us_title', $petcare_system_setup['about_us_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Welcome to The Pet Care Company'), 'required']) }}
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-0">
                            {{ Form::label('about_us_image', __('Image'), ['class' => 'form-label']) }}@if (empty($petcare_system_setup['about_us_image']))<x-required></x-required> @endif
                            <div class="choose-file">
                                <label for="about_us_image" class="form-label">
                                    <input type="file" name="about_us_image" id="about_us_image"
                                        class="form-control me-3" style="width: 365px;"
                                        onchange="document.getElementById('about_us_image_preview').src = window.URL.createObjectURL(this.files[0])" @if (empty($petcare_system_setup['about_us_image'])) required @endif >
                                </label>
                                <p class="text-danger d-none" id="validation">{{ __('This field is required.') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <img id="about_us_image_preview" class="mt-2 mb-0" width="50%" src="{{ !empty($petcare_system_setup['about_us_image']) ? asset($petcare_system_setup['about_us_image']) : '' }}" />
                        </div>
                        <div class="form-group col-md-12">
                            {{ Form::label('about_us_description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::textarea('about_us_description',$petcare_system_setup['about_us_description'] ?? null, ['class' => 'form-control summernote', 'rows' => 3, 'placeholder' => __('Description'),'required']) }}
                        </div>
                    </div>
                </div>

                <div class="card-body pb-0">
                    <h5 class="mb-4">{{ __('Section 2 : Milestones') }}</h5>
                    <div class="row mt-2 row-gap">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('milestones_tagline_label', __('Milestones Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('milestones_tagline_label', $petcare_system_setup['milestones_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Our Milestones'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('milestones_title', __('Milestones Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('milestones_title', $petcare_system_setup['milestones_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Celebrating our journey of care and commitment'), 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body pb-0">
                    <h5 class="mb-4">{{ __('Section 3 : Team Memeber') }}</h5>
                    <div class="row mt-2 row-gap">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('team_member_tagline_label', __('Team Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('team_member_tagline_label', $petcare_system_setup['team_member_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Team'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('team_member_title', __('Team Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('team_member_title', $petcare_system_setup['team_member_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Meet Our Experts'), 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input class="btn btn-primary" type="submit" value="{{ __('Save Changes') }}">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush
