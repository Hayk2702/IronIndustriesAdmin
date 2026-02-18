<?php
namespace App\Traits;

use App\Models\Permission;
use App\Models\Roles;

trait HasPermissionsTrait
{
    public function hasPermissionTo($permission) {

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);

    }

    public function hasPermissionThroughRole($permission) {

        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;

    }

    public function hasRole( ... $roles) {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;

    }

    protected function hasPermission($permission) {

        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    protected function getAllPermissions(array $permissions) {

        return Permission::whereIn('slug',$permissions)->get();

    }
}
