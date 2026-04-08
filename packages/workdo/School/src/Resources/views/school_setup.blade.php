<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        @permission('school_branch manage')
            <a href="{{ route('schoolbranches.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('schoolbranches*') ? 'active' : '' }}">{{ __('Branch') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

         @permission('school_department manage')
            <a href="{{ route('schooldepartment.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('schooldepartment*') ? 'active' : '' }}">{{ __('Department') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission

        @permission('school_designation manage')
            <a href="{{ route('schooldesignation.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('schooldesignation*') ? 'active' : '' }}">{{ __('Designation') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        @endpermission
    </div>
</div>
