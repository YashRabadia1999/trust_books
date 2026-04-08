<?php

namespace Workdo\SocialMediaAnalytics\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'SocialMediaAnalytics';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Operations',
            'title' => __('Social Media Analytics'),
            'icon' => 'ti ti-social',
            'name' => 'socialmediaanalytics',
            'parent' => null,
            'order' => 515,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'socialmediaanalytics manage'
        ]); 
        $menu->add([
            'category' => 'Operations',
            'title' => __('Youtube'),
            'icon' => '',
            'name' => 'socialmediaanalytics-youtube',
            'parent' => 'socialmediaanalytics',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'get.youtube.data',
            'module' => $module,
            'permission' => 'socialmediaanalytics youtube manage'
        ]);        
        $menu->add([
            'category' => 'Operations',
            'title' => __('Instagram'),
            'icon' => '',
            'name' => 'socialmediaanalytics-instagram',
            'parent' => 'socialmediaanalytics',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'get.instagram.data',
            'module' => $module,
            'permission' => 'socialmediaanalytics instagram manage'
        ]); 
        $menu->add([
            'category' => 'Operations',
            'title' => __('Facebook'),
            'icon' => '',
            'name' => 'socialmediaanalytics-facebook',
            'parent' => 'socialmediaanalytics',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'get.facebook.data',
            'module' => $module,
            'permission' => 'socialmediaanalytics facebook manage'
        ]);  
        $menu->add([
            'category' => 'Operations',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'socialmedia-system-setup',
            'parent' => 'socialmediaanalytics',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'social-system.index',
            'module' => $module,
            'permission' => 'socialmediaanalytics system manage'
        ]);
    }
}
