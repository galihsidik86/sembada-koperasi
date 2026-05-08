<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Cicilan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = trim((string) $request->query('q', ''));

        $pinjaman = Pinjaman::query()
            ->with('anggota:id,nomor_anggota,nama')
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('nomor_pinjaman', 'like', "%{$q}%")
                  ->orWhereHas('anggota', fn ($a) => $a->where('nama', 'like', "%{$q}%")
                                                      ->orWhere('nomor_anggota', 'like', "%{$q}%"));
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $counts = Pinjaman::query()
            ->selectRaw('status, COUNT(*) AS c')
            ->groupBy('status')
            ->pluck('c', 'status');

        return Inertia::render('Pinjaman/Index', [
            'pinjaman' => $pinjaman,
            'filters'  => ['q' => $q, 'status' => $status],
            'counts'   => $counts,
        ]);
    }

    public function create(Request $request)
    {
        $anggotaId = $request->query('anggota_id');
        $anggotaTerpilih = $anggotaId ? Anggota::find($anggotaId) : null;

        $anggotaList = Anggota::where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id', 'nomor_anggota', 'nama']);

        return Inertia::render('Pinjaman/Create', [
            'nomor_pinjaman'    => Pinjaman::nextNomor(),
            'anggota_list'      => $anggotaList,
            'anggota_terpilih'  => $anggotaTerpilih,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_pinjaman'    => ['required', 'string', 'max:30', 'unique:pinjaman,nomor_pinjaman'],
            'anggota_id'        => ['required', 'exists:anggota,id'],
            'pokok'             => ['required', 'numeric', 'min:1'],
            'bunga_persen'      => ['required', 'numeric', 'min:0', 'max:100'],
            'tenor_bulan'       => ['required', 'integer', 'min:1', 'max:120'],
            'metode_bunga'      => ['required', 'in:flat,menurun'],
            'tanggal_pengajuan' => ['required', 'date'],
            'keterangan'        => ['nullable', 'string'],
        ]);

        $data['status'] = 'pengajuan';
        $data['diajukan_oleh'] = $request->user()?->id;

        $pinjaman = Pinjaman::create($data);

        return redirect()->route('pinjaman.show', $pinjaman)
            ->with('success', "Pengajuan pinjaman {$pinjaman->nomor_pinjaman} berhasil dicatat. Menunggu persetujuan.");
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load(['anggota:id,nomor_anggota,nama,no_telp', 'cicilan', 'pengaju:id,name', 'penyetuju:id,name']);

        return Inertia::render('Pinjaman/Show', [
            'pinjaman'        => $pinjaman,
            'total_terbayar'  => $pinjaman->totalTerbayar(),
            'sisa_tagihan'    => $pinjaman->sisaTotalTagihan(),
        ]);
    }

    public function approve(Request $request, Pinjaman $pinjaman)
    {
        if ($pinjaman->status !== 'pengajuan') {
            return back()->with('error', 'Pinjaman ini sudah diproses sebelumnya.');
        }

        $data = $request->validate([
            'tanggal_disetujui' => ['required', 'date'],
            'tanggal_cair'      => ['required', 'date', 'after_or_equal:tanggal_disetujui'],
        ]);

        DB::transaction(function () use ($pinjaman, $data, $request) {
            $pinjaman->update([
                'status'            => 'cair',
                'tanggal_disetujui' => $data['tanggal_disetujui'],
                'tanggal_cair'      => $data['tanggal_cair'],
                'disetujui_oleh'    => $request->user()?->id,
            ]);

            $pinjaman->generateJadwal();
        });

        return redirect()->route('pinjaman.show', $pinjaman)
            ->with('success', "Pinjaman {$pinjaman->nomor_pinjaman} disetujui dan dicairkan. Jadwal angsuran sudah dibuat.");
    }

    public function reject(Request $request, Pinjaman $pinjaman)
    {
        if ($pinjaman->status !== 'pengajuan') {
            return back()->with('error', 'Pinjaman ini sudah diproses sebelumnya.');
        }

        $data = $request->validate([
            'catatan_penolakan' => ['required', 'string', 'max:500'],
        ]);

        $pinjaman->update([
            'status'            => 'ditolak',
            'catatan_penolakan' => $data['catatan_penolakan'],
            'disetujui_oleh'    => $request->user()?->id,
        ]);

        return redirect()->route('pinjaman.show', $pinjaman)
            ->with('success', 'Pengajuan ditolak.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        if (! in_array($pinjaman->status, ['pengajuan', 'ditolak'], true)) {
            return back()->with('error', 'Pinjaman yang sudah cair tidak bisa dihapus.');
        }

        $pinjaman->delete();

        return redirect()->route('pinjaman.index')
            ->with('success', 'Pengajuan pinjaman dihapus.');
    }

    public function bayarCicilan(Request $request, Cicilan $cicilan)
    {
        $data = $request->validate([
            'dibayar'       => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date'],
        ]);

        if ($data['dibayar'] < $cicilan->total_due) {
            return back()->withErrors([
                'dibayar' => 'Pembayaran kurang dari total yang harus dibayar (Rp ' . number_format($cicilan->total_due, 0, ',', '.') . '). Untuk MVP, bayar penuh dulu.',
            ])->withInput();
        }

        DB::transaction(function () use ($cicilan, $data, $request) {
            $isLate = $data['tanggal_bayar'] > $cicilan->jatuh_tempo;

            $cicilan->update([
                'dibayar'       => $data['dibayar'],
                'tanggal_bayar' => $data['tanggal_bayar'],
                'status'        => $isLate ? 'telat' : 'lunas',
                'dicatat_oleh'  => $request->user()?->id,
            ]);

            $cicilan->pinjaman->checkLunas();
        });

        return back()->with('success', "Cicilan #{$cicilan->no_urut} berhasil dicatat sebagai dibayar.");
    }

    public function batalBayar(Cicilan $cicilan)
    {
        $cicilan->update([
            'dibayar'       => 0,
            'tanggal_bayar' => null,
            'status'        => 'belum_bayar',
            'dicatat_oleh'  => null,
        ]);

        $cicilan->pinjaman->update(['status' => 'cair', 'tanggal_lunas' => null]);

        return back()->with('success', "Pembayaran cicilan #{$cicilan->no_urut} dibatalkan.");
    }
}
