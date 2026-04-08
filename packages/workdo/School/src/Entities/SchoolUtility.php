<?php

namespace Workdo\School\Entities;

use App\Models\Setting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $student_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_parent manage',
            'school_homework manage',
            'school_classroom manage',
            'school_subject manage',
            'school_timetable manage',
            'school_student manage',
            'school_parent manage',
        ];

        $parent_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_parent manage',
            'school_homework manage',
            'school_classroom manage',
            'school_subject manage',
            'school_timetable manage',
            'school_student manage',
            'school_parent manage',

        ];

        $staff_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_admission manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_teacher manage',
            'school_parent manage',
            'school_homework manage',
            'school_admission create',
            'school_admission show',
            'school_admission edit',
            'school_classroom create',
            'school_classroom manage',
            'school_classroom edit',
            'school_subject manage',
            'school_subject create',
            'school_subject edit',
            'school_timetable manage',
            'school_timetable create',
            'school_timetable edit',
            'school_student manage',
            'school_student create',
            'school_student edit',
            'school_employee manage',
            'school_parent manage',
            'school_parent create',
            'school_parent edit',
            'school_homework manage',
            'school_homework create',
            'school_homework edit',
            'school_teachertimetable manage',
            'school_teachertimetable create',
            'school_teachertimetable edit',
            'school_viewhomework manage'
        ];

        if ($role_id == Null) {
            // student
            $roles_s = Role::where('name', 'student')->get();
            foreach ($roles_s as $role) {
                foreach ($student_permissions as $permission_s) {
                    $permission = Permission::where('name', $permission_s)->first();
                    if (!$role->hasPermission($permission_s)) {
                        $role->givePermission($permission);
                    }
                }
            }

            // parent
            $roles_p = Role::where('name', 'parent')->get();

            foreach ($roles_p as $role) {
                foreach ($parent_permissions as $permission_p) {
                    $permission = Permission::where('name', $permission_p)->first();
                    if (!$role->hasPermission($permission_p)) {
                        $role->givePermission($permission);
                    }
                }
            }

            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }
        } else {
            if ($rolename == 'student') {

                $roles_s = Role::where('name', 'student')->where('id', $role_id)->first();
                foreach ($student_permissions as $permission_s) {
                    $permission = Permission::where('name', $permission_s)->first();
                    if (!$roles_s->hasPermission($permission_s)) {
                        $roles_s->givePermission($permission);
                    }
                }
            } elseif ($rolename == 'parent') {
                $roles_v = Role::where('name', 'parent')->where('id', $role_id)->first();
                foreach ($parent_permissions as $permission_p) {
                    $permission = Permission::where('name', $permission_p)->first();
                    if (!$roles_v->hasPermission($permission_p)) {
                        $roles_v->givePermission($permission);
                    }
                }
            } elseif ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }

    public static function defaultdata($company_id = null, $workspace_id = null)
    {
        $company_setting = [
            "admission_prefix" => "#ADM",
        ];
        $student_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_parent manage',
            'school_homework manage',
            'school_classroom manage',
            'school_subject manage',
            'school_timetable manage',
            'school_student manage',
            'school_parent manage',
            'school_grade manage',

        ];

        $parent_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_parent manage',
            'school_homework manage',
            'school_classroom manage',
            'school_subject manage',
            'school_timetable manage',
            'school_student manage',
            'school_parent manage',
            'school_grade manage',

        ];

        $staff_permissions = [
            'school_dashboard manage',
            'school_management manage',
            'school_admission manage',
            'school_class manage',
            'school_subject manage',
            'school_timetable manage',
            'school_routinelist manage',
            'school_users manage',
            'school_student manage',
            'school_student show',
            'school_teacher manage',
            'school_parent manage',
            'school_homework manage',
            'school_admission create',
            'school_admission show',
            'school_admission edit',
            'school_classroom create',
            'school_classroom manage',
            'school_classroom edit',
            'school_subject manage',
            'school_subject create',
            'school_subject edit',
            'school_timetable manage',
            'school_timetable create',
            'school_timetable edit',
            'school_student manage',
            'school_student create',
            'school_student edit',
            'school_employee manage',
            'school_parent manage',
            'school_parent create',
            'school_parent edit',
            'school_homework manage',
            'school_homework create',
            'school_homework edit',
            'school_teachertimetable manage',
            'school_teachertimetable create',
            'school_teachertimetable edit',
            'school_viewhomework manage',
            'school_grade manage',
            'school_grade create',
            'school_grade edit',
            'school_grade delete',
            'school_attendance manage',
            'school_attendance create',
            'school_attendance edit',
            'school_attendance delete',
            'school_bulkattendance manage'
        ];
        if (!empty($company_id)) {
            $student_role = Role::where('name', 'student')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($student_role)) {
                $student_role = new Role();
                $student_role->name = 'student';
                $student_role->guard_name = 'web';
                $student_role->module = 'School';
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
            $parent_role = Role::where('name', 'parent')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($parent_role)) {
                $parent_role = new Role();
                $parent_role->name = 'parent';
                $parent_role->guard_name = 'web';
                $parent_role->module = 'School';
                $parent_role->created_by = $company_id;
                $parent_role->save();
                foreach ($parent_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$parent_role->hasPermission($permission_v)) {
                            $parent_role->givePermission($permission);
                        }
                    }
                }
            }
            $staff_role = Role::where('name', 'staff')->where('created_by', $company_id)->where('guard_name', 'web')->first();
            if (empty($staff_role)) {
                $staff_role = new Role();
                $staff_role->name = 'staff';
                $staff_role->guard_name = 'web';
                $staff_role->module = 'School';
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
