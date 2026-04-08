@extends('layouts.main')
@section('page-title')
{{__('System Setup')}}
@endsection
@section('page-breadcrumb')
{{ __('System Setup') }}
@endsection
@section('content')
<div class="row">
    <div class="col-xxl-3 col-lg-4 col-12">
        @include('social-media-analytics::layouts.system_setup')
    </div>
    <div class="col-xl-9">
        <div class="card" id="socialmedia-facebook-sidenav">
            {{ Form::open(['route' => 'socialmediaanalytics-facebook.store', 'enctype' => 'multipart/form-data']) }}
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <h5 class="">{{ __('Facebook') }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-label ">{{ __('Facebook Client ID') }}</label> <br>
                        <input class="form-control" placeholder="{{ __('Facebook Client ID') }}"
                            name="social_media_facebook_client_id" type="text"
                            value="{{ isset($settings['social_media_facebook_client_id']) ? $settings['social_media_facebook_client_id'] : '' }}"
                            id="social_media_facebook_client_id">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label ">{{ __('Facebook Access Token') }}</label> <br>
                        <input class="form-control" placeholder="{{ __('Facebook Access Token') }}"
                            name="social_media_facebook_access_token" type="text"
                            value="{{ isset($settings['social_media_facebook_access_token']) ? $settings['social_media_facebook_access_token'] : '' }}"
                            id="social_media_facebook_access_token">
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <input class="btn btn-print-invoice  btn-primary m-r-12" type="submit"
                    value="{{ __('Save Changes') }}">
            </div>
            {{ Form::close() }}
        </div>
    </div>    
</div>
@endsection