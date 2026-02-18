<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\HasPermissionsTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPermissionsTrait, LaravelPermissionToVueJS,SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() {

        return $this->belongsToMany(Roles::class,'users_roles','user_id','role_id');

    }

    public function permissions() {

        return $this->belongsToMany(Permission::class,'users_permissions','user_id','permission_id');
    }

    public function getPermissions(){
        $permission = $this->permissions()->get();
        $permissions = array_merge([], (($permission) ? $permission->toArray() : []));
        $rolesPerm = $this->roles()->with('permission')->get();
        foreach ($rolesPerm as $role){
            if($role->permission){
                $permissions = array_merge($permissions, $role->permission->toArray());
            }
        }
        $data = [];
        if($permissions AND count($permissions)){
            foreach ($permissions as $row){
                if(!in_array($row['slug'],$data))
                    array_push($data, $row['slug']);
            }
        }
        return $data;
    }

    public function isSuperAdmin()
    {

        foreach ($this->roles()->get() as $role) {

            if ($role->slug == 'superadmin') {
                return true;
            }
        }
        return false;
    }

    public function isAdmin()
    {

        foreach ($this->roles()->get() as $role) {

            if ($role->slug == 'administrator') {
                return true;
            }
        }
        return false;
    }
}
