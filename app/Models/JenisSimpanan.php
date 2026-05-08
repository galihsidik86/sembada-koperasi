<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSimpanan extends Model
{
    use HasFactory;

    protected $table = 'jenis_simpanan';

    protected $fillable = [
        'kode',
        'nama',
        'nominal_default',
        'dapat_ditarik',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'nominal_default' => 'decimal:2',
            'dapat_ditarik'   => 'boolean',
        ];
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(SimpananTransaksi::class, 'jenis_simpanan_id');
    }
}
