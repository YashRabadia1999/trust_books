<?php

namespace Workdo\PetCare\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'PetCare';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('PetCare Dashboard'),
            'icon' => '',
            'name' => 'petcare dashboard',
            'parent' => 'dashboard',
            'order' => 427,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petcare.dashboard',
            'module' => $module,
            'permission' => 'petcare dashboard manage'
        ]);
        // $menu->add([
        //     'category' => 'Medical',
        //     'title' => __('PetCare'),
        //     'icon' => 'stethoscope',
        //     'name' => 'petcare',
        //     'parent' => null,
        //     'order' => 661,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => '',
        //     'module' => $module,
        //     'permission' => 'petcare manage'
        // ]);        
        $menu->add([
            'category' => 'Medical',
            'title' => __('Services'),
            'icon' => 'home',
            'name' => 'pet services',
            'parent' => 'petcare',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.services.index',
            'module' => $module,
            'permission' => 'pet_services manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Vaccines'),
            'icon' => 'home',
            'name' => 'pet vaccines',
            'parent' => 'petcare',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.vaccines.index',
            'module' => $module,
            'permission' => 'pet_vaccines manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Grooming Packages'),
            'icon' => 'home',
            'name' => 'pet grooming packages',
            'parent' => 'petcare',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.grooming.packages.index',
            'module' => $module,
            'permission' => 'pet_grooming_packages manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Pet Appointments'),
            'icon' => 'home',
            'name' => 'pet appointments',
            'parent' => 'petcare',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.appointments.index',
            'module' => $module,
            'permission' => 'pet_appointments manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Billing & Payments'),
            'icon' => 'home',
            'name' => 'billing & payments',
            'parent' => 'petcare',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petcare.billing.payments.index',
            'module' => $module,
            'permission' => 'billing_payments manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Pet Adoption'),
            'icon' => 'home',
            'name' => 'pet adoption',
            'parent' => 'petcare',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.adoption.index',
            'module' => $module,
            'permission' => 'pet_adoption manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Adoption Request'),
            'icon' => 'home',
            'name' => 'pet adoption request',
            'parent' => 'petcare',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.adoption.request.index',
            'module' => $module,
            'permission' => 'pet_adoption_request manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('Adoption Request Payments'),
            'icon' => 'home',
            'name' => 'adoption request payments',
            'parent' => 'petcare',
            'order' => 80,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'pet.adoption.request.payments.index',
            'module' => $module,
            'permission' => 'adoption_request_payments manage'
        ]);        
        $menu->add([
            'category' => 'Medical',
            'title' => __('Contacts'),
            'icon' => 'home',
            'name' => 'petcare contacts',
            'parent' => 'petcare',
            'order' => 90,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petcare.contact.us.index',
            'module' => $module,
            'permission' => 'petcare_contacts manage'
        ]);
        $menu->add([
            'category' => 'Medical',
            'title' => __('System Setup'),
            'icon' => 'home',
            'name' => 'petcare system setup',
            'parent' => 'petcare',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petcare.brand.setting.index',
            'module' => $module,
            'permission' => 'petcare_brand_setting manage'
        ]);
    }
}
