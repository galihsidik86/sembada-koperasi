<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    anggota: { type: Object, required: true },
    jenis:   { type: Array,  required: true },
    filters: { type: Object, default: () => ({ q: '' }) },
});

const q = ref(props.filters.q || '');
let timer = null;
watch(q, () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        router.get('/simpanan', { q: q.value }, { preserveState: true, replace: true });
    }, 250);
});

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Simpanan" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Simpanan</h1>
        </template>

        <FlashBanner />

        <div class="mb-4 flex items-center gap-3">
            <input
                v-model="q"
                type="search"
                placeholder="Cari anggota…"
                class="w-full max-w-sm rounded-lg border-cream-300 bg-white text-sm shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
            />
        </div>

        <div class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
            <table class="w-full text-sm">
                <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                    <tr>
                        <th class="px-4 py-3">No. anggota</th>
                        <th class="px-4 py-3">Nama</th>
                        <th v-for="j in jenis" :key="j.id" class="px-4 py-3 text-right">{{ j.nama }}</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    <tr v-if="anggota.data.length === 0">
                        <td :colspan="jenis.length + 4" class="px-4 py-12 text-center text-tanah-500">
                            Belum ada anggota aktif. Tambah anggota dulu di menu Anggota.
                        </td>
                    </tr>
                    <tr v-for="a in anggota.data" :key="a.id" class="hover:bg-cream-50">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-tanah-700">{{ a.nomor_anggota }}</td>
                        <td class="px-4 py-3 font-semibold text-wedel-900">{{ a.nama }}</td>
                        <td v-for="j in jenis" :key="j.id" class="whitespace-nowrap px-4 py-3 text-right font-mono text-sm text-tanah-700">
                            {{ rupiah(a.saldo[j.kode]) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right font-mono text-sm font-semibold text-wedel-900">{{ rupiah(a.saldo_total) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <Link :href="route('simpanan.show', a.id)" class="text-sm font-medium text-sogan-500 hover:underline">Detail</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="anggota.last_page > 1" class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-tanah-500">
            <div>Menampilkan {{ anggota.from }}–{{ anggota.to }} dari {{ anggota.total }}.</div>
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
                    <span v-else v-html="link.label" class="rounded-md border border-cream-200 bg-cream-50 px-3 py-1.5 text-tanah-300" />
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
