<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\JenisSimpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $anggota = Anggota::query()
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('nomor_anggota', 'like', "%{$q}%")
                  ->orWhere('nik', 'like', "%{$q}%");
            }))
            ->when($status, fn ($qq) => $qq->where('status', $status))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Anggota/Index', [
            'anggota' => $anggota,
            'filters' => ['q' => $q, 'status' => $status],
        ]);
    }

    public function create()
    {
        return Inertia::render('Anggota/Create', [
            'nomor_anggota' => Anggota::nextNomor(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $anggota = DB::transaction(function () use ($data, $request) {
            $anggota = Anggota::create($data);

            $pokok = JenisSimpanan::where('kode', 'pokok')->first();
            if ($pokok && $pokok->nominal_default > 0) {
                $anggota->simpananTransaksi()->create([
                    'jenis_simpanan_id' => $pokok->id,
                    'tipe'              => 'setor',
                    'nominal'           => $pokok->nominal_default,
                    'tanggal'           => $anggota->tanggal_masuk,
                    'keterangan'        => 'Simpanan pokok awal — auto-dicatat saat pendaftaran.',
                    'dicatat_oleh'      => $request->user()?->id,
                ]);
            }

            return $anggota;
        });

        return redirect()->route('anggota.show', $anggota)
            ->with('success', "Anggota {$anggota->nama} berhasil ditambahkan.");
    }

    public function show(Anggota $anggota)
    {
        $anggota->load('user:id,email,role,anggota_id');

        return Inertia::render('Anggota/Show', [
            'anggota' => $anggota,
        ]);
    }

    public function createUser(Anggota $anggota)
    {
        if ($anggota->user) {
            return back()->with('error', 'Anggota ini sudah punya akun login.');
        }

        $email = $anggota->email
            ?: strtolower($anggota->nomor_anggota) . '@anggota.sembada.test';

        if (User::where('email', $email)->exists()) {
            return back()->with('error', "Email {$email} sudah dipakai akun lain. Update email anggota dulu.");
        }

        $password = Str::random(8);

        User::create([
            'name'       => $anggota->nama,
            'email'      => $email,
            'password'   => Hash::make($password),
            'role'       => 'anggota',
            'anggota_id' => $anggota->id,
        ]);

        return back()->with('success', "Akun dibuat untuk {$anggota->nama}. Email: {$email} · Kata sandi sementara: {$password} — catat dan kasih tahu anggota, ya. Akan hilang setelah halaman di-refresh.");
    }

    public function destroyUser(Anggota $anggota)
    {
        if (! $anggota->user) {
            return back()->with('error', 'Anggota ini tidak punya akun.');
        }

        $anggota->user->delete();

        return back()->with('success', 'Akun login anggota dihapus.');
    }

    public function edit(Anggota $anggota)
    {
        return Inertia::render('Anggota/Edit', [
            'anggota' => $anggota,
        ]);
    }

    public function update(Request $request, Anggota $anggota)
    {
        $data = $this->validateData($request, $anggota->id);

        $anggota->update($data);

        return redirect()->route('anggota.show', $anggota)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        $nama = $anggota->nama;
        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', "Anggota {$nama} sudah dihapus.");
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $unique = fn (string $col) => $ignoreId
            ? "unique:anggota,{$col},{$ignoreId}"
            : "unique:anggota,{$col}";

        return $request->validate([
            'nomor_anggota' => ['required', 'string', 'max:20', $unique('nomor_anggota')],
            'nik'           => ['required', 'string', 'size:16', $unique('nik')],
            'nama'          => ['required', 'string', 'max:255'],
            'panggilan'     => ['nullable', 'string', 'max:60'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir'  => ['nullable', 'string', 'max:120'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'alamat'        => ['required', 'string'],
            'no_telp'       => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:255'],
            'pekerjaan'     => ['nullable', 'string', 'max:120'],
            'tanggal_masuk' => ['required', 'date'],
            'status'        => ['required', 'in:aktif,non-aktif,keluar'],
        ], [
            'nomor_anggota.unique' => 'Nomor anggota ini sudah dipakai.',
            'nik.unique'           => 'NIK ini sudah terdaftar.',
            'nik.size'             => 'NIK harus 16 digit.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh hari ini atau masa depan.',
        ]);
    }
}
