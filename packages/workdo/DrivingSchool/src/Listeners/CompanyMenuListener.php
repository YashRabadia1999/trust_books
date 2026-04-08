<?php

namespace Workdo\DrivingSchool\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'DrivingSchool';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Driving School Dashboard'),
            'icon' => '',
            'name' => 'drivingschool-dashboard',
            'parent' => 'dashboard',
            'order' => 245,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving-dashboard.index',
            'module' => $module,
            'permission' => 'drivingschool dashboard manage'
        ]);
        // $menu->add([
        //     'category' => 'Education',
        //     'title' => __('Driving School'),
        //     'icon' => 'ti ti-bus',
        //     'name' => 'drivingschool',
        //     'parent' => null,
        //     'order' => 659,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => '',
        //     'module' => $module,
        //     'permission' => 'drivingschool manage'
        // ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Student'),
            'icon' => '',
            'name' => 'driving-student',
            'parent' => 'drivingschool',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving-student.index',
            'module' => $module,
            'permission' => 'drivingstudent manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Vehicle'),
            'icon' => '',
            'name' => 'vehicle',
            'parent' => 'drivingschool',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving-vehicle.index',
            'module' => $module,
            'permission' => 'drivingvehicle manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Class'),
            'icon' => '',
            'name' => 'drivingclass',
            'parent' => 'drivingschool',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving-class.index',
            'module' => $module,
            'permission' => 'drivingclass manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Lesson'),
            'icon' => '',
            'name' => 'lesson',
            'parent' => 'drivingschool',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'lesson.index',
            'module' => $module,
            'permission' => 'drivinglesson manage'
        ]);
        // $menu->add([
        //     'category' => 'Education',
        //     'title' => __('Invoice'),
        //     'icon' => '',
        //     'name' => 'invoice',
        //     'parent' => 'drivingschool',
        //     'order' => 30,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => 'drivinginvoice.index',
        //     'module' => $module,
        //     'permission' => 'drivinginvoice manage'
        // ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Progress Report'),
            'icon' => '',
            'name' => 'progress-report',
            'parent' => 'drivingschool',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'progress_report.index',
            'module' => $module,
            'permission' => 'progress report manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Licence Tracking'),
            'icon' => '',
            'name' => 'licence-traking',
            'parent' => 'drivingschool',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'licence_traking.index',
            'module' => $module,
            'permission' => 'licence traking manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('Driving Test Hub'),
            'icon' => '',
            'name' => 'driving-test-hub',
            'parent' => 'drivingschool',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving_test_hub.index',
            'module' => $module,
            'permission' => 'driving testhub manage'
        ]);
        $menu->add([
            'category' => 'Education',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'drivingschool',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'driving_licence_type.index',
            'module' => $module,
            'permission' => 'drivingsetup manage'
        ]);
    }
}
