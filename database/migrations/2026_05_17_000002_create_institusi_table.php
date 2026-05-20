<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institusi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_institusi');
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institusi');
    }
};
