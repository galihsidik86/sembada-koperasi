<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'nomor_pinjaman',
        'anggota_id',
        'pokok',
        'bunga_persen',
        'tenor_bulan',
        'metode_bunga',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'tanggal_cair',
        'tanggal_lunas',
        'status',
        'keterangan',
        'catatan_penolakan',
        'diajukan_oleh',
        'disetujui_oleh',
    ];

    protected function casts(): array
    {
        return [
            'pokok'             => 'decimal:2',
            'bunga_persen'      => 'decimal:2',
            'tanggal_pengajuan' => 'date',
            'tanggal_disetujui' => 'date',
            'tanggal_cair'      => 'date',
            'tanggal_lunas'     => 'date',
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function cicilan(): HasMany
    {
        return $this->hasMany(Cicilan::class)->orderBy('no_urut');
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public static function nextNomor(): string
    {
        $year = now()->format('Y');
        $last = static::where('nomor_pinjaman', 'like', "PJ-{$year}-%")
            ->orderByDesc('id')
            ->value('nomor_pinjaman');

        $next = $last ? (int) substr($last, -4) + 1 : 1;

        return "PJ-{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function generateJadwal(): void
    {
        $this->cicilan()->delete();

        $startDate = $this->tanggal_cair ?? $this->tanggal_disetujui ?? now()->toDateString();
        $jatuh = Carbon::parse($startDate);

        $pokokPerBulan = round($this->pokok / $this->tenor_bulan, 2);
        $sisaPokok = (float) $this->pokok;

        for ($i = 1; $i <= $this->tenor_bulan; $i++) {
            $jatuh = $jatuh->copy()->addMonth();

            $pokokThis = ($i === $this->tenor_bulan) ? $sisaPokok : $pokokPerBulan;

            if ($this->metode_bunga === 'flat') {
                $bunga = round(((float) $this->pokok * (float) $this->bunga_persen / 100) / 12, 2);
            } else {
                $bunga = round($sisaPokok * ((float) $this->bunga_persen / 100) / 12, 2);
            }

            $this->cicilan()->create([
                'no_urut'     => $i,
                'jatuh_tempo' => $jatuh->toDateString(),
                'pokok_due'   => $pokokThis,
                'bunga_due'   => $bunga,
                'total_due'   => round($pokokThis + $bunga, 2),
                'status'      => 'belum_bayar',
            ]);

            $sisaPokok -= $pokokThis;
        }
    }

    public function totalTerbayar(): float
    {
        return (float) $this->cicilan()->sum('dibayar');
    }

    public function sisaTotalTagihan(): float
    {
        $total = (float) $this->cicilan()->sum('total_due');
        return $total - $this->totalTerbayar();
    }

    public function checkLunas(): void
    {
        $hasOpen = $this->cicilan()->where('status', '!=', 'lunas')->exists();

        if (! $hasOpen && $this->status !== 'lunas') {
            $this->update([
                'status'        => 'lunas',
                'tanggal_lunas' => now()->toDateString(),
            ]);
        }
    }
}
