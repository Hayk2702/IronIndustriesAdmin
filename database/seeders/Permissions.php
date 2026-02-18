<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'Create Users',
                'slug' => 'createusers'
            ],
            [
                'name' => 'Show Users',
                'slug' => 'showusers'
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'editusers'
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'deleteusers'
            ],


            [
                'name' => 'Show Roles',
                'slug' => 'showroles'
            ],
            [
                'name' => 'Create Roles',
                'slug' => 'createroles'
            ],
            [
                'name' => 'Edit Roles',
                'slug' => 'editroles'
            ],
            [
                'name' => 'Delete Roles',
                'slug' => 'deleteroles'
            ],


            [
                'name' => 'Show Permissions',
                'slug' => 'showpermissions'
            ],
            [
                'name' => 'Create Permissions',
                'slug' => 'createpermissions'
            ],
            [
                'name' => 'Edit Permissions',
                'slug' => 'editpermissions'
            ],
            [
                'name' => 'Delete Permissions',
                'slug' => 'deletepermissions'
            ],


            [
                'name' => 'Show about company',
                'slug' => 'showaboutcompany'
            ],
            [
                'name' => 'Edit about company',
                'slug' => 'editaboutcompany'
            ],

            [
                'name' => 'Show about us',
                'slug' => 'showaboutus'
            ],
            [
                'name' => 'Edit about us',
                'slug' => 'editaboutus'
            ],



            [
                'name' => 'Show Permissions',
                'slug' => 'showservices'
            ],
            [
                'name' => 'Create Permissions',
                'slug' => 'createservices'
            ],
            [
                'name' => 'Edit Permissions',
                'slug' => 'editservices'
            ],
            [
                'name' => 'Delete Permissions',
                'slug' => 'deleteservices'
            ],


            [
                'name' => 'Show Products',
                'slug' => 'showproducts'
            ],
            [
                'name' => 'Create Permissions',
                'slug' => 'createproducts'
            ],
            [
                'name' => 'Edit Permissions',
                'slug' => 'editproducts'
            ],
            [
                'name' => 'Delete Permissions',
                'slug' => 'deleteproducts'
            ],

        ];
        DB::table('permissions')->truncate();

        foreach ($permissions as $key => $value) {
            $permission = new Permission();
            $permission->name = $value['name'];
            $permission->slug = $value['slug'];
            $permission->save();
        }
    }
}
