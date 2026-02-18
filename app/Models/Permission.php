<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\\Models\\User','users_roles','permission_id','user_id','id','id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\\Models\\Roles','roles_permission','permission_id','role_id','id','id');
    }
}
