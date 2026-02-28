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
                'name' => 'Show Services',
                'slug' => 'showservices'
            ],
            [
                'name' => 'Create Services',
                'slug' => 'createservices'
            ],
            [
                'name' => 'Edit Services',
                'slug' => 'editservices'
            ],
            [
                'name' => 'Delete Services',
                'slug' => 'deleteservices'
            ],


            [
                'name' => 'Show Categories',
                'slug' => 'showcategories'
            ],
            [
                'name' => 'Create Categories',
                'slug' => 'createcategories'
            ],
            [
                'name' => 'Edit Categories',
                'slug' => 'editcategories'
            ],
            [
                'name' => 'Delete Categories',
                'slug' => 'deletecategories'
            ],


            [
                'name' => 'Show Products',
                'slug' => 'showproducts'
            ],
            [
                'name' => 'Create Products',
                'slug' => 'createproducts'
            ],
            [
                'name' => 'Edit Products',
                'slug' => 'editproducts'
            ],
            [
                'name' => 'Delete Products',
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
