<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $table = 'roles';
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\\Models\\User','users_roles','role_id','user_id','id','id');
    }

    public function permission()
    {
        return $this->belongsToMany('App\\Models\\Permission','roles_permission','role_id','permission_id','id','id');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\\Models\\Permission','roles_permission','role_id','permission_id','id','id');
    }

    public function getPermissions(){
        $permissions = $this->permissions()->get();
        $data = [];
        if($permissions AND count($permissions)){
            foreach ($permissions as $row){
                if(!in_array($row['slug'],$data))
                    array_push($data, $row['slug']);
            }
        }
        return $data;
    }
}
