<?php

namespace Workdo\SmsCredit\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['*'], function ($view) {
            if (Auth::check() && module_is_active('SmsCredit')) {
                $view->getFactory()->startPush('custom_side_menu', view('sms-credit::layouts.sidebar'));
            }
        });
    }

    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
