<?php

namespace Workdo\PettyCashManagement\Entities;

use App\Models\Setting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PettyCashUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $staff_permissions = [
            'pettycash management manage',
            'expense manage',
            'request manage',
            'request create',
            'request edit',
            'request delete',
            'reimbursement manage',
            'reimbursement create',
            'reimbursement edit',
            'reimbursement delete',
        ];

        if ($role_id == Null) {
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
            if ($rolename == 'staff') {
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
        $staff_permissions = [
            'pettycash management manage',
            'expense manage',
            'request manage',
            'request create',
            'request edit',
            'request delete',
            'reimbursement manage',
            'reimbursement create',
            'reimbursement edit',
            'reimbursement delete',
        ];
        if (!empty($company_id)) {
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
    }
}
