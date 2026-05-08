<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class MemberController extends Controller
{
    private function anggota(Request $request): Anggota
    {
        $anggota = $request->user()?->anggota;

        if (! $anggota) {
            abort(403, 'Akun ini tidak terhubung ke data anggota. Hubungi pengurus.');
        }

        return $anggota;
    }

    public function beranda(Request $request)
    {
        $anggota = $this->anggota($request);

        $jenis = JenisSimpanan::orderBy('id')->get();
        $saldo = $jenis->mapWithKeys(fn ($j) => [
            $j->kode => $anggota->saldoSimpanan($j->id),
        ])->all();

        $pinjamanAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['cair', 'disetujui', 'pengajuan'])
            ->with('cicilan')
            ->orderByDesc('id')
            ->first();

        $cicilanBerikut = null;
        if ($pinjamanAktif) {
            $cicilanBerikut = $pinjamanAktif->cicilan()
                ->where('status', 'belum_bayar')
                ->orderBy('jatuh_tempo')
                ->first();
        }

        return Inertia::render('Member/Beranda', [
            'anggota'         => $anggota,
            'jenis'           => $jenis,
            'saldo'           => $saldo,
            'saldo_total'     => array_sum($saldo),
            'pinjaman_aktif'  => $pinjamanAktif,
            'cicilan_berikut' => $cicilanBerikut,
        ]);
    }

    public function simpanan(Request $request)
    {
        $anggota = $this->anggota($request);
        $jenis   = JenisSimpanan::orderBy('id')->get();

        $saldo = $jenis->mapWithKeys(fn ($j) => [
            $j->kode => $anggota->saldoSimpanan($j->id),
        ])->all();

        $transaksi = $anggota->simpananTransaksi()
            ->with('jenis:id,kode,nama')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(20);

        return Inertia::render('Member/Simpanan', [
            'anggota'     => $anggota,
            'jenis'       => $jenis,
            'saldo'       => $saldo,
            'saldo_total' => array_sum($saldo),
            'transaksi'   => $transaksi,
        ]);
    }

    public function pinjamanIndex(Request $request)
    {
        $anggota = $this->anggota($request);

        $pinjaman = Pinjaman::where('anggota_id', $anggota->id)
            ->orderByDesc('id')
            ->paginate(20);

        return Inertia::render('Member/Pinjaman/Index', [
            'anggota'  => $anggota,
            'pinjaman' => $pinjaman,
        ]);
    }

    public function pinjamanCreate(Request $request)
    {
        $anggota = $this->anggota($request);

        // Tidak boleh ajukan baru kalau masih ada yang belum lunas
        $adaPinjamanAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['pengajuan', 'disetujui', 'cair'])
            ->exists();

        return Inertia::render('Member/Pinjaman/Create', [
            'anggota'             => $anggota,
            'nomor_pinjaman'      => Pinjaman::nextNomor(),
            'ada_pinjaman_aktif'  => $adaPinjamanAktif,
        ]);
    }

    public function pinjamanStore(Request $request)
    {
        $anggota = $this->anggota($request);

        $adaPinjamanAktif = Pinjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['pengajuan', 'disetujui', 'cair'])
            ->exists();

        if ($adaPinjamanAktif) {
            return back()->with('error', 'Kamu masih punya pinjaman yang belum lunas. Tunggu sampai lunas, ya.');
        }

        $data = $request->validate([
            'pokok'       => ['required', 'numeric', 'min:100000'],
            'tenor_bulan' => ['required', 'integer', 'min:1', 'max:60'],
            'keterangan'  => ['nullable', 'string', 'max:500'],
        ]);

        $pinjaman = Pinjaman::create([
            'nomor_pinjaman'    => Pinjaman::nextNomor(),
            'anggota_id'        => $anggota->id,
            'pokok'             => $data['pokok'],
            'bunga_persen'      => 12, // default policy — admin can adjust later
            'tenor_bulan'       => $data['tenor_bulan'],
            'metode_bunga'      => 'flat',
            'tanggal_pengajuan' => now()->toDateString(),
            'status'            => 'pengajuan',
            'keterangan'        => $data['keterangan'] ?? null,
            'diajukan_oleh'     => $request->user()?->id,
        ]);

        return redirect()->route('me.pinjaman.show', $pinjaman)
            ->with('success', 'Pengajuan pinjaman kamu sudah masuk. Pengurus akan menghubungi dalam 1×24 jam.');
    }

    public function pinjamanShow(Request $request, Pinjaman $pinjaman)
    {
        $anggota = $this->anggota($request);

        if ($pinjaman->anggota_id !== $anggota->id) {
            abort(404);
        }

        $pinjaman->load('cicilan');

        return Inertia::render('Member/Pinjaman/Show', [
            'anggota'        => $anggota,
            'pinjaman'       => $pinjaman,
            'total_terbayar' => $pinjaman->totalTerbayar(),
            'sisa_tagihan'   => $pinjaman->sisaTotalTagihan(),
        ]);
    }

    public function profil(Request $request)
    {
        $anggota = $this->anggota($request);

        return Inertia::render('Member/Profil', [
            'anggota' => $anggota,
            'user'    => $request->user()->only(['id', 'name', 'email']),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
