<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    anggota:     { type: Object, required: true },
    jenis:       { type: Array,  required: true },
    saldo:       { type: Object, required: true },
    saldo_total: { type: Number, required: true },
    transaksi:   { type: Object, required: true },
});

const form = useForm({
    jenis_simpanan_id: props.jenis[0]?.id,
    tipe: 'setor',
    nominal: '',
    tanggal: new Date().toISOString().slice(0, 10),
    keterangan: '',
});

const submit = () => form.post(route('simpanan.transaksi.store', props.anggota.id), {
    onSuccess: () => form.reset('nominal', 'keterangan'),
});

const jenisAktif = computed(() => props.jenis.find(j => j.id === form.jenis_simpanan_id));

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}

function fmtDate(d) {
    if (!d) return '—';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}

function deleteTransaksi(t) {
    if (!confirm(`Hapus transaksi ${t.tipe} ${rupiah(t.nominal)}? Tindakan ini permanen.`)) return;
    router.delete(route('simpanan.transaksi.destroy', t.id));
}
</script>

<template>
    <Head :title="`Simpanan — ${anggota.nama}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center gap-3">
                <Link href="/simpanan" class="text-sm text-tanah-500 hover:text-sogan-500">← Simpanan</Link>
                <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">{{ anggota.nama }}</h1>
                <span class="font-mono text-xs text-tanah-500">{{ anggota.nomor_anggota }}</span>
            </div>
        </template>

        <FlashBanner />

        <!-- Saldo summary -->
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-gradient-to-br from-sogan-700 to-sogan-500 p-5 text-cream-50 shadow-warm-md">
                <div class="text-xs font-bold uppercase tracking-wider text-emas-300">Total simpanan</div>
                <div class="mt-2 font-display text-3xl font-semibold leading-none" style="font-variation-settings: 'opsz' 72, 'SOFT' 30;">{{ rupiah(saldo_total) }}</div>
                <div class="mt-2 text-xs text-cream-100/80">{{ anggota.nama }}</div>
            </div>
            <div v-for="j in jenis" :key="j.id" class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
                <div class="text-xs uppercase tracking-wider text-tanah-500">{{ j.nama }}</div>
                <div class="mt-2 font-display text-2xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 48, 'SOFT' 30;">{{ rupiah(saldo[j.kode]) }}</div>
                <div class="mt-1 text-xs" :class="j.dapat_ditarik ? 'text-padi-700' : 'text-tanah-500'">
                    {{ j.dapat_ditarik ? 'Bisa ditarik' : 'Tidak bisa ditarik' }}
                </div>
            </div>
        </section>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Form transaksi -->
            <section class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm lg:col-span-1">
                <h2 class="text-sm font-semibold text-sogan-500">Catat transaksi</h2>

                <form class="mt-4 space-y-4" @submit.prevent="submit">
                    <div>
                        <InputLabel value="Jenis simpanan" />
                        <select
                            v-model="form.jenis_simpanan_id"
                            class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                            required
                        >
                            <option v-for="j in jenis" :key="j.id" :value="j.id">{{ j.nama }}</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.jenis_simpanan_id" />
                    </div>

                    <div>
                        <InputLabel value="Tipe" />
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <label
                                :class="['flex cursor-pointer items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm font-semibold',
                                         form.tipe === 'setor' ? 'border-padi-500 bg-padi-100 text-padi-700' : 'border-cream-300 bg-white text-tanah-500']"
                            >
                                <input type="radio" v-model="form.tipe" value="setor" class="sr-only" />
                                Setor
                            </label>
                            <label
                                :class="['flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm font-semibold',
                                         !jenisAktif?.dapat_ditarik
                                            ? 'cursor-not-allowed border-cream-200 bg-cream-50 text-tanah-300'
                                            : (form.tipe === 'tarik'
                                                ? 'border-bata-500 bg-bata-100 text-bata-700 cursor-pointer'
                                                : 'border-cream-300 bg-white text-tanah-500 cursor-pointer')]"
                            >
                                <input
                                    type="radio"
                                    v-model="form.tipe"
                                    value="tarik"
                                    class="sr-only"
                                    :disabled="!jenisAktif?.dapat_ditarik"
                                />
                                Tarik
                            </label>
                        </div>
                        <p v-if="!jenisAktif?.dapat_ditarik" class="mt-1 text-xs text-tanah-500">
                            {{ jenisAktif?.nama }} tidak bisa ditarik.
                        </p>
                        <InputError class="mt-2" :message="form.errors.tipe" />
                    </div>

                    <div>
                        <InputLabel for="nominal" value="Nominal (Rp)" />
                        <TextInput
                            id="nominal"
                            v-model="form.nominal"
                            type="number"
                            min="1"
                            step="500"
                            class="mt-1 block w-full font-mono"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.nominal" />
                    </div>

                    <div>
                        <InputLabel for="tanggal" value="Tanggal" />
                        <TextInput id="tanggal" v-model="form.tanggal" type="date" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.tanggal" />
                    </div>

                    <div>
                        <InputLabel for="keterangan" value="Keterangan (opsional)" />
                        <TextInput id="keterangan" v-model="form.keterangan" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.keterangan" />
                    </div>

                    <PrimaryButton class="w-full" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
                        Catat transaksi
                    </PrimaryButton>
                </form>
            </section>

            <!-- History -->
            <section class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm lg:col-span-2">
                <header class="border-b border-cream-200 bg-cream-50 px-5 py-3">
                    <h2 class="text-sm font-semibold text-sogan-500">Riwayat transaksi</h2>
                </header>

                <table class="w-full text-sm">
                    <thead class="bg-cream-50 text-left text-xs font-semibold uppercase tracking-wider text-tanah-500">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Jenis</th>
                            <th class="px-4 py-2">Tipe</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200">
                        <tr v-if="transaksi.data.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-tanah-500">
                                Belum ada transaksi. Catat setoran pertama di sebelah kiri.
                            </td>
                        </tr>
                        <tr v-for="t in transaksi.data" :key="t.id" class="hover:bg-cream-50">
                            <td class="whitespace-nowrap px-4 py-2 text-tanah-700">{{ fmtDate(t.tanggal) }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-tanah-700">{{ t.jenis?.nama }}</td>
                            <td class="whitespace-nowrap px-4 py-2">
                                <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold capitalize',
                                               t.tipe === 'setor' ? 'bg-padi-100 text-padi-700' : 'bg-bata-100 text-bata-700']">
                                    {{ t.tipe }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-2 text-right font-mono">
                                <span :class="t.tipe === 'setor' ? 'text-padi-700' : 'text-bata-700'">
                                    {{ t.tipe === 'setor' ? '+' : '−' }}{{ rupiah(t.nominal) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-2 text-right">
                                <button
                                    @click="deleteTransaksi(t)"
                                    class="text-xs text-bata-700/70 hover:text-bata-700 hover:underline"
                                    title="Hapus transaksi"
                                >Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="transaksi.last_page > 1" class="border-t border-cream-200 px-4 py-3 text-sm text-tanah-500">
                    Hal {{ transaksi.current_page }} dari {{ transaksi.last_page }} · {{ transaksi.total }} transaksi.
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
