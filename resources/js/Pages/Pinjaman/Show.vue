<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    pinjaman:        { type: Object, required: true },
    total_terbayar:  { type: Number, required: true },
    sisa_tagihan:    { type: Number, required: true },
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

// Approve form
const approveForm = useForm({
    tanggal_disetujui: new Date().toISOString().slice(0, 10),
    tanggal_cair:      new Date().toISOString().slice(0, 10),
});
const showApprove = ref(false);
const approve = () => approveForm.post(route('pinjaman.approve', props.pinjaman.id), {
    onSuccess: () => { showApprove.value = false; },
});

// Reject form
const rejectForm = useForm({ catatan_penolakan: '' });
const showReject = ref(false);
const reject = () => rejectForm.post(route('pinjaman.reject', props.pinjaman.id), {
    onSuccess: () => { showReject.value = false; },
});

// Bayar cicilan
const bayarFor = ref(null); // cicilan id
const bayarForm = useForm({ dibayar: '', tanggal_bayar: new Date().toISOString().slice(0, 10) });
function openBayar(c) {
    bayarFor.value = c.id;
    bayarForm.dibayar = c.total_due;
    bayarForm.tanggal_bayar = new Date().toISOString().slice(0, 10);
}
function submitBayar() {
    bayarForm.post(route('cicilan.bayar', bayarFor.value), {
        onSuccess: () => { bayarFor.value = null; },
    });
}
function batalBayar(c) {
    if (!confirm(`Batalkan pembayaran cicilan #${c.no_urut}?`)) return;
    router.post(route('cicilan.batal', c.id));
}

function destroyPinjaman() {
    if (!confirm('Hapus pengajuan pinjaman ini?')) return;
    router.delete(route('pinjaman.destroy', props.pinjaman.id));
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
    'cair':      'Cair',
    'ditolak':   'Ditolak',
    'lunas':     'Lunas',
    'belum_bayar': 'Belum bayar',
    'telat':     'Telat',
};
</script>

