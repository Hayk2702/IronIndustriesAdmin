<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceImages extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'image_path', 'sort'];

    protected $appends = ['image_url'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
