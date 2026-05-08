<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    anggota:         { type: Object, required: true },
    jenis:           { type: Array,  required: true },
    saldo:           { type: Object, required: true },
    saldo_total:     { type: Number, required: true },
    pinjaman_aktif:  { type: [Object, null], default: null },
    cicilan_berikut: { type: [Object, null], default: null },
});

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}
function fmtDate(d) {
    if (!d) return '—';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}

const statusLabel = {
    'pengajuan': 'Menunggu',
    'disetujui': 'Disetujui',
    'cair':      'Aktif',
    'lunas':     'Lunas',
    'ditolak':   'Ditolak',
};
</script>

<template>
    <Head title="Beranda" />

    <MemberLayout>
        <FlashBanner />

        <p class="text-sm text-tanah-500">Halo,</p>
        <h1 class="font-display text-2xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 48, 'SOFT' 30;">
            {{ anggota.panggilan || anggota.nama.split(' ')[0] }}.
        </h1>

        <!-- Saldo hero -->
        <section class="relative mt-4 overflow-hidden rounded-2xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-5 text-cream-50 shadow-warm-md">
            <div
                class="pointer-events-none absolute inset-0 opacity-[0.07]"
                style="background-image: url('/design-system/assets/patterns/kawung.svg'); background-size: 100px;"
            />
            <div class="relative">
                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-emas-300">SIMPANAN KAMU</div>
                <div class="mt-2 font-display text-3xl font-semibold leading-none" style="font-variation-settings: 'opsz' 72, 'SOFT' 30;">{{ rupiah(saldo_total) }}</div>
                <div class="mt-3 grid grid-cols-3 gap-2 text-[11px]">
                    <div v-for="j in jenis" :key="j.id" class="rounded-lg bg-cream-100/10 px-2 py-1.5">
                        <div class="text-cream-100/70">{{ j.nama.replace('Simpanan ', '') }}</div>
                        <div class="font-mono font-semibold">{{ rupiah(saldo[j.kode]) }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick actions -->
        <section class="mt-5 grid grid-cols-2 gap-3">
            <Link
                href="/me/simpanan"
                class="rounded-xl border border-cream-200 bg-white p-4 shadow-warm-xs transition-shadow hover:shadow-warm-sm"
            >
                <div class="text-xs uppercase tracking-wider text-tanah-500">Lihat</div>
                <div class="mt-1 font-display text-base font-semibold text-wedel-900">Riwayat simpanan</div>
            </Link>
            <Link
                href="/me/pinjaman"
                class="rounded-xl border border-cream-200 bg-white p-4 shadow-warm-xs transition-shadow hover:shadow-warm-sm"
            >
                <div class="text-xs uppercase tracking-wider text-tanah-500">Cek</div>
                <div class="mt-1 font-display text-base font-semibold text-wedel-900">Pinjaman saya</div>
            </Link>
        </section>

        <!-- Pinjaman aktif card -->
        <section v-if="pinjaman_aktif" class="mt-5 rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-sogan-500">Pinjaman aktif</h2>
                <span class="rounded-full bg-padi-100 px-2 py-0.5 text-[10px] font-semibold text-padi-700">{{ statusLabel[pinjaman_aktif.status] }}</span>
            </div>
            <div class="mt-2 font-mono text-xl font-semibold text-wedel-900">{{ rupiah(pinjaman_aktif.pokok) }}</div>
            <div class="text-xs text-tanah-500">{{ pinjaman_aktif.tenor_bulan }} bulan · {{ pinjaman_aktif.bunga_persen }}% per tahun</div>

            <div v-if="cicilan_berikut" class="mt-4 rounded-lg bg-emas-100 p-3">
                <div class="text-[10px] font-bold uppercase tracking-wider text-emas-700">CICILAN BERIKUTNYA</div>
                <div class="mt-1 flex items-baseline justify-between">
                    <div class="font-mono text-base font-semibold text-wedel-900">{{ rupiah(cicilan_berikut.total_due) }}</div>
                    <div class="text-xs text-emas-700">jatuh tempo {{ fmtDate(cicilan_berikut.jatuh_tempo) }}</div>
                </div>
            </div>

            <Link
                :href="`/me/pinjaman/${pinjaman_aktif.id}`"
                class="mt-3 block text-center text-sm font-semibold text-sogan-500 hover:underline"
            >
                Lihat jadwal lengkap →
            </Link>
        </section>

        <section v-else class="mt-5 rounded-xl border border-dashed border-cream-300 bg-white p-5 text-center">
            <p class="text-sm text-tanah-500">Kamu belum punya pinjaman aktif.</p>
            <Link href="/me/pinjaman/ajukan" class="mt-2 inline-block text-sm font-semibold text-sogan-500 hover:underline">
                Ajukan pinjaman →
            </Link>
        </section>
    </MemberLayout>
</template>
