<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'SuperAdmin',
                'slug' => 'superadmin'
            ],
        ];
        DB::table('roles')->truncate();
        DB::table('roles_permission')->truncate();
        $permissions = Permission::get();
        foreach ($roles as $key => $value) {
            $role = new Roles();
            $role->id = $value['id'];
            $role->name = $value['name'];
            $role->slug = $value['slug'];
            $role->save();
            foreach ($permissions as $perm) {
                if ($role->slug != 'superadmin' and !in_array($perm->slug, $value['permission']))
                    continue;
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $role->id;
                $rolePermission->permission_id = $perm->id;
                $rolePermission->save();
            }

        }
    }
}
