<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            anggota_aktif: 0,
            total_simpanan: 0,
            pinjaman_aktif: 0,
            pinjaman_pending: 0,
            shu_sementara: 0,
        }),
    },
    pengajuan_queue: { type: Array, default: () => [] },
    cicilan_jatuh:   { type: Array, default: () => [] },
});

const user = computed(() => usePage().props.auth?.user);

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}
function fmtDate(d) {
    if (!d) return '—';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}

const cards = computed(() => [
    { label: 'Anggota aktif',    value: props.stats.anggota_aktif,            note: 'orang',                                       href: '/anggota'  },
    { label: 'Total simpanan',   value: rupiah(props.stats.total_simpanan),   note: 'pokok + wajib + sukarela',                    href: '/simpanan' },
    { label: 'Pinjaman aktif',   value: rupiah(props.stats.pinjaman_aktif),   note: `${props.stats.pinjaman_pending || 0} pengajuan menunggu`, href: '/pinjaman' },
    { label: 'SHU sementara',    value: rupiah(props.stats.shu_sementara),    note: 'tahun berjalan',                              href: '/laporan'  },
]);
</script>

<template>
    <Head title="Beranda" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Beranda</h1>
        </template>

        <div class="space-y-6">
            <!-- Welcome card -->
            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-6 text-cream-50 shadow-warm-md sm:p-8">
                <div
                    class="pointer-events-none absolute inset-0 opacity-[0.07]"
                    style="background-image: url('/design-system/assets/patterns/kawung.svg'); background-size: 120px;"
                />
                <div class="relative">
                    <p class="text-xs font-bold tracking-[0.18em] text-emas-300">SELAMAT DATANG</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold leading-tight" style="font-variation-settings: 'opsz' 72, 'SOFT' 30;">Halo, {{ user?.name?.split(' ')[0] || 'pengurus' }}.</h2>
                    <p class="mt-2 max-w-xl text-sm text-cream-100/85">
                        Ringkasan operasional koperasi kamu hari ini.
                    </p>
                </div>
            </section>

            <!-- KPI grid -->
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="c in cards"
                    :key="c.label"
                    :href="c.href"
                    class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm transition-shadow hover:shadow-warm-md"
                >
                    <div class="text-xs font-medium uppercase tracking-wider text-tanah-500">{{ c.label }}</div>
                    <div class="mt-2 font-display text-2xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 48, 'SOFT' 30;">{{ c.value }}</div>
                    <div class="mt-1 text-xs text-tanah-500">{{ c.note }}</div>
                </Link>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Pengajuan menunggu -->
                <section class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
                    <header class="flex items-center justify-between border-b border-cream-200 bg-cream-50 px-5 py-3">
                        <h2 class="text-sm font-semibold text-sogan-500">Pengajuan menunggu persetujuan</h2>
                        <Link href="/pinjaman?status=pengajuan" class="text-xs text-tanah-500 hover:text-sogan-500">Lihat semua</Link>
                    </header>
                    <ul v-if="pengajuan_queue.length > 0" class="divide-y divide-cream-200">
                        <li v-for="p in pengajuan_queue" :key="p.id" class="flex items-center justify-between px-5 py-3 hover:bg-cream-50">
                            <div>
                                <Link :href="route('pinjaman.show', p.id)" class="font-semibold text-wedel-900 hover:text-sogan-500">{{ p.anggota?.nama }}</Link>
                                <div class="text-xs text-tanah-500">{{ p.nomor_pinjaman }} · {{ p.tenor_bulan }} bln · {{ fmtDate(p.tanggal_pengajuan) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono text-sm font-semibold text-wedel-900">{{ rupiah(p.pokok) }}</div>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="px-5 py-8 text-center text-sm text-tanah-500">
                        Tidak ada pengajuan menunggu.
                    </div>
                </section>

                <!-- Cicilan jatuh tempo dekat -->
                <section class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
                    <header class="flex items-center justify-between border-b border-cream-200 bg-cream-50 px-5 py-3">
                        <h2 class="text-sm font-semibold text-sogan-500">Cicilan jatuh tempo dalam 7 hari</h2>
                    </header>
                    <ul v-if="cicilan_jatuh.length > 0" class="divide-y divide-cream-200">
                        <li v-for="c in cicilan_jatuh" :key="c.id" class="flex items-center justify-between px-5 py-3 hover:bg-cream-50">
                            <div>
                                <Link :href="route('pinjaman.show', c.pinjaman?.id)" class="font-semibold text-wedel-900 hover:text-sogan-500">{{ c.pinjaman?.anggota?.nama }}</Link>
                                <div class="text-xs text-tanah-500">{{ c.pinjaman?.nomor_pinjaman }} · cicilan #{{ c.no_urut }} · jatuh tempo {{ fmtDate(c.jatuh_tempo) }}</div>
                            </div>
                            <div class="font-mono text-sm font-semibold text-wedel-900">{{ rupiah(c.total_due) }}</div>
                        </li>
                    </ul>
                    <div v-else class="px-5 py-8 text-center text-sm text-tanah-500">
                        Tidak ada cicilan jatuh tempo dalam 7 hari ke depan.
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
