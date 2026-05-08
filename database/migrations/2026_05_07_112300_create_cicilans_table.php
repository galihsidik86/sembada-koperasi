<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $table->unsignedSmallInteger('no_urut');
            $table->date('jatuh_tempo');
            $table->decimal('pokok_due', 15, 2);
            $table->decimal('bunga_due', 15, 2);
            $table->decimal('total_due', 15, 2);
            $table->decimal('dibayar', 15, 2)->default(0);
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum_bayar', 'lunas', 'telat'])->default('belum_bayar');
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['pinjaman_id', 'no_urut']);
            $table->index('jatuh_tempo');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicilan');
    }
};
