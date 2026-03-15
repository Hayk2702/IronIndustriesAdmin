<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_prices', function (Blueprint $table) {
            $table->id();
            $table->string('material_name', 150);
            $table->decimal('cut_cost', 12, 2)->nullable();
            $table->decimal('material_cost_per_kg', 12, 2)->nullable();
            $table->decimal('density_kg_m2', 12, 4)->nullable();
            $table->decimal('bend_price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_prices');
    }
}
