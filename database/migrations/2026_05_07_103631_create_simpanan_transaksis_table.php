<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('jenis_simpanan_id')->constrained('jenis_simpanan')->restrictOnDelete();
            $table->enum('tipe', ['setor', 'tarik']);
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['anggota_id', 'jenis_simpanan_id']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan_transaksi');
    }
};
