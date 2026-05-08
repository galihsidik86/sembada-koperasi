<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    anggota:        { type: Object, required: true },
    pinjaman:       { type: Object, required: true },
    total_terbayar: { type: Number, required: true },
    sisa_tagihan:   { type: Number, required: true },
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

const statusBadge = {
    'pengajuan': 'bg-emas-100 text-emas-700',
    'disetujui': 'bg-padi-100 text-padi-700',
    'cair':      'bg-padi-100 text-padi-700',
    'ditolak':   'bg-bata-100 text-bata-700',
    'lunas':     'bg-cream-200 text-tanah-700',
    'belum_bayar':'bg-cream-200 text-tanah-700',
    'telat':     'bg-bata-100 text-bata-700',
};
const statusLabel = {
    'pengajuan': 'Menunggu persetujuan',
    'disetujui': 'Disetujui',
    'cair':      'Aktif',
    'lunas':     'Lunas',
    'ditolak':   'Ditolak',
    'belum_bayar': 'Belum bayar',
    'telat':     'Telat',
};
</script>

<template>
    <Head :title="pinjaman.nomor_pinjaman" />

    <MemberLayout>
        <FlashBanner />

        <div class="flex items-center gap-2">
            <Link href="/me/pinjaman" class="text-sm text-tanah-500 hover:text-sogan-500">←</Link>
            <h1 class="font-mono text-sm text-tanah-700">{{ pinjaman.nomor_pinjaman }}</h1>
            <span :class="['rounded-full px-2 py-0.5 text-[10px] font-semibold', statusBadge[pinjaman.status]]">{{ statusLabel[pinjaman.status] }}</span>
        </div>

        <section class="mt-3 rounded-2xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-5 text-cream-50 shadow-warm-md">
            <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-emas-300">POKOK PINJAMAN</div>
            <div class="mt-2 font-display text-3xl font-semibold leading-none" style="font-variation-settings: 'opsz' 72, 'SOFT' 30;">{{ rupiah(pinjaman.pokok) }}</div>
            <div class="mt-2 text-xs text-cream-100/80">{{ pinjaman.tenor_bulan }} bulan · {{ pinjaman.bunga_persen }}% per tahun · {{ pinjaman.metode_bunga }}</div>
        </section>

        <section v-if="pinjaman.cicilan && pinjaman.cicilan.length > 0" class="mt-5">
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-cream-200 bg-white p-3 shadow-warm-xs">
                    <div class="text-[10px] uppercase tracking-wider text-tanah-500">Sudah dibayar</div>
                    <div class="mt-1 font-mono text-base font-semibold text-padi-700">{{ rupiah(total_terbayar) }}</div>
                </div>
                <div class="rounded-xl border border-cream-200 bg-white p-3 shadow-warm-xs">
                    <div class="text-[10px] uppercase tracking-wider text-tanah-500">Sisa tagihan</div>
                    <div class="mt-1 font-mono text-base font-semibold text-wedel-900">{{ rupiah(sisa_tagihan) }}</div>
                </div>
            </div>
        </section>

        <section v-if="pinjaman.cicilan && pinjaman.cicilan.length > 0" class="mt-5">
            <h2 class="mb-3 text-sm font-semibold text-sogan-500">Jadwal angsuran</h2>
            <ul class="space-y-2">
                <li
                    v-for="c in pinjaman.cicilan"
                    :key="c.id"
                    class="flex items-center justify-between rounded-xl border border-cream-200 bg-white px-4 py-3 shadow-warm-xs"
                >
                    <div>
                        <div class="text-xs text-tanah-500">Cicilan ke {{ c.no_urut }} · jatuh tempo {{ fmtDate(c.jatuh_tempo) }}</div>
                        <div class="font-mono text-sm font-semibold text-wedel-900">{{ rupiah(c.total_due) }}</div>
                        <div v-if="c.tanggal_bayar" class="text-[11px] text-tanah-500">dibayar {{ fmtDate(c.tanggal_bayar) }}</div>
                    </div>
                    <span :class="['rounded-full px-2 py-0.5 text-[10px] font-semibold', statusBadge[c.status]]">{{ statusLabel[c.status] }}</span>
                </li>
            </ul>
        </section>

        <section v-else-if="pinjaman.status === 'pengajuan'" class="mt-5 rounded-xl border border-emas-300 bg-emas-100/40 p-4">
            <p class="text-sm text-emas-700">
                Pengajuan kamu sedang ditinjau pengurus. Kalau sudah disetujui, jadwal cicilan akan muncul di sini.
            </p>
        </section>

        <section v-else-if="pinjaman.status === 'ditolak'" class="mt-5 rounded-xl border border-bata-300 bg-bata-100/40 p-4">
            <h2 class="text-sm font-semibold text-bata-700">Pengajuan ditolak</h2>
            <p v-if="pinjaman.catatan_penolakan" class="mt-2 text-sm text-bata-700">{{ pinjaman.catatan_penolakan }}</p>
        </section>

        <section v-if="pinjaman.keterangan" class="mt-5 rounded-xl border border-cream-200 bg-white p-4 shadow-warm-xs">
            <h3 class="text-xs uppercase tracking-wider text-tanah-500">Keterangan</h3>
            <p class="mt-1 text-sm text-tanah-700">{{ pinjaman.keterangan }}</p>
        </section>
    </MemberLayout>
</template>
