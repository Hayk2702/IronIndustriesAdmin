<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPriceThickness extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_price_id',
        'thickness_mm',
    ];

    public function material()
    {
        return $this->belongsTo(MaterialPrice::class, 'material_price_id');
    }
}
