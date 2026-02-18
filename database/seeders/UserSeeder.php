<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => 'asdasd',
                'role' => 'superadmin',
            ],
        ];

        DB::table('users')->truncate();
        DB::table('users_roles')->truncate();

        foreach ($data as $key => $value) {
            $row = new User();
            $row->id = $value['id'];
            $row->name = $value['name'];
            $row->email = $value['email'];
            $row->password = Hash::make($value['password']);
            $row->save();

            if($value['role']){
                $userRole = new UserRole();
                $userRole->user_id = $value['id'];
                $userRole->role_id = $this->getRole($value['role']);
                $userRole->save();
            }

        }

    }

    public function getRole($slug){

        $role = Roles::where('slug', $slug)->first();

        return $role->id;
    }


}
