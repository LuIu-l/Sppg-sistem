<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_makanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan')->unique();
            $table->string('satuan', 50);
            $table->decimal('kalori_per_satuan', 8, 2)->default(0);
            $table->decimal('protein_per_satuan', 8, 2)->default(0);
            $table->decimal('karbohidrat_per_satuan', 8, 2)->default(0);
            $table->decimal('lemak_per_satuan', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_makanan');
    }
};
