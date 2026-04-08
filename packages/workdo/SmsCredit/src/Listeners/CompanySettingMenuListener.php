<?php

namespace Workdo\SmsCredit\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    public function handle(CompanySettingMenuEvent $event)
    {
        $module = 'SmsCredit';
        $menu = $event->menu;
        $menu->add([
            'title' => __('SMS Credit Settings'),
            'name' => 'sms-credit-settings',
            'order' => 580,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'sms-credit-settings',
            'module' => $module,
            'permission' => 'sms credit manage'
        ]);
    }
}
