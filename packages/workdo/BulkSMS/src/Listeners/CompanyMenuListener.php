<?php

namespace Workdo\BulkSMS\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'BulkSMS';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Bulk SMS'),
            'icon' => 'ti ti-message-circle',
            'name' => 'bulksms',
            'parent' => null,
            'order' => 1255,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'bulksms manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Contact'),
            'icon' => '',
            'name' => 'bulksms-contact',
            'parent' => 'bulksms',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bulksms-contacts.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Group'),
            'icon' => '',
            'name' => 'bulksms-group',
            'parent' => 'bulksms',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bulksms-group.index',
            'module' => $module,
            'permission' => 'group_contact manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Customer Messages'),
            'icon' => '',
            'name' => 'customer-messages',
            'parent' => 'bulksms',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'customer-messages.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Manage SMS'),
            'icon' => '',
            'name' => 'manage-sms',
            'parent' => 'bulksms',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Send Single SMS'),
            'icon' => '',
            'name' => 'send-single-sms',
            'parent' => 'manage-sms',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bulksms-single-sms.index',
            'module' => $module,
            'permission' => 'singlesms_send manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('Send Bulk SMS'),
            'icon' => '',
            'name' => 'send-bulk-sms',
            'parent' => 'manage-sms',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bulksms-send-sms.index',
            'module' => $module,
            'permission' => 'bulksms_send manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => 'Excel SMS',
            'icon' => '',
            'name' => 'excel-sms',
            'parent' => 'manage-sms',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'excel-sms.create',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
        $menu->add([
            'category' => 'Productivity',
            'title' => __('SMS Credits'),
            'icon' => 'ti ti-credit-card',
            'name' => 'sms-credit',
            'parent' => null,
            'order' => 1260,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('Buy Credits'),
            'icon' => '',
            'name' => 'buy-sms-credit',
            'parent' => 'sms-credit',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.create',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('My Balance'),
            'icon' => '',
            'name' => 'sms-credit-balance',
            'parent' => 'sms-credit',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.balance',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);

        $menu->add([
            'category' => 'Productivity',
            'title' => __('Purchase History'),
            'icon' => '',
            'name' => 'sms-credit-history',
            'parent' => 'sms-credit',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sms-credit.index',
            'module' => $module,
            'permission' => 'bulksms_contact manage'
        ]);
    }
}
