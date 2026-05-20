<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_manfaat_id')->constrained('penerima_manfaat')->onDelete('restrict');
            $table->foreignId('jadwal_distribusi_id')->constrained('jadwal_distribusi')->onDelete('restrict');
            $table->foreignId('petugas_id')->constrained('pengguna')->onDelete('restrict');
            $table->enum('status', ['terdistribusi', 'dibatalkan'])->default('terdistribusi');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu_distribusi');
            $table->timestamps();

            $table->index(['penerima_manfaat_id', 'waktu_distribusi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi');
    }
};
