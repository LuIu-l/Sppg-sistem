<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_gizi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->date('tanggal_berlaku');
            $table->decimal('total_kalori', 8, 2)->default(0);
            $table->decimal('total_protein', 8, 2)->default(0);
            $table->decimal('total_karbohidrat', 8, 2)->default(0);
            $table->decimal('total_lemak', 8, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_penolakan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('pengguna')->onDelete('restrict');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_gizi');
    }
};
