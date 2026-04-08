<?php

namespace Workdo\BulkSMS\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'BulkSMS';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Bulk SMS Settings'),
            'name' => 'bulksms',
            'order' => 639,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'navigation' => 'bulksms-sidenav',
            'module' => $module,
            'permission' => 'bulksms manage'
        ]);
    }
}
