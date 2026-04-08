<?php

namespace Workdo\DrivingSchool\Database\Seeders;

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
        $module = 'DrivingSchool';

        $permissions  = [
            'drivingschool dashboard manage',
            'drivingschool manage',
            'drivingstudent manage',
            'drivingstudent create',
            'drivingstudent show',
            'drivingstudent edit',
            'drivingstudent delete',
            'drivingvehicle manage',
            'drivingvehicle create',
            'drivingvehicle edit',
            'drivingvehicle delete',
            'drivingclass manage',
            'drivingclass create',
            'drivingclass edit',
            'drivingclass delete',
            'drivingclass show',
            'drivinglesson manage',
            'drivinglesson create',
            'drivinglesson edit',
            'drivinglesson delete',
            'drivinglesson show',
            'drivinginvoice manage',
            'drivinginvoice create',
            'drivinginvoice edit',
            'drivinginvoice show',
            'drivinginvoice delete',
            'drivinginvoice status change',
            'drivinginvoice payment',
            'drivingsetup manage',
            'driving licencetype manage',
            'driving licencetype create',
            'driving licencetype edit',
            'driving licencetype delete',
            'driving testtype manage',
            'driving testtype create',
            'driving testtype edit',
            'driving testtype delete',
            'driving testhub manage',
            'driving testhub show',
            'driving testhub create',
            'driving testhub edit',
            'driving testhub delete',
            'licence traking manage',
            'licence traking show',
            'licence traking create',
            'licence traking edit',
            'licence traking delete',
            'progress report manage',
            'progress report show',
            'progress report create',
            'progress report edit',
            'progress report delete',
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
