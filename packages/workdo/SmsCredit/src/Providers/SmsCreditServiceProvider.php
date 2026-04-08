<?php

namespace Workdo\SmsCredit\Providers;

use Illuminate\Support\ServiceProvider;

class SmsCreditServiceProvider extends ServiceProvider
{
    protected $moduleName = 'SmsCredit';
    protected $moduleNameLower = 'sms-credit';

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'sms-credit');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
