{{-- SMS Notification Settings --}}
<div class="card" id="sms-notification-sidenav">
    <div class="sms-setting-wrap">
        {{ Form::open(['route' => ['sms.notification.setting.store'], 'id' => 'sms-notification-form']) }}
        @method('post')
        <div class="card-header p-3">
            <h5>{{ __('SMS Notification Settings') }}</h5>
        </div>
        <div class="card-body p-3 pb-0">
            <ul class="nav nav-pills gap-2 mb-3" id="pills-tab-sms" role="tablist">
                @php
                    $active = 'active';
                @endphp
                @foreach ($sms_notification_modules as $s_module)
                    @if (Laratrust::hasPermission($s_module . ' manage') ||
                            Laratrust::hasPermission(strtolower($s_module) . ' manage') ||
                            strtolower($s_module) == 'general')
                        <li class="nav-item">
                            <a class="nav-link text-capitalize rounded-1 {{ $active }}"
                                id="pills-{{ strtolower($s_module) }}-tab-sms" data-bs-toggle="pill"
                                href="#pills-{{ strtolower($s_module) }}-sms" role="tab"
                                aria-controls="pills-{{ strtolower($s_module) }}-sms"
                                aria-selected="true">{{ Module_Alias_Name($s_module) }}</a>
                        </li>
                        @php
                            $active = '';
                        @endphp
                    @endif
                @endforeach
            </ul>
            <div class="tab-content" id="pills-tabContent-sms">
                @foreach ($sms_notification_modules as $s_module)
                    <div class="tab-pane fade {{ $loop->index == 0 ? 'active' : '' }} show"
                        id="pills-{{ strtolower($s_module) }}-sms" role="tabpanel"
                        aria-labelledby="pills-{{ strtolower($s_module) }}-tab-sms">
                        <div class="row">
                            @foreach ($sms_notify as $s_action)
                                @if ($s_action->permissions == null || Laratrust::hasPermission($s_action->permissions))
                                    @if ($s_action->module == $s_module)
                                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                                            <div class="rounded-1 card list_colume_notifi p-3 h-100 mb-0">
                                                <div
                                                    class="card-body d-flex align-items-center justify-content-between gap-2 p-0">
                                                    <h6 class="mb-0">
                                                        <label for="{{ $s_action->action }}_sms"
                                                            class="form-label mb-0">{{ $s_action->action }}</label>
                                                    </h6>
                                                    <div class="form-check form-switch d-inline-block text-end">
                                                        <input type="hidden" name="sms_noti[{{ $s_action->action }}]"
                                                            value="0" />
                                                        <input class="form-check-input"
                                                            {{ isset($settings[$s_action->action . '_sms']) && $settings[$s_action->action . '_sms'] == true ? 'checked' : '' }}
                                                            id="sms_notification_{{ $s_action->action }}"
                                                            name="sms_noti[{{ $s_action->action }}]" type="checkbox"
                                                            value="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end flex-wrap p-3">
            <input class="btn btn-print-invoice btn-primary" type="submit" value="{{ __('Save Changes') }}">
        </div>
        {{ Form::close() }}
    </div>
</div>
