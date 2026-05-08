<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pinjaman', 30)->unique();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->decimal('pokok', 15, 2);
            $table->decimal('bunga_persen', 5, 2)->default(0);
            $table->unsignedSmallInteger('tenor_bulan');
            $table->enum('metode_bunga', ['flat', 'menurun'])->default('flat');
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui')->nullable();
            $table->date('tanggal_cair')->nullable();
            $table->date('tanggal_lunas')->nullable();
            $table->enum('status', ['pengajuan', 'disetujui', 'ditolak', 'cair', 'lunas'])->default('pengajuan');
            $table->text('keterangan')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['anggota_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
