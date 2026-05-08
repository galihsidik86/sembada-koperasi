<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cicilan extends Model
{
    use HasFactory;

    protected $table = 'cicilan';

    protected $fillable = [
        'pinjaman_id',
        'no_urut',
        'jatuh_tempo',
        'pokok_due',
        'bunga_due',
        'total_due',
        'dibayar',
        'tanggal_bayar',
        'status',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'pokok_due'     => 'decimal:2',
            'bunga_due'     => 'decimal:2',
            'total_due'     => 'decimal:2',
            'dibayar'       => 'decimal:2',
            'jatuh_tempo'   => 'date',
            'tanggal_bayar' => 'date',
        ];
    }

    public function pinjaman(): BelongsTo
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
