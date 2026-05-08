<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    tahun:     { type: Number, required: true },
    periode:   { type: Object, required: true },
    ringkasan: { type: Object, required: true },
    rincian:   { type: Array,  required: true },
});

const tahunSelected = ref(props.tahun);
watch(tahunSelected, (val) => {
    router.get('/laporan', { tahun: val }, { preserveState: false });
});

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}

const tahunOptions = [];
for (let y = new Date().getFullYear(); y >= 2020; y--) tahunOptions.push(y);
</script>

<template>
    <Head title="Laporan SHU" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Laporan SHU</h1>
                <select
                    v-model="tahunSelected"
                    class="rounded-lg border-cream-300 bg-white text-sm shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                >
                    <option v-for="y in tahunOptions" :key="y" :value="y">Periode {{ y }}</option>
                </select>
            </div>
        </template>

        <!-- Hero ringkasan -->
        <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-6 text-cream-50 shadow-warm-md">
            <div
                class="pointer-events-none absolute inset-0 opacity-[0.06]"
                style="background-image: url('/design-system/assets/patterns/kawung.svg'); background-size: 120px;"
            />
            <div class="relative grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <div class="text-xs font-bold uppercase tracking-[0.18em] text-emas-300">SHU SEMENTARA {{ tahun }}</div>
                    <div class="mt-2 font-display text-4xl font-semibold leading-none" style="font-variation-settings: 'opsz' 96, 'SOFT' 30;">{{ rupiah(ringkasan.total_bunga_terbayar) }}</div>
                    <div class="mt-2 text-xs text-cream-100/80">Bunga pinjaman yang sudah terbayar dalam periode.</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wider text-cream-100/70">Alokasi Jasa Simpanan ({{ ringkasan.persen_simpanan }}%)</div>
                    <div class="mt-2 font-mono text-xl font-semibold">{{ rupiah(ringkasan.alokasi_jasa_simpanan) }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wider text-cream-100/70">Alokasi Jasa Pinjaman ({{ ringkasan.persen_pinjaman }}%)</div>
                    <div class="mt-2 font-mono text-xl font-semibold">{{ rupiah(ringkasan.alokasi_jasa_pinjaman) }}</div>
                </div>
            </div>
        </section>

        <p class="mt-3 text-xs text-tanah-500">
            Sisa {{ 100 - ringkasan.persen_simpanan - ringkasan.persen_pinjaman }}% dari pendapatan dialokasikan ke Dana Cadangan, Pengurus, dan Sosial sesuai AD/ART koperasi (belum diatur di sistem).
        </p>

        <!-- Tabel rincian -->
        <section class="mt-6 overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
            <header class="border-b border-cream-200 bg-cream-50 px-5 py-3">
                <h2 class="text-sm font-semibold text-sogan-500">Rincian per anggota</h2>
            </header>
            <table class="w-full text-sm">
                <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                    <tr>
                        <th class="px-4 py-2">No. anggota</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2 text-right">Saldo simpanan</th>
                        <th class="px-4 py-2 text-right">Bunga dibayar</th>
                        <th class="px-4 py-2 text-right">Jasa simpanan</th>
                        <th class="px-4 py-2 text-right">Jasa pinjaman</th>
                        <th class="px-4 py-2 text-right">Total SHU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    <tr v-if="rincian.length === 0">
                        <td colspan="7" class="px-4 py-12 text-center text-tanah-500">Belum ada anggota.</td>
                    </tr>
                    <tr v-for="r in rincian" :key="r.id" class="hover:bg-cream-50">
                        <td class="whitespace-nowrap px-4 py-2 font-mono text-xs text-tanah-700">{{ r.nomor_anggota }}</td>
                        <td class="px-4 py-2 font-semibold text-wedel-900">{{ r.nama }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-tanah-700">{{ rupiah(r.saldo) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-tanah-500">{{ rupiah(r.bunga_dibayar) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-padi-700">{{ rupiah(r.jasa_simpanan) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-padi-700">{{ rupiah(r.jasa_pinjaman) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono font-semibold text-wedel-900">{{ rupiah(r.total_shu) }}</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </AuthenticatedLayout>
</template>
