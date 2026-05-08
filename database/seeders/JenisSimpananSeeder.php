<?php

namespace Database\Seeders;

use App\Models\JenisSimpanan;
use Illuminate\Database\Seeder;

class JenisSimpananSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'kode' => 'pokok',
                'nama' => 'Simpanan Pokok',
                'nominal_default' => 100000,
                'dapat_ditarik' => false,
                'deskripsi' => 'Dibayar sekali saat masuk jadi anggota. Tidak bisa ditarik selama jadi anggota.',
            ],
            [
                'kode' => 'wajib',
                'nama' => 'Simpanan Wajib',
                'nominal_default' => 25000,
                'dapat_ditarik' => false,
                'deskripsi' => 'Dibayar rutin tiap bulan oleh setiap anggota.',
            ],
            [
                'kode' => 'sukarela',
                'nama' => 'Simpanan Sukarela',
                'nominal_default' => 0,
                'dapat_ditarik' => true,
                'deskripsi' => 'Tabungan sukarela. Bebas setor dan tarik kapan saja.',
            ],
        ];

        foreach ($rows as $row) {
            JenisSimpanan::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
