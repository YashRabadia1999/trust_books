<?php

namespace Workdo\PettyCashManagement\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'PettyCashManagement';
        $menu = $event->menu;
        // $menu->add([
        //     'category' => 'Finance',
        //     'title' => __('Petty Cash'),
        //     'icon' => 'cash',
        //     'name' => 'pettycashmanagement',
        //     'parent' => null,
        //     'order' => 1365,
        //     'ignore_if' => [],
        //     'depend_on' => [],
        //     'route' => '',
        //     'module' => $module,
        //     'permission' => 'pettycash management manage'
        // ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Petty Cash'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'pettycash',
            'parent' => 'pettycashmanagement',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petty-cash.index',
            'module' => $module,
            'permission' => 'pettycash manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Expense'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'pettycashexpense',
            'parent' => 'pettycashmanagement',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'patty_cash_expense.index',
            'module' => $module,
            'permission' => 'expense manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Petty Cash Request'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'pettycashrequest',
            'parent' => 'pettycashmanagement',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'petty-cash-request.index',
            'module' => $module,
            'permission' => 'request manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Reimbursement'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'reimbursement',
            'parent' => 'pettycashmanagement',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reimbursement.index',
            'module' => $module,
            'permission' => 'reimbursement manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Categories'),
            'icon' => 'ti ti-3d-cube-sphere',
            'name' => 'categories',
            'parent' => 'pettycashmanagement',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'cash_categories.index',
            'module' => $module,
            'permission' => 'categories manage'
        ]);
    }
}
