<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialPriceThicknessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_price_thicknesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_price_id')->constrained('material_prices')->cascadeOnDelete();
            $table->decimal('thickness_mm', 8, 3);
            $table->timestamps();

            $table->unique(['material_price_id', 'thickness_mm'], 'mp_thickness_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_price_thicknesses');
    }
}
