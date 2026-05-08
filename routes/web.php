<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananController;
use App\Models\Anggota;
use App\Models\Cicilan;
use App\Models\Pinjaman;
use App\Models\SimpananTransaksi;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if ($u = auth()->user()) {
        return $u->isAnggota() ? redirect('/me') : redirect('/dashboard');
    }
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// ─── Admin / pengurus / pengawas / teller ────────────────────────────────
Route::middleware(['auth', 'role:admin,pengurus,pengawas,teller'])->group(function () {
    Route::get('/dashboard', function () {
        $totalSimpanan = SimpananTransaksi::query()
            ->selectRaw('SUM(CASE WHEN tipe = "setor" THEN nominal ELSE -nominal END) AS total')
            ->value('total');

        $pinjamanAktif = Pinjaman::where('status', 'cair')->sum('pokok');
        $pinjamanPending = Pinjaman::where('status', 'pengajuan')->count();

        $tahun = now()->year;
        $shuSementara = (float) Cicilan::query()
            ->whereYear('tanggal_bayar', $tahun)
            ->whereIn('status', ['lunas', 'telat'])
            ->sum('bunga_due');

        $pengajuanQueue = Pinjaman::with('anggota:id,nomor_anggota,nama')
            ->where('status', 'pengajuan')
            ->orderBy('tanggal_pengajuan')
            ->limit(5)
            ->get(['id', 'nomor_pinjaman', 'anggota_id', 'pokok', 'tenor_bulan', 'tanggal_pengajuan']);

        $cicilanJatuh = Cicilan::with('pinjaman.anggota:id,nomor_anggota,nama')
            ->where('status', 'belum_bayar')
            ->whereDate('jatuh_tempo', '<=', now()->addDays(7))
            ->orderBy('jatuh_tempo')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'anggota_aktif'    => Anggota::where('status', 'aktif')->count(),
                'total_simpanan'   => (float) ($totalSimpanan ?? 0),
                'pinjaman_aktif'   => (float) ($pinjamanAktif ?? 0),
                'pinjaman_pending' => $pinjamanPending,
                'shu_sementara'    => $shuSementara,
            ],
            'pengajuan_queue' => $pengajuanQueue,
            'cicilan_jatuh'   => $cicilanJatuh,
        ]);
    })->name('dashboard');

    Route::resource('anggota', AnggotaController::class);
    Route::post('/anggota/{anggota}/akun', [AnggotaController::class, 'createUser'])->name('anggota.akun.store');
    Route::delete('/anggota/{anggota}/akun', [AnggotaController::class, 'destroyUser'])->name('anggota.akun.destroy');

    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('/simpanan/{anggota}', [SimpananController::class, 'show'])->name('simpanan.show');
    Route::post('/simpanan/{anggota}/transaksi', [SimpananController::class, 'storeTransaksi'])->name('simpanan.transaksi.store');
    Route::delete('/simpanan/transaksi/{transaksi}', [SimpananController::class, 'destroyTransaksi'])->name('simpanan.transaksi.destroy');

    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
    Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::get('/pinjaman/{pinjaman}', [PinjamanController::class, 'show'])->name('pinjaman.show');
    Route::delete('/pinjaman/{pinjaman}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
    Route::post('/pinjaman/{pinjaman}/approve', [PinjamanController::class, 'approve'])->name('pinjaman.approve');
    Route::post('/pinjaman/{pinjaman}/reject', [PinjamanController::class, 'reject'])->name('pinjaman.reject');

    Route::post('/cicilan/{cicilan}/bayar', [PinjamanController::class, 'bayarCicilan'])->name('cicilan.bayar');
    Route::post('/cicilan/{cicilan}/batal', [PinjamanController::class, 'batalBayar'])->name('cicilan.batal');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Anggota (member) ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:anggota'])->prefix('me')->name('me.')->group(function () {
    Route::get('/',                       [MemberController::class, 'beranda'])      ->name('beranda');
    Route::get('/simpanan',               [MemberController::class, 'simpanan'])     ->name('simpanan');
    Route::get('/pinjaman',               [MemberController::class, 'pinjamanIndex'])->name('pinjaman.index');
    Route::get('/pinjaman/ajukan',        [MemberController::class, 'pinjamanCreate'])->name('pinjaman.create');
    Route::post('/pinjaman',              [MemberController::class, 'pinjamanStore'])->name('pinjaman.store');
    Route::get('/pinjaman/{pinjaman}',    [MemberController::class, 'pinjamanShow']) ->name('pinjaman.show');
    Route::get('/profil',                 [MemberController::class, 'profil'])       ->name('profil');
    Route::put('/profil/password',        [MemberController::class, 'updatePassword'])->name('password');
});

require __DIR__.'/auth.php';
