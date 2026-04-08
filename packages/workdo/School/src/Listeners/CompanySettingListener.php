<?php

namespace Workdo\School\Listeners;

use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        $module = 'School';
        if (in_array($module, $event->html->modules)) {
            $methodName = 'index';
            $controllerClass = "Workdo\\School\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 330,
                        'module' => $module,
                        'permission' => 'school_management manage'
                    ]);
                }
            }
        }
    }
}
