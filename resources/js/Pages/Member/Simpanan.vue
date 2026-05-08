<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    anggota:     { type: Object, required: true },
    jenis:       { type: Array,  required: true },
    saldo:       { type: Object, required: true },
    saldo_total: { type: Number, required: true },
    transaksi:   { type: Object, required: true },
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
</script>

<template>
    <Head title="Simpanan saya" />

    <MemberLayout>
        <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Simpanan saya</h1>

        <!-- Saldo total -->
        <section class="mt-4 rounded-2xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-5 text-cream-50 shadow-warm-md">
            <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-emas-300">TOTAL</div>
            <div class="mt-2 font-display text-3xl font-semibold leading-none" style="font-variation-settings: 'opsz' 72, 'SOFT' 30;">{{ rupiah(saldo_total) }}</div>
        </section>

        <!-- Per jenis -->
        <section class="mt-4 space-y-2">
            <div v-for="j in jenis" :key="j.id" class="flex items-center justify-between rounded-xl border border-cream-200 bg-white px-4 py-3 shadow-warm-xs">
                <div>
                    <div class="font-semibold text-wedel-900">{{ j.nama }}</div>
                    <div class="text-xs" :class="j.dapat_ditarik ? 'text-padi-700' : 'text-tanah-500'">
                        {{ j.dapat_ditarik ? 'Bisa ditarik' : 'Tidak bisa ditarik' }}
                    </div>
                </div>
                <div class="font-mono text-sm font-semibold text-wedel-900">{{ rupiah(saldo[j.kode]) }}</div>
            </div>
        </section>

        <!-- Riwayat -->
        <section class="mt-6">
            <h2 class="mb-3 text-sm font-semibold text-sogan-500">Riwayat transaksi</h2>

            <div v-if="transaksi.data.length === 0" class="rounded-xl border border-dashed border-cream-300 bg-white p-6 text-center text-sm text-tanah-500">
                Belum ada transaksi. Setoran pertamamu akan tampil di sini.
            </div>

            <ul v-else class="space-y-2">
                <li
                    v-for="t in transaksi.data"
                    :key="t.id"
                    class="flex items-center justify-between rounded-xl border border-cream-200 bg-white px-4 py-3 shadow-warm-xs"
                >
                    <div>
                        <div class="text-sm font-semibold text-wedel-900">
                            <span :class="t.tipe === 'setor' ? 'text-padi-700' : 'text-bata-700'">
                                {{ t.tipe === 'setor' ? 'Setor' : 'Tarik' }}
                            </span>
                            · {{ t.jenis?.nama }}
                        </div>
                        <div class="text-xs text-tanah-500">{{ fmtDate(t.tanggal) }}</div>
                        <div v-if="t.keterangan" class="mt-0.5 text-xs italic text-tanah-500">{{ t.keterangan }}</div>
                    </div>
                    <div class="font-mono text-sm font-semibold" :class="t.tipe === 'setor' ? 'text-padi-700' : 'text-bata-700'">
                        {{ t.tipe === 'setor' ? '+' : '−' }}{{ rupiah(t.nominal) }}
                    </div>
                </li>
            </ul>

            <div v-if="transaksi.last_page > 1" class="mt-4 text-center text-xs text-tanah-500">
                Hal {{ transaksi.current_page }} dari {{ transaksi.last_page }}
            </div>
        </section>
    </MemberLayout>
</template>
