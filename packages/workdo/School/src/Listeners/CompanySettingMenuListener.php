<?php

namespace Workdo\School\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'School';
        $menu = $event->menu;
        $menu->add([
            'title' => 'School & Institute Management Settings',
            'name' => 'school-setting',
            'order' => 330,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'school-sidenav',
            'module' => $module,
            'permission' => 'school_management manage'
        ]);
    }
}
