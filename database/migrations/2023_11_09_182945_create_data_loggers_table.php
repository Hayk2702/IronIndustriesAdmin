<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_loggers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('time')->nullable();
            $table->double('duration')->nullable();
            $table->string('ip_address')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('headers')->nullable();
            $table->text('url')->nullable();
            $table->string('type')->nullable();
            $table->string('method')->nullable();
            $table->text('input')->nullable();
            $table->text('output')->nullable();

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
        Schema::dropIfExists('data_loggers');
    }
}
