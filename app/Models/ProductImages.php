<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImages extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_path'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
