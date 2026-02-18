<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->nullable()->index();
            $table->string('title', 150);
            $table->longText('description')->nullable();

            $table->string('price', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('material', 100)->nullable();
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
        Schema::dropIfExists('products');
    }
}
