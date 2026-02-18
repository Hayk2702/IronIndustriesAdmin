<?php

namespace App\Models\Sanctum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use HasFactory;
    protected $fillable=["name", 'token',];
}
