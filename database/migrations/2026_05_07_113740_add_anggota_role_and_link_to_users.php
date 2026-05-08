<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','pengurus','pengawas','teller','anggota') NOT NULL DEFAULT 'pengurus'");

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('anggota_id')
                ->nullable()
                ->after('role')
                ->constrained('anggota')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['anggota_id']);
            $table->dropColumn('anggota_id');
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','pengurus','pengawas','teller') NOT NULL DEFAULT 'pengurus'");
    }
};
