<?php

namespace Workdo\SmsCredit\Listeners;

use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    public function handle(CompanySettingEvent $event)
    {
        $module = 'SmsCredit';
        $methodName = 'index';
        $controllerName = 'Company\SettingsController';

        if (class_exists(\Workdo\SmsCredit\Http\Controllers\Company\SettingsController::class)) {
            $controller = \App::make(\Workdo\SmsCredit\Http\Controllers\Company\SettingsController::class);
            return $controller->callAction($methodName, $parameters = []);
        } else {
            return '';
        }
    }
}
