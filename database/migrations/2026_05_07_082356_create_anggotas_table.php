<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_anggota', 20)->unique();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('panggilan')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat');
            $table->string('no_telp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->date('tanggal_masuk');
            $table->enum('status', ['aktif', 'non-aktif', 'keluar'])->default('aktif');
            $table->timestamps();

            $table->index('status');
            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
