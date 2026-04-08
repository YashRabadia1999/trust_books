<?php

namespace Workdo\SmsCredit\Providers;

use App\Events\CompanyMenuEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Workdo\SmsCredit\Listeners\CompanyMenuListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CompanyMenuEvent::class => [
            CompanyMenuListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
