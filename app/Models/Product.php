<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }
}