<template>
    <Head :title="pinjaman.nomor_pinjaman" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center gap-3">
                <Link href="/pinjaman" class="text-sm text-tanah-500 hover:text-sogan-500">← Pinjaman</Link>
                <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">{{ pinjaman.nomor_pinjaman }}</h1>
                <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', statusBadge[pinjaman.status]]">{{ statusLabel[pinjaman.status] }}</span>
            </div>
        </template>

        <FlashBanner />

        <!-- Info card -->
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm lg:col-span-2">
                <header class="border-b border-cream-200 bg-cream-50 px-5 py-3">
                    <h2 class="text-sm font-semibold text-sogan-500">Detail pinjaman</h2>
                </header>
                <dl class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Anggota</dt>
                        <dd class="mt-1 font-semibold text-wedel-900">
                            <Link :href="route('anggota.show', pinjaman.anggota.id)" class="hover:text-sogan-500 hover:underline">{{ pinjaman.anggota?.nama }}</Link>
                        </dd>
                        <dd class="font-mono text-xs text-tanah-500">{{ pinjaman.anggota?.nomor_anggota }}{{ pinjaman.anggota?.no_telp ? ` · ${pinjaman.anggota.no_telp}` : '' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Pokok pinjaman</dt>
                        <dd class="mt-1 font-display text-2xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 48, 'SOFT' 30;">{{ rupiah(pinjaman.pokok) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Bunga & tenor</dt>
                        <dd class="mt-1 text-tanah-700">{{ pinjaman.bunga_persen }}% per tahun · {{ pinjaman.tenor_bulan }} bulan · <span class="capitalize">{{ pinjaman.metode_bunga }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Sisa tagihan</dt>
                        <dd class="mt-1 font-mono font-semibold text-wedel-900">{{ rupiah(sisa_tagihan) }}</dd>
                        <dd class="text-xs text-tanah-500">terbayar {{ rupiah(total_terbayar) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Tanggal pengajuan</dt>
                        <dd class="mt-1 text-tanah-700">{{ fmtDate(pinjaman.tanggal_pengajuan) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Tanggal cair</dt>
                        <dd class="mt-1 text-tanah-700">{{ fmtDate(pinjaman.tanggal_cair) }}</dd>
                    </div>
                    <div v-if="pinjaman.keterangan" class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Keterangan</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-tanah-700">{{ pinjaman.keterangan }}</dd>
                    </div>
                    <div v-if="pinjaman.catatan_penolakan" class="sm:col-span-2 rounded-lg bg-bata-100/40 p-3">
                        <dt class="text-xs font-semibold text-bata-700">Alasan penolakan</dt>
                        <dd class="mt-1 text-sm text-bata-700">{{ pinjaman.catatan_penolakan }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Action sidebar -->
            <aside class="space-y-4">
                <!-- Approve / reject for status=pengajuan -->
                <div v-if="pinjaman.status === 'pengajuan'" class="rounded-xl border border-emas-300 bg-emas-100/40 p-5">
                    <h2 class="text-sm font-semibold text-emas-700">Menunggu keputusan</h2>
                    <p class="mt-1 text-xs text-tanah-700">
                        Setujui untuk membuat jadwal angsuran otomatis, atau tolak dengan alasan.
                    </p>

                    <div class="mt-3 space-y-2">
                        <PrimaryButton v-if="!showApprove && !showReject" type="button" class="w-full" @click="showApprove = true">
                            Setujui & cairkan
                        </PrimaryButton>
                        <SecondaryButton v-if="!showApprove && !showReject" type="button" class="w-full" @click="showReject = true">
                            Tolak
                        </SecondaryButton>

                        <form v-if="showApprove" class="space-y-3" @submit.prevent="approve">
                            <div>
                                <InputLabel for="tanggal_disetujui" value="Tanggal disetujui" />
                                <TextInput id="tanggal_disetujui" v-model="approveForm.tanggal_disetujui" type="date" class="mt-1 block w-full" required />
                                <InputError class="mt-1" :message="approveForm.errors.tanggal_disetujui" />
                            </div>
                            <div>
                                <InputLabel for="tanggal_cair" value="Tanggal cair" />
                                <TextInput id="tanggal_cair" v-model="approveForm.tanggal_cair" type="date" class="mt-1 block w-full" required />
                                <InputError class="mt-1" :message="approveForm.errors.tanggal_cair" />
                            </div>
                            <div class="flex gap-2">
                                <PrimaryButton :disabled="approveForm.processing" :class="{ 'opacity-50': approveForm.processing }">Cairkan</PrimaryButton>
                                <SecondaryButton type="button" @click="showApprove = false">Batal</SecondaryButton>
                            </div>
                        </form>

                        <form v-if="showReject" class="space-y-3" @submit.prevent="reject">
                            <div>
                                <InputLabel for="catatan_penolakan" value="Alasan penolakan" />
                                <textarea
                                    id="catatan_penolakan"
                                    v-model="rejectForm.catatan_penolakan"
                                    rows="3"
                                    class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                                    required
                                />
                                <InputError class="mt-1" :message="rejectForm.errors.catatan_penolakan" />
                            </div>
                            <div class="flex gap-2">
                                <DangerButton @click="reject" type="button">Tolak</DangerButton>
                                <SecondaryButton type="button" @click="showReject = false">Batal</SecondaryButton>
                            </div>
                        </form>

                        <button
                            v-if="!showApprove && !showReject"
                            @click="destroyPinjaman"
                            class="w-full rounded-md px-3 py-2 text-xs text-tanah-500 hover:bg-cream-50 hover:text-bata-700"
                        >
                            Hapus pengajuan
                        </button>
                    </div>
                </div>

                <div v-if="pinjaman.status === 'cair'" class="rounded-xl border border-padi-300 bg-padi-100/40 p-5">
                    <h2 class="text-sm font-semibold text-padi-700">Pinjaman aktif</h2>
                    <p class="mt-1 text-xs text-tanah-700">Catat pembayaran cicilan saat anggota membayar.</p>
                </div>

                <div v-if="pinjaman.status === 'lunas'" class="rounded-xl border border-cream-300 bg-cream-100 p-5">
                    <h2 class="text-sm font-semibold text-tanah-700">Pinjaman lunas</h2>
                    <p class="mt-1 text-xs text-tanah-500">Lunas pada {{ fmtDate(pinjaman.tanggal_lunas) }}.</p>
                </div>
            </aside>
        </section>

        <!-- Jadwal angsuran -->
        <section v-if="pinjaman.cicilan && pinjaman.cicilan.length > 0" class="mt-6 overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm">
            <header class="border-b border-cream-200 bg-cream-50 px-5 py-3">
                <h2 class="text-sm font-semibold text-sogan-500">Jadwal angsuran</h2>
            </header>

            <table class="w-full text-sm">
                <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                    <tr>
                        <th class="px-4 py-2 text-right">#</th>
                        <th class="px-4 py-2">Jatuh tempo</th>
                        <th class="px-4 py-2 text-right">Pokok</th>
                        <th class="px-4 py-2 text-right">Bunga</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    <tr v-for="c in pinjaman.cicilan" :key="c.id" class="hover:bg-cream-50">
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-tanah-700">{{ c.no_urut }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-tanah-700">{{ fmtDate(c.jatuh_tempo) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-tanah-700">{{ rupiah(c.pokok_due) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono text-tanah-500">{{ rupiah(c.bunga_due) }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-right font-mono font-semibold text-wedel-900">{{ rupiah(c.total_due) }}</td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold', statusBadge[c.status]]">{{ statusLabel[c.status] }}</span>
                            <span v-if="c.tanggal_bayar" class="ml-2 text-xs text-tanah-500">{{ fmtDate(c.tanggal_bayar) }}</span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-right">
                            <template v-if="c.status === 'belum_bayar'">
                                <button v-if="bayarFor !== c.id" @click="openBayar(c)" class="text-sm font-medium text-sogan-500 hover:underline">Catat bayar</button>
                                <form v-else class="flex items-center gap-1" @submit.prevent="submitBayar">
                                    <TextInput v-model="bayarForm.tanggal_bayar" type="date" class="!py-1 text-xs" />
                                    <PrimaryButton :disabled="bayarForm.processing" class="!px-2 !py-1 !text-xs">OK</PrimaryButton>
                                    <button type="button" @click="bayarFor = null" class="text-xs text-tanah-500 hover:underline">Batal</button>
                                </form>
                            </template>
                            <button v-else @click="batalBayar(c)" class="text-xs text-tanah-500 hover:text-bata-700 hover:underline">Batal bayar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Empty schedule for pengajuan -->
        <section v-else class="mt-6 rounded-xl border border-dashed border-cream-300 bg-white p-8 text-center">
            <p class="text-sm text-tanah-500">
                Jadwal angsuran akan dibuat otomatis setelah pengajuan ini disetujui.
            </p>
        </section>
    </AuthenticatedLayout>
</template>
