<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('position')->nullable()->after('id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('position')->nullable()->after('id');
        });

        Schema::table('material_prices', function (Blueprint $table) {
            $table->unsignedInteger('position')->nullable()->after('id');
        });

        $this->fillPositions('services');
        $this->fillPositions('products');
        $this->fillPositions('material_prices');
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('material_prices', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }

    private function fillPositions(string $table): void
    {
        $rows = DB::table($table)->orderBy('id')->get(['id']);

        foreach ($rows as $index => $row) {
            DB::table($table)
                ->where('id', $row->id)
                ->update(['position' => $index + 1]);
        }
    }
};
