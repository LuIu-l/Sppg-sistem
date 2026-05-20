<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima_manfaat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penerima', 20)->unique();
            $table->string('nama');
            $table->char('nik', 16)->unique()->nullable();
            $table->char('nisn', 10)->unique()->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->foreignId('institusi_id')->nullable()->constrained('institusi')->onDelete('set null');
            $table->string('pin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima_manfaat');
    }
};
