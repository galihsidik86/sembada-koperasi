<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    pinjaman: { type: Object, required: true },
    filters:  { type: Object, default: () => ({ q: '', status: '' }) },
    counts:   { type: Object, default: () => ({}) },
});

const q = ref(props.filters.q || '');
const status = ref(props.filters.status || '');

let timer = null;
watch([q, status], () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get('/pinjaman', { q: q.value, status: status.value }, { preserveState: true, replace: true });
    }, 250);
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
};

const statusLabel = {
    'pengajuan': 'Menunggu',
    'disetujui': 'Disetujui',
    'cair':      'Cair',
    'ditolak':   'Ditolak',
    'lunas':     'Lunas',
};
</script>

<template>
    <Head title="Pinjaman" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Pinjaman</h1>
        </template>

        <FlashBanner />

        <!-- Quick stats -->
        <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-5">
            <button
                v-for="key in ['pengajuan','cair','lunas','ditolak']"
                :key="key"
                @click="status = status === key ? '' : key"
                :class="['rounded-lg border bg-white px-3 py-2 text-left text-sm shadow-warm-xs transition-colors',
                         status === key ? 'border-sogan-500 bg-sogan-50' : 'border-cream-200 hover:bg-cream-50']"
            >
                <div class="text-xs uppercase tracking-wider text-tanah-500">{{ statusLabel[key] }}</div>
                <div class="mt-1 font-display text-xl font-semibold text-wedel-900">{{ counts[key] || 0 }}</div>
            </button>
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-3">
            <input
                v-model="q"
                type="search"
                placeholder="Cari nomor pinjaman / anggota…"
                class="w-full max-w-sm rounded-lg border-cream-300 bg-white text-sm shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
            />
            <div class="flex-1" />
            <Link :href="route('pinjaman.create')">
                <PrimaryButton type="button">+ Pengajuan baru</PrimaryButton>
            </Link>
        </div>

        <div class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
            <table class="w-full text-sm">
                <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                    <tr>
                        <th class="px-4 py-3">No. pinjaman</th>
                        <th class="px-4 py-3">Anggota</th>
                        <th class="px-4 py-3 text-right">Pokok</th>
                        <th class="px-4 py-3 text-right">Bunga</th>
                        <th class="px-4 py-3 text-right">Tenor</th>
                        <th class="px-4 py-3">Pengajuan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    <tr v-if="pinjaman.data.length === 0">
                        <td colspan="8" class="px-4 py-12 text-center text-tanah-500">
                            Belum ada pinjaman.
                            <Link :href="route('pinjaman.create')" class="text-sogan-500 hover:underline">Buat pengajuan pertama.</Link>
                        </td>
                    </tr>
                    <tr v-for="p in pinjaman.data" :key="p.id" class="hover:bg-cream-50">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-tanah-700">{{ p.nomor_pinjaman }}</td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-wedel-900">{{ p.anggota?.nama }}</div>
                            <div class="font-mono text-xs text-tanah-500">{{ p.anggota?.nomor_anggota }}</div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right font-mono">{{ rupiah(p.pokok) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right text-tanah-500">{{ p.bunga_persen }}% {{ p.metode_bunga }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right text-tanah-500">{{ p.tenor_bulan }} bulan</td>
                        <td class="whitespace-nowrap px-4 py-3 text-tanah-500">{{ fmtDate(p.tanggal_pengajuan) }}</td>
                        <td class="px-4 py-3">
                            <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold capitalize', statusBadge[p.status]]">{{ statusLabel[p.status] }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <Link :href="route('pinjaman.show', p.id)" class="text-sm font-medium text-sogan-500 hover:underline">Detail</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="pinjaman.last_page > 1" class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-tanah-500">
            <div>Menampilkan {{ pinjaman.from }}–{{ pinjaman.to }} dari {{ pinjaman.total }}.</div>
            <div class="flex flex-wrap gap-1">
                <template v-for="(link, i) in pinjaman.links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        :class="['rounded-md border px-3 py-1.5',
                                 link.active
                                    ? 'border-sogan-500 bg-sogan-500 text-cream-50'
                                    : 'border-cream-300 bg-white hover:bg-cream-50 text-tanah-700']"
                    />
                    <span v-else v-html="link.label" class="rounded-md border border-cream-200 bg-cream-50 px-3 py-1.5 text-tanah-300" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
