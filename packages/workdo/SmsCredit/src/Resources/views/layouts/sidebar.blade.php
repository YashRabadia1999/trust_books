@if (Auth::user()->type == 'super admin' || Auth::user()->type == 'company')
    <li class="dash-item dash-hasmenu {{ request()->is('sms-credit*') ? 'active' : '' }}">
        <a href="#!" class="dash-link">
            <span class="dash-micon"><i class="ti ti-message-circle-2"></i></span>
            <span class="dash-mtext">{{ __('SMS Credits') }}</span>
            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
        </a>
        <ul class="dash-submenu">
            <li class="dash-item {{ request()->is('sms-credit') || request()->is('sms-credit/index') ? 'active' : '' }}">
                <a class="dash-link" href="{{ route('sms-credit.index') }}">{{ __('Purchase History') }}</a>
            </li>
            <li class="dash-item {{ request()->is('sms-credit/create') ? 'active' : '' }}">
                <a class="dash-link" href="{{ route('sms-credit.create') }}">{{ __('Buy Credits') }}</a>
            </li>
            <li class="dash-item {{ request()->is('sms-credit/balance') ? 'active' : '' }}">
                <a class="dash-link" href="{{ route('sms-credit.balance') }}">{{ __('Credit Balance') }}</a>
            </li>
        </ul>
    </li>
@endif

