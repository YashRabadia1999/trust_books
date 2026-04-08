<?php

namespace Workdo\PettyCashManagement\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'PettyCashManagement';

        $permissions  = [
            'pettycash management manage',
            'pettycash manage',
            'pettycash create',
            'pettycash edit',
            'pettycash delete',
            'request manage',
            'request create',
            'request edit',
            'request delete',
            'request approve',
            'reimbursement manage',
            'reimbursement create',
            'reimbursement edit',
            'reimbursement delete',
            'reimbursement approve',
            'categories manage',
            'categories create',
            'categories edit',
            'categories delete',
            'expense manage',
            'expense delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $check = Permission::where('name',$value)->where('module',$module)->exists();
            if($check == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
