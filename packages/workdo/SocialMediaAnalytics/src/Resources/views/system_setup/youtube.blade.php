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
        <div class="card" id="socialmedia-youtube-sidenav">
            {{ Form::open(['route' => 'socialmediaanalytics-youtube.store', 'enctype' => 'multipart/form-data']) }}
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <h5 class="">{{ __('Youtube') }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label ">{{ __('Google Client ID') }}</label> <br>
                            <input class="form-control" placeholder="{{ __('Google Client ID') }}" name="social_media_youtube_google_client_id" type="text"
                                value="{{ isset($settings['social_media_youtube_google_client_id']) ? $settings['social_media_youtube_google_client_id'] :'' }}">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label ">{{ __('Google Client Secret') }}</label> <br>
                            <input class="form-control" placeholder="{{ __('Google Client Secret') }}" name="social_media_youtube_google_client_secret" type="text"
                                value="{{ isset($settings['social_media_youtube_google_client_secret']) ? $settings['social_media_youtube_google_client_secret'] : '' }}">
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="StoreLink" style="display: block">
                                <label for="store_link" class="form-label">{{__('Google Redirect Uri')}}</label>
                                <div class="input-group gap-2">
                                    <input type="text" value="{{ isset($settings['social_media_youtube_google_redirect_uri']) ? $settings['social_media_youtube_google_redirect_uri'] : env('APP_URL').'social/auth/callback'  }}" id="myInput" class="form-control rounded-1 d-inline-block" readonly="">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" onclick="CopyFunction()" id="google_redirect_url"><i class="far fa-copy"></i>
                                            {{__('Copy Link')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('social.youtube.auth') }}"><button class="btn btn-print-invoice btn-primary me-1" type="button">{{ __('Google Auth') }}</button></a>
                <input class="btn btn-print-invoice  btn-primary m-r-12" type="submit"
                    value="{{ __('Save Changes') }}">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
<script>
    function CopyFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");

        $('#google_redirect_url').html('<i class="far fa-copy"></i> Copied!')
        setInterval(() => {
            $('#google_redirect_url').html('<i class="far fa-copy"></i> Copy Link')
        }, 2000);

    }
</script>