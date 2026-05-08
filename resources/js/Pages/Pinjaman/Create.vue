<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    nomor_pinjaman:   { type: String, required: true },
    anggota_list:     { type: Array,  required: true },
    anggota_terpilih: { type: [Object, null], default: null },
});

const form = useForm({
    nomor_pinjaman:    props.nomor_pinjaman,
    anggota_id:        props.anggota_terpilih?.id ?? '',
    pokok:             '',
    bunga_persen:      12,
    tenor_bulan:       12,
    metode_bunga:      'flat',
    tanggal_pengajuan: new Date().toISOString().slice(0, 10),
    keterangan:        '',
});

const submit = () => form.post(route('pinjaman.store'));

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}

const preview = computed(() => {
    const pokok = Number(form.pokok) || 0;
    const tenor = Number(form.tenor_bulan) || 1;
    const bunga = Number(form.bunga_persen) || 0;

    const pokokPerBulan = pokok / tenor;
    let bungaPerBulan;
    if (form.metode_bunga === 'flat') {
        bungaPerBulan = (pokok * bunga / 100) / 12;
    } else {
        bungaPerBulan = (pokok * bunga / 100) / 12;
    }
    const cicilanPerBulan = pokokPerBulan + bungaPerBulan;
    const totalBunga = form.metode_bunga === 'flat'
        ? bungaPerBulan * tenor
        : (pokok * bunga / 100 / 12) * (tenor + 1) / 2;

    return {
        cicilanPerBulan,
        totalBunga,
        totalBayar: pokok + totalBunga,
    };
});
</script>

<template>
    <Head title="Pengajuan pinjaman" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link href="/pinjaman" class="text-sm text-tanah-500 hover:text-sogan-500">← Pinjaman</Link>
                <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Pengajuan pinjaman</h1>
            </div>
        </template>

        <form class="grid grid-cols-1 gap-6 lg:grid-cols-3" @submit.prevent="submit">
            <div class="space-y-6 lg:col-span-2">
                <fieldset class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-xs">
                    <legend class="px-2 text-sm font-semibold text-sogan-500">Anggota & nomor</legend>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <InputLabel for="nomor_pinjaman" value="Nomor pinjaman" />
                            <TextInput id="nomor_pinjaman" v-model="form.nomor_pinjaman" type="text" class="mt-1 block w-full font-mono" required />
                            <InputError class="mt-2" :message="form.errors.nomor_pinjaman" />
                        </div>
                        <div>
                            <InputLabel for="anggota_id" value="Anggota" />
                            <select
                                id="anggota_id"
                                v-model="form.anggota_id"
                                class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                                required
                            >
                                <option value="" disabled>Pilih anggota…</option>
                                <option v-for="a in anggota_list" :key="a.id" :value="a.id">
                                    {{ a.nomor_anggota }} — {{ a.nama }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.anggota_id" />
                        </div>
                    </div>
                </fieldset>

                <fieldset class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-xs">
                    <legend class="px-2 text-sm font-semibold text-sogan-500">Detail pinjaman</legend>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <InputLabel for="pokok" value="Pokok pinjaman (Rp)" />
                            <TextInput id="pokok" v-model="form.pokok" type="number" min="1" step="100000" class="mt-1 block w-full font-mono" required />
                            <InputError class="mt-2" :message="form.errors.pokok" />
                        </div>
                        <div>
                            <InputLabel for="bunga_persen" value="Bunga per tahun (%)" />
                            <TextInput id="bunga_persen" v-model="form.bunga_persen" type="number" min="0" max="100" step="0.5" class="mt-1 block w-full font-mono" required />
                            <InputError class="mt-2" :message="form.errors.bunga_persen" />
                        </div>
                        <div>
                            <InputLabel for="tenor_bulan" value="Tenor (bulan)" />
                            <TextInput id="tenor_bulan" v-model="form.tenor_bulan" type="number" min="1" max="120" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.tenor_bulan" />
                        </div>
                        <div>
                            <InputLabel value="Metode bunga" />
                            <div class="mt-2 flex gap-4 text-sm">
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" v-model="form.metode_bunga" value="flat" class="text-sogan-500 focus:ring-sogan-500" />
                                    <span>Flat</span>
                                </label>
                                <label class="inline-flex items-center gap-2">
                                    <input type="radio" v-model="form.metode_bunga" value="menurun" class="text-sogan-500 focus:ring-sogan-500" />
                                    <span>Menurun</span>
                                </label>
                            </div>
                            <InputError class="mt-2" :message="form.errors.metode_bunga" />
                        </div>
                        <div>
                            <InputLabel for="tanggal_pengajuan" value="Tanggal pengajuan" />
                            <TextInput id="tanggal_pengajuan" v-model="form.tanggal_pengajuan" type="date" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.tanggal_pengajuan" />
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="keterangan" value="Keterangan / tujuan pinjaman" />
                            <textarea
                                id="keterangan"
                                v-model="form.keterangan"
                                rows="2"
                                class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                                placeholder="mis. Modal usaha warung"
                            />
                            <InputError class="mt-2" :message="form.errors.keterangan" />
                        </div>
                    </div>
                </fieldset>

                <div class="flex items-center gap-3">
                    <PrimaryButton :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
                        Kirim pengajuan
                    </PrimaryButton>
                    <Link href="/pinjaman" class="text-sm text-tanah-500 hover:text-tanah-700">Batal</Link>
                </div>
            </div>

            <!-- Preview -->
            <aside class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
                <h2 class="text-sm font-semibold text-sogan-500">Estimasi cicilan</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-tanah-500">Cicilan per bulan</dt>
                        <dd class="font-mono font-semibold text-wedel-900">{{ rupiah(preview.cicilanPerBulan) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-tanah-500">Total bunga</dt>
                        <dd class="font-mono text-tanah-700">{{ rupiah(preview.totalBunga) }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-cream-200 pt-3">
                        <dt class="text-tanah-500">Total dibayar</dt>
                        <dd class="font-mono font-semibold text-wedel-900">{{ rupiah(preview.totalBayar) }}</dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs text-tanah-500">
                    Estimasi pakai metode <strong class="capitalize">{{ form.metode_bunga }}</strong>.
                    Jadwal pasti dibuat otomatis saat pengajuan disetujui.
                </p>
            </aside>
        </form>
    </AuthenticatedLayout>
</template>
