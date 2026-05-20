<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bahan_makanan', function (Blueprint $table) {
            $table->decimal('stok_minimum', 10, 2)->default(0)->after('satuan');
        });

        Schema::table('stok_bahan', function (Blueprint $table) {
            if (Schema::hasColumn('stok_bahan', 'stok_minimum')) {
                $table->dropColumn('stok_minimum');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_bahan', function (Blueprint $table) {
            $table->decimal('stok_minimum', 10, 2)->default(0);
        });

        Schema::table('bahan_makanan', function (Blueprint $table) {
            if (Schema::hasColumn('bahan_makanan', 'stok_minimum')) {
                $table->dropColumn('stok_minimum');
            }
        });
    }
};
