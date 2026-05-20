<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('username', 100)->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->foreignId('peran_id')->constrained('peran')->onDelete('restrict');
            $table->foreignId('institusi_id')->nullable()->constrained('institusi')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
