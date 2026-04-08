@extends('layouts.main')

@section('page-title')
{{ __('Brand Setting') }}
@endsection

@section('page-breadcrumb')
{{ __('Brand Setting') }}
@endsection

@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('pet-care::layouts.system-setup')
    </div>
    <div class="col-sm-9">
        <div class="card">
            {{ Form::open(['route' => 'petcare.brand.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true, 'enctype' => 'multipart/form-data']) }}
                <div class="card-header">
                    <div class="col-12">
                        <h5 class="">
                            {{ __('Brand Setting') }}
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-gap">
                        <div class="col-md-6 col-12 d-flex">
                            <div class="card w-100">
                                <div class="card-header">
                                    <h5 class="small-title">{{ __('Logo') }}</h5>
                                </div>
                                
                                <div class="card-body setting-card setting-logo-box p-3">
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded" style="min-height: 150px;">
                                        <img alt="Logo" src="{{ asset($petcare_logo) }}?{{ time() }}"
                                            id="pre_default_logo" class="img-fluid" style="max-width: 170px; max-height: 80px; object-fit: contain;">
                                    </div>
                                    <div class="choose-files text-center  mt-3">
                                        <label for="petcare_logo">
                                            <div class="bg-primary"> <i
                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                            <input type="file" class="form-control file" name="petcare_logo" id="petcare_logo"
                                                data-filename="petcare_logo"
                                                onchange="document.getElementById('pre_default_logo').src = window.URL.createObjectURL(this.files[0])">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 d-flex">
                            <div class="card w-100">
                                <div class="card-header">
                                    <h5 class="small-title">{{ __('Favicon') }}</h5>
                                </div>
                                <div class="card-body setting-card setting-logo-box p-3">
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded" style="min-height: 150px;">
                                        <img alt="Favicon" src="{{ asset($petcare_favicon) }}?{{ time() }}"
                                            id="img_petcare_favicon"
                                            style="max-width: 70px; max-height: 50px; object-fit: contain;">
                                    </div>
                                    <div class="choose-files text-center mt-3">
                                        <label for="petcare_favicon">
                                            <div class=" bg-primary "> <i
                                                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                            <input type="file" class="form-control file" name="petcare_favicon" id="petcare_favicon"
                                                data-filename="petcare_favicon"
                                                onchange="document.getElementById('img_petcare_favicon').src = window.URL.createObjectURL(this.files[0])">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 row-gap setting-box">
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">
                                <label for="petcare_footer_title" class="mb-2">{{ __('Footer Title') }}</label><x-required></x-required>
                                {{ Form::text('petcare_footer_title', !empty($petcare_system_setup['petcare_footer_title']) ? $petcare_system_setup['petcare_footer_title'] : null, ['class' => 'form-control', 'placeholder' => __('Want To Keep Your Pet In, Our Center?'),'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">
                                <label for="petcare_footer_text" class="mb-2">{{ __('Footer Text') }}</label><x-required></x-required>
                                {{ Form::textarea('petcare_footer_text', !empty($petcare_system_setup['petcare_footer_text']) ? $petcare_system_setup['petcare_footer_text'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Text'),'rows'=>3,'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">
                                <label for="petcare_footer_link_text" class="mb-2">{{ __('Footer Link Text') }}</label><x-required></x-required>
                                {{ Form::text('petcare_footer_link_text', !empty($petcare_system_setup['petcare_footer_link_text']) ? $petcare_system_setup['petcare_footer_link_text'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Link Text'),'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">
                                <label for="petcare_footer_link_url" class="mb-2">{{ __('Footer Link URL') }}</label><x-required></x-required>
                                {{ Form::url('petcare_footer_link_url', !empty($petcare_system_setup['petcare_footer_link_url']) ? $petcare_system_setup['petcare_footer_link_url'] : null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('https://example.com')]) }}
                                <small class="text-danger d-block mt-2">
                                    {{ __('Note: Please enter the full URL including https:// or http://') }}
                                </small> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input class="btn  btn-primary " type="submit" value="{{ __('Save Changes') }}">
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection