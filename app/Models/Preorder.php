<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'calculating_information',
        'comment',
        'file_path',
        'is_viewed',
    ];

    protected $casts = [
        'is_viewed' => 'boolean',
    ];
}
