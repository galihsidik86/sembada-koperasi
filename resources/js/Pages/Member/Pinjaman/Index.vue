<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    anggota:  { type: Object, required: true },
    pinjaman: { type: Object, required: true },
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
    'cair':      'Aktif',
    'lunas':     'Lunas',
    'ditolak':   'Ditolak',
};
</script>

<template>
    <Head title="Pinjaman saya" />

    <MemberLayout>
        <div class="flex items-center justify-between">
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Pinjaman saya</h1>
            <Link href="/me/pinjaman/ajukan">
                <PrimaryButton type="button" class="!px-3 !py-1.5 text-xs">+ Ajukan</PrimaryButton>
            </Link>
        </div>

        <ul v-if="pinjaman.data.length > 0" class="mt-4 space-y-3">
            <li
                v-for="p in pinjaman.data"
                :key="p.id"
            >
                <Link
                    :href="`/me/pinjaman/${p.id}`"
                    class="block rounded-xl border border-cream-200 bg-white p-4 shadow-warm-xs transition-shadow hover:shadow-warm-sm"
                >
                    <div class="flex items-center justify-between">
                        <div class="font-mono text-xs text-tanah-500">{{ p.nomor_pinjaman }}</div>
                        <span :class="['rounded-full px-2 py-0.5 text-[10px] font-semibold', statusBadge[p.status]]">{{ statusLabel[p.status] }}</span>
                    </div>
                    <div class="mt-2 font-mono text-xl font-semibold text-wedel-900">{{ rupiah(p.pokok) }}</div>
                    <div class="text-xs text-tanah-500">{{ p.tenor_bulan }} bulan · diajukan {{ fmtDate(p.tanggal_pengajuan) }}</div>
                </Link>
            </li>
        </ul>

        <div v-else class="mt-6 rounded-xl border border-dashed border-cream-300 bg-white p-8 text-center">
            <p class="text-sm text-tanah-500">Belum pernah ajukan pinjaman.</p>
            <Link href="/me/pinjaman/ajukan" class="mt-2 inline-block text-sm font-semibold text-sogan-500 hover:underline">
                Ajukan pinjaman pertama →
            </Link>
        </div>
    </MemberLayout>
</template>
