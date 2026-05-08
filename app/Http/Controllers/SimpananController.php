<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\SimpananTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SimpananController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $jenis = JenisSimpanan::orderBy('id')->get();

        $anggota = Anggota::query()
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nomor_anggota', 'like', "%{$q}%");
            }))
            ->where('status', 'aktif')
            ->orderBy('nomor_anggota')
            ->paginate(20)
            ->withQueryString();

        $ids = $anggota->getCollection()->pluck('id');

        $saldoPerJenis = SimpananTransaksi::query()
            ->whereIn('anggota_id', $ids)
            ->selectRaw('anggota_id, jenis_simpanan_id,
                SUM(CASE WHEN tipe = "setor" THEN nominal ELSE 0 END) -
                SUM(CASE WHEN tipe = "tarik" THEN nominal ELSE 0 END) AS saldo')
            ->groupBy('anggota_id', 'jenis_simpanan_id')
            ->get()
            ->groupBy('anggota_id');

        $anggota->getCollection()->transform(function ($a) use ($saldoPerJenis, $jenis) {
            $bucket = collect($saldoPerJenis[$a->id] ?? [])->keyBy('jenis_simpanan_id');
            $a->saldo = $jenis->mapWithKeys(fn ($j) => [
                $j->kode => (float) ($bucket[$j->id]->saldo ?? 0),
            ])->all();
            $a->saldo_total = array_sum($a->saldo);
            return $a;
        });

        return Inertia::render('Simpanan/Index', [
            'anggota' => $anggota,
            'jenis'   => $jenis,
            'filters' => ['q' => $q],
        ]);
    }

    public function show(Anggota $anggota)
    {
        $jenis = JenisSimpanan::orderBy('id')->get();

        $saldoPerJenis = $jenis->mapWithKeys(fn ($j) => [
            $j->kode => $anggota->saldoSimpanan($j->id),
        ])->all();

        $transaksi = $anggota->simpananTransaksi()
            ->with('jenis:id,kode,nama')
            ->with('pencatat:id,name')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(20);

        return Inertia::render('Simpanan/Show', [
            'anggota'      => $anggota,
            'jenis'        => $jenis,
            'saldo'        => $saldoPerJenis,
            'saldo_total'  => array_sum($saldoPerJenis),
            'transaksi'    => $transaksi,
        ]);
    }

    public function storeTransaksi(Request $request, Anggota $anggota)
    {
        $jenis = JenisSimpanan::findOrFail($request->input('jenis_simpanan_id'));

        $data = $request->validate([
            'jenis_simpanan_id' => ['required', 'exists:jenis_simpanan,id'],
            'tipe'              => ['required', 'in:setor,tarik'],
            'nominal'           => ['required', 'numeric', 'min:1'],
            'tanggal'           => ['required', 'date'],
            'keterangan'        => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['tipe'] === 'tarik') {
            if (! $jenis->dapat_ditarik) {
                return back()->withErrors([
                    'tipe' => "Simpanan {$jenis->nama} tidak bisa ditarik.",
                ])->withInput();
            }

            $saldo = $anggota->saldoSimpanan($jenis->id);
            if ($data['nominal'] > $saldo) {
                return back()->withErrors([
                    'nominal' => 'Saldo tidak cukup. Saldo saat ini Rp ' . number_format($saldo, 0, ',', '.') . '.',
                ])->withInput();
            }
        }

        DB::transaction(function () use ($anggota, $data, $request) {
            $anggota->simpananTransaksi()->create([
                'jenis_simpanan_id' => $data['jenis_simpanan_id'],
                'tipe'              => $data['tipe'],
                'nominal'           => $data['nominal'],
                'tanggal'           => $data['tanggal'],
                'keterangan'        => $data['keterangan'] ?? null,
                'dicatat_oleh'      => $request->user()?->id,
            ]);
        });

        $verb = $data['tipe'] === 'setor' ? 'Setoran' : 'Penarikan';

        return redirect()->route('simpanan.show', $anggota)
            ->with('success', "{$verb} Rp " . number_format($data['nominal'], 0, ',', '.') . " untuk {$jenis->nama} berhasil dicatat.");
    }

    public function destroyTransaksi(SimpananTransaksi $transaksi)
    {
        $anggota = $transaksi->anggota;
        $transaksi->delete();

        return redirect()->route('simpanan.show', $anggota)
            ->with('success', 'Transaksi simpanan berhasil dihapus.');
    }
}
