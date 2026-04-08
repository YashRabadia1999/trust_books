<?php

namespace Workdo\BulkSMS\Database\Seeders;

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
        $module = 'BulkSMS';

        $permissions  = [
            'bulksms manage',
            'bulksms_contact manage',
            'bulksms_contact create',
            'bulksms_contact edit',
            'bulksms_contact delete',
            'bulksms_contact import',        
            'group_contact manage', 
            'group_contact create', 
            'group_contact edit', 
            'group_contact delete',     
            'group_contact show', 
            'singlesms_send manage',
            'singlesms_send create',                
            'singlesms_send delete',                
            'bulksms_send manage',                
            'bulksms_send create',                
            'bulksms_send delete',                
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
