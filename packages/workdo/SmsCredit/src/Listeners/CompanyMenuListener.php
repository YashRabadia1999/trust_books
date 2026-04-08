<?php

namespace Workdo\SmsCredit\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'SmsCredit';
        $menu = $event->menu;

        $menu->add([
            'category' => 'Productivity',
            'title' => __('SMS Credits'),
            'icon' => 'ti ti-credit-card',
            'name' => 'sms-credit',
            'parent' => null,
            'order' => 1260,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('Buy Credits'),
            'icon' => '',
            'name' => 'buy-sms-credit',
            'parent' => 'sms-credit',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.create',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('My Balance'),
            'icon' => '',
            'name' => 'sms-credit-balance',
            'parent' => 'sms-credit',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.balance',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('Purchase History'),
            'icon' => '',
            'name' => 'sms-credit-history',
            'parent' => 'sms-credit',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
    }
}
