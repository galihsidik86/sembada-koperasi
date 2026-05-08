<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    anggota: { type: Object, required: true },
    filters: { type: Object, default: () => ({ q: '', status: '' }) },
});

const q = ref(props.filters.q || '');
const status = ref(props.filters.status || '');

let timer = null;
watch([q, status], () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get('/anggota', { q: q.value, status: status.value }, { preserveState: true, replace: true });
    }, 250);
});

function fmtDate(d) {
    if (!d) return '—';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}

const statusBadge = {
    'aktif':     'bg-padi-100 text-padi-700',
    'non-aktif': 'bg-emas-100 text-emas-700',
    'keluar':    'bg-bata-100 text-bata-700',
};
</script>

<template>
    <Head title="Anggota" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Anggota</h1>
        </template>

        <FlashBanner />

        <!-- Toolbar -->
        <div class="mb-4 flex flex-wrap items-center gap-3">
            <input
                v-model="q"
                type="search"
                placeholder="Cari nama, nomor anggota, atau NIK…"
                class="w-full max-w-sm rounded-lg border-cream-300 bg-white text-sm shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
            />
            <select
                v-model="status"
                class="rounded-lg border-cream-300 bg-white text-sm shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
            >
                <option value="">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="non-aktif">Non-aktif</option>
                <option value="keluar">Keluar</option>
            </select>

            <div class="flex-1" />

            <Link :href="route('anggota.create')">
                <PrimaryButton type="button">+ Tambah anggota</PrimaryButton>
            </Link>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
            <table class="w-full text-sm">
                <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                    <tr>
                        <th class="px-4 py-3">No. anggota</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">No. telp</th>
                        <th class="px-4 py-3">Tgl. masuk</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    <tr v-if="anggota.data.length === 0">
                        <td colspan="6" class="px-4 py-12 text-center text-tanah-500">
                            Belum ada anggota.
                            <Link :href="route('anggota.create')" class="text-sogan-500 hover:underline">Tambah satu, yuk.</Link>
                        </td>
                    </tr>
                    <tr v-for="a in anggota.data" :key="a.id" class="hover:bg-cream-50">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-tanah-700">{{ a.nomor_anggota }}</td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-wedel-900">{{ a.nama }}</div>
                            <div class="text-xs text-tanah-500">{{ a.panggilan || '—' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-tanah-500">{{ a.no_telp || '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-tanah-500">{{ fmtDate(a.tanggal_masuk) }}</td>
                        <td class="px-4 py-3">
                            <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold capitalize', statusBadge[a.status]]">
                                {{ a.status }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <Link :href="route('anggota.show', a.id)" class="text-sm font-medium text-sogan-500 hover:underline">Detail</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="anggota.last_page > 1" class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-tanah-500">
            <div>
                Menampilkan {{ anggota.from }}–{{ anggota.to }} dari {{ anggota.total }} anggota.
            </div>
            <div class="flex flex-wrap gap-1">
                <template v-for="(link, i) in anggota.links" :key="i">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        :class="['rounded-md border px-3 py-1.5',
                                 link.active
                                    ? 'border-sogan-500 bg-sogan-500 text-cream-50'
                                    : 'border-cream-300 bg-white hover:bg-cream-50 text-tanah-700']"
                    />
                    <span
                        v-else
                        v-html="link.label"
                        class="rounded-md border border-cream-200 bg-cream-50 px-3 py-1.5 text-tanah-300"
                    />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
