<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        @permission('driving licencetype manage')
            <a href="{{ route('driving_licence_type.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('driving_licence_type*') ? 'active' : '' }}">{{ __('Licence Type') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission

        @permission('driving testtype manage')
            <a href="{{ route('driving_test_type.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('driving_test_type*') ? 'active' : '' }}">{{ __('Test Type') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
    </div>
</div>
