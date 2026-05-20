<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_makanan_id')->constrained('bahan_makanan')->onDelete('restrict');
            $table->foreignId('menu_gizi_id')->nullable()->constrained('menu_gizi')->onDelete('set null');
            $table->decimal('stok_aktual', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->decimal('kebutuhan_per_porsi', 10, 2)->default(0);
            $table->timestamp('terakhir_diubah')->nullable();
            $table->timestamps();

            $table->index('bahan_makanan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bahan');
    }
};
