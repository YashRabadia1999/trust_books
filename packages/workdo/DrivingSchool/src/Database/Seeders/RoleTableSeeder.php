<?php

namespace Workdo\DrivingSchool\Database\Seeders;


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\DrivingSchool\Entities\DrivingSchoolUtility;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $super_admin = User::where('type','super admin')->first();
        if(!empty($super_admin))
        {
            $companys = User::where('type','company')->get();
            if(count($companys) > 0)
            {
                foreach ($companys as $key => $company) {
                    $role = Role::where('name','driving student')->where('created_by',$company->id)->where('guard_name','web')->exists();
                    if(!$role)
                    {
                        $role                   = new Role();
                        $role->name             = 'driving student';
                        $role->guard_name       = 'web';
                        $role->module           = 'DrivingSchool';
                        $role->created_by       = $company->id;
                        $role->save();
                    }
                }
            }
        }
        DrivingSchoolUtility::GivePermissionToRoles();
        DrivingSchoolUtility::defaultdata();
    }
}
