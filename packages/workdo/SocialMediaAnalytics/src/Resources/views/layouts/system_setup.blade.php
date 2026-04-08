
<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="socialmedia-facebook-sidenav">
        <a href="{{route('social-system.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('socialmedia-system-setup/facebook*') ? 'active' : '')}}">{{__('Facebook')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        <a href="{{ route('socialmediaanalytics-instagram.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('socialmedia-system-setup/instagram*') ? 'active' : '')}}">{{__('Instagram')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        <a href="{{ route('socialmediaanalytics-youtube.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('socialmedia-system-setup/youtube*') ? 'active' : '')}}">{{__('Youtube')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
