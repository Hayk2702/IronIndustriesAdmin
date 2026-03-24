<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableMaterialPricesAddEntityPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_prices', function (Blueprint $table) {
            $table->decimal('entity_price', 12, 2)->nullable()->after("bend_price");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_prices', function (Blueprint $table) {
            $table->dropColumn('entity_price');
        });
    }
}
