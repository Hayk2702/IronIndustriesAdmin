<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_name',
        'cut_cost',
        'material_cost_per_kg',
        'density_kg_m2',
        'bend_price',
    ];

    public function thicknesses()
    {
        return $this->hasMany(MaterialPriceThickness::class);
    }
}
