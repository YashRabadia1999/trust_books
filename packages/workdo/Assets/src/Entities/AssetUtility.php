<?php

namespace Workdo\Assets\Entities;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $staff_permissions=[
            'assets manage',
        ];
        if($role_id != null)
        {
            if($rolename == 'staff')
            {
                $roles_v = Role::where('name','staff')->where('id',$role_id)->first();
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    if(!$roles_v->hasPermission($permission_v))
                    {
                        $roles_v->givePermission($permission);
                    }
                }
            }
        }
    }

    public static function AssetQuantity($assets_id = null,$quantity = null, $purchase_date = null, $type ='Asset')
    {
        $assethistory                  = new AssetHistory();
        $assethistory->assets_id       = $assets_id;
        $assethistory->quantity        = $quantity;
        $assethistory->date            = $purchase_date;
        $assethistory->type            = $type;
        $assethistory->created_by      = creatorId();
        $assethistory->workspace_id    = getActiveWorkSpace();
        if ($assethistory->save()) {
            return true;
        } else {
            return false;
        }
    }
}
