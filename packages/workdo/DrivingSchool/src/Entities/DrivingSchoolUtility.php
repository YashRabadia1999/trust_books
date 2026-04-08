<?php

namespace Workdo\DrivingSchool\Entities;

use App\Models\Setting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingSchoolUtility extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingSchoolUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $student_permissions = [
            'drivingschool dashboard manage',
            'user profile manage',
            'user chat manage',
            'drivingschool manage',
            'drivingstudent manage',
            'drivingstudent show',
            'drivingclass manage',
            'drivingclass show',
            'drivinglesson manage',
        ];

        $staff_permissions = [
            'drivingschool dashboard manage',
            'user profile manage',
            'user chat manage',
            'drivingschool manage',
            'drivingstudent manage',
            'drivingstudent create',
            'drivingstudent edit',
            'drivingstudent delete',
            'drivingstudent show',
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
        ];

        if ($role_id == Null) {
            $roles_student = Role::where('name', 'driving student')->get();
            foreach ($roles_student as $role) {
                foreach ($student_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();

                    if (!$role->hasPermission($permission_c)) {
                        $role->givePermission($permission);
                    }
                }
            }

            $roles_staff = Role::where('name', 'staff')->get();

            foreach ($roles_staff as $role) {
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!$role->hasPermission($permission_v)) {
                        $role->givePermission($permission);
                    }
                }
            }

        } else {
            if ($rolename == 'student') {
                $roles_student = Role::where('name', 'driving student')->where('id', $role_id)->first();
                foreach ($student_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if ($permission && !$roles_student->hasPermission($permission_c)) {
                        $roles_student->givePermission($permission);
                    }
                }
            } elseif ($rolename == 'staff') {
                $roles_staff = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if ($permission && !$roles_staff->hasPermission($permission_v)) {
                        $roles_staff->givePermission($permission);
                    }
                }
            }            
        }
    }

    public static function defaultdata($company_id = null, $workspace_id = null)
    {
        $company_setting = [
            "class_prefix" => "#Class00",
        ];
        $student_permissions = [
            'drivingschool dashboard manage',
            'user profile manage',
            'user chat manage',
            'drivingschool manage',
            'drivingstudent manage',
            'drivingstudent show',
            'drivingclass manage',
            'drivingclass show',
            'drivinglesson manage',
        ];

        $staff_permissions = [
            'drivingschool dashboard manage',
            'user profile manage',
            'user chat manage',
            'drivingschool manage',
            'drivingstudent manage',
            'drivingstudent create',
            'drivingstudent edit',
            'drivingstudent delete',
            'drivingstudent show',
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
        ];

        if (!empty($company_id)) {
            $student_role = Role::where('name', 'driving student')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($student_role)) {
                $student_role = new Role();
                $student_role->name = 'student';
                $student_role->guard_name = 'web';
                $student_role->module = 'DrivingSchool';
                $student_role->created_by = $company_id;
                $student_role->save();
                foreach ($student_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$student_role->hasPermission($permission_v)) {
                            $student_role->givePermission($permission);
                        }
                    }
                }
            }
            $staff_role = Role::where('name', 'staff')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($staff_role)) {
                $staff_role = new Role();
                $staff_role->name = 'staff';
                $staff_role->guard_name = 'web';
                $staff_role->module = 'DrivingSchool';
                $staff_role->created_by = $company_id;
                $staff_role->save();
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$staff_role->hasPermission($permission_v)) {
                            $staff_role->givePermission($permission);
                        }
                    }
                }
            }
        }
        if ($company_id == Null) {
            $companys = User::where('type', 'company')->get();
            foreach ($companys as $company) {
                $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
                foreach ($WorkSpaces as $WorkSpace) {
                    foreach ($company_setting as $key => $value) {
                        // Define the data to be updated or inserted
                        $data = [
                            'key' => $key,
                            'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                            'created_by' => $company->id,
                        ];

                        // Check if the record exists, and update or insert accordingly
                        Setting::updateOrInsert($data, ['value' => $value]);
                    }
                }
            }
        } elseif ($workspace_id == Null) {
            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
            foreach ($WorkSpaces as $WorkSpace) {
                foreach ($company_setting as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                        'created_by' => $company->id,
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
            }
        } else {
            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpace = WorkSpace::where('created_by', $company->id)->where('id', $workspace_id)->first();
            foreach ($company_setting as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => !empty($WorkSpace->id) ? $WorkSpace->id : 0,
                    'created_by' => $company->id,
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
        }
    }
}
