<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    anggota:            { type: Object, required: true },
    nomor_pinjaman:     { type: String, required: true },
    ada_pinjaman_aktif: { type: Boolean, default: false },
});

const form = useForm({
    pokok: '',
    tenor_bulan: 12,
    keterangan: '',
});

const submit = () => form.post(route('me.pinjaman.store'));

function rupiah(n) {
    return 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
}

const preview = computed(() => {
    const pokok = Number(form.pokok) || 0;
    const tenor = Number(form.tenor_bulan) || 1;
    const bunga = 12; // default policy
    const cicilan = pokok / tenor + (pokok * bunga / 100) / 12;
    return {
        cicilan,
        total: cicilan * tenor,
    };
});
</script>

<template>
    <Head title="Ajukan pinjaman" />

    <MemberLayout>
        <div class="flex items-center gap-2">
            <Link href="/me/pinjaman" class="text-sm text-tanah-500 hover:text-sogan-500">←</Link>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Ajukan pinjaman</h1>
        </div>

        <div v-if="ada_pinjaman_aktif" class="mt-4 rounded-xl border border-emas-300 bg-emas-100/40 p-4">
            <p class="text-sm text-emas-700">
                Kamu masih punya pinjaman yang belum lunas. Tunggu sampai lunas dulu, ya, baru ajukan baru.
            </p>
            <Link href="/me/pinjaman" class="mt-2 inline-block text-sm font-semibold text-sogan-500 hover:underline">
                ← Kembali ke daftar pinjaman
            </Link>
        </div>

        <form v-else class="mt-4 space-y-4" @submit.prevent="submit">
            <p class="text-sm text-tanah-500">
                Bunga 12% per tahun · metode flat. Pengajuan akan ditinjau pengurus dalam 1×24 jam.
            </p>

            <div>
                <InputLabel for="pokok" value="Berapa yang mau dipinjam? (Rp)" />
                <TextInput
                    id="pokok"
                    v-model="form.pokok"
                    type="number"
                    min="100000"
                    step="100000"
                    placeholder="Mis. 2000000"
                    class="mt-1 block w-full font-mono"
                    required
                />
                <InputError class="mt-1" :message="form.errors.pokok" />
                <p class="mt-1 text-xs text-tanah-500">Minimum Rp 100.000.</p>
            </div>

            <div>
                <InputLabel for="tenor_bulan" value="Mau dicicil berapa bulan?" />
                <select
                    id="tenor_bulan"
                    v-model="form.tenor_bulan"
                    class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                    required
                >
                    <option v-for="t in [3, 6, 9, 12, 18, 24, 36]" :key="t" :value="t">{{ t }} bulan</option>
                </select>
                <InputError class="mt-1" :message="form.errors.tenor_bulan" />
            </div>

            <div>
                <InputLabel for="keterangan" value="Buat apa? (opsional)" />
                <textarea
                    id="keterangan"
                    v-model="form.keterangan"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                    placeholder="Mis. Tambahan modal warung"
                />
                <InputError class="mt-1" :message="form.errors.keterangan" />
            </div>

            <!-- Preview -->
            <div v-if="form.pokok" class="rounded-xl border border-cream-200 bg-cream-50 p-4">
                <div class="text-xs uppercase tracking-wider text-tanah-500">Estimasi</div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-sm text-tanah-700">Cicilan per bulan</span>
                    <span class="font-mono text-base font-semibold text-wedel-900">{{ rupiah(preview.cicilan) }}</span>
                </div>
                <div class="mt-1 flex items-baseline justify-between">
                    <span class="text-sm text-tanah-700">Total dibayar</span>
                    <span class="font-mono text-sm font-semibold text-tanah-700">{{ rupiah(preview.total) }}</span>
                </div>
            </div>

            <PrimaryButton class="w-full" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
                Kirim pengajuan
            </PrimaryButton>
        </form>
    </MemberLayout>
</template>
