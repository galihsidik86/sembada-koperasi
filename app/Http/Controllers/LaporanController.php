<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Cicilan;
use App\Models\SimpananTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) ($request->query('tahun') ?: now()->year);
        $periodeMulai  = Carbon::create($tahun, 1, 1)->startOfDay();
        $periodeAkhir  = Carbon::create($tahun, 12, 31)->endOfDay();

        $alokasiJasaSimpanan = 0.25;
        $alokasiJasaPinjaman = 0.25;

        // Pendapatan koperasi (bunga yang sudah masuk dalam periode)
        $totalBungaTerbayar = (float) Cicilan::query()
            ->whereBetween('tanggal_bayar', [$periodeMulai, $periodeAkhir])
            ->whereIn('status', ['lunas', 'telat'])
            ->sum('bunga_due');

        // Total saldo simpanan akhir periode (per anggota)
        $saldoPerAnggota = SimpananTransaksi::query()
            ->where('tanggal', '<=', $periodeAkhir)
            ->selectRaw('anggota_id, SUM(CASE WHEN tipe = "setor" THEN nominal ELSE -nominal END) AS saldo')
            ->groupBy('anggota_id')
            ->pluck('saldo', 'anggota_id');

        $totalSaldo = (float) $saldoPerAnggota->sum();

        // Bunga terbayar per anggota (pinjaman dia, bunga yang masuk koperasi)
        $bungaPerAnggota = Cicilan::query()
            ->join('pinjaman', 'pinjaman.id', '=', 'cicilan.pinjaman_id')
            ->whereBetween('cicilan.tanggal_bayar', [$periodeMulai, $periodeAkhir])
            ->whereIn('cicilan.status', ['lunas', 'telat'])
            ->selectRaw('pinjaman.anggota_id, SUM(cicilan.bunga_due) AS bunga')
            ->groupBy('pinjaman.anggota_id')
            ->pluck('bunga', 'anggota_id');

        $totalBunga = (float) $bungaPerAnggota->sum();

        $alokSimpanan = $totalBungaTerbayar * $alokasiJasaSimpanan;
        $alokPinjaman = $totalBungaTerbayar * $alokasiJasaPinjaman;

        $rincian = Anggota::query()
            ->where('status', '!=', 'keluar')
            ->orderBy('nomor_anggota')
            ->get(['id', 'nomor_anggota', 'nama'])
            ->map(function ($a) use ($saldoPerAnggota, $bungaPerAnggota, $totalSaldo, $totalBunga, $alokSimpanan, $alokPinjaman) {
                $saldo = (float) ($saldoPerAnggota[$a->id] ?? 0);
                $bunga = (float) ($bungaPerAnggota[$a->id] ?? 0);

                $jasaSimpanan = $totalSaldo > 0 ? $alokSimpanan * ($saldo / $totalSaldo) : 0;
                $jasaPinjaman = $totalBunga > 0 ? $alokPinjaman * ($bunga / $totalBunga) : 0;

                return [
                    'id'            => $a->id,
                    'nomor_anggota' => $a->nomor_anggota,
                    'nama'          => $a->nama,
                    'saldo'         => $saldo,
                    'bunga_dibayar' => $bunga,
                    'jasa_simpanan' => round($jasaSimpanan, 2),
                    'jasa_pinjaman' => round($jasaPinjaman, 2),
                    'total_shu'     => round($jasaSimpanan + $jasaPinjaman, 2),
                ];
            });

        return Inertia::render('Laporan/Index', [
            'tahun'    => $tahun,
            'periode'  => [
                'mulai' => $periodeMulai->toDateString(),
                'akhir' => $periodeAkhir->toDateString(),
            ],
            'ringkasan' => [
                'total_bunga_terbayar' => $totalBungaTerbayar,
                'total_saldo'          => $totalSaldo,
                'alokasi_jasa_simpanan' => $alokSimpanan,
                'alokasi_jasa_pinjaman' => $alokPinjaman,
                'persen_simpanan'      => $alokasiJasaSimpanan * 100,
                'persen_pinjaman'      => $alokasiJasaPinjaman * 100,
            ],
            'rincian'  => $rincian,
        ]);
    }
}
