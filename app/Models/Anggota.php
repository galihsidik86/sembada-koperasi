<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nomor_anggota',
        'nik',
        'nama',
        'panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'email',
        'pekerjaan',
        'tanggal_masuk',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
        ];
    }

    public static function nextNomor(): string
    {
        $last = static::orderByDesc('id')->value('nomor_anggota');
        $next = $last ? (int) substr($last, -4) + 1 : 1;

        return 'AGT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function simpananTransaksi()
    {
        return $this->hasMany(SimpananTransaksi::class);
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function saldoSimpanan(?int $jenisId = null): float
    {
        $q = $this->simpananTransaksi();
        if ($jenisId) {
            $q->where('jenis_simpanan_id', $jenisId);
        }

        $setor = (clone $q)->where('tipe', 'setor')->sum('nominal');
        $tarik = (clone $q)->where('tipe', 'tarik')->sum('nominal');

        return (float) ($setor - $tarik);
    }
}
