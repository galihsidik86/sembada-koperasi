<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    form: { type: Object, required: true },
    submitLabel: { type: String, default: 'Simpan' },
    cancelHref: { type: String, default: '/anggota' },
});

defineEmits(['submit']);
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">
        <!-- Identitas -->
        <fieldset class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-xs">
            <legend class="px-2 text-sm font-semibold text-sogan-500">Identitas</legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="nomor_anggota" value="Nomor anggota" />
                    <TextInput id="nomor_anggota" v-model="form.nomor_anggota" type="text" class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.nomor_anggota" />
                </div>
                <div>
                    <InputLabel for="nik" value="NIK (16 digit)" />
                    <TextInput id="nik" v-model="form.nik" type="text" maxlength="16" class="mt-1 block w-full font-mono" required />
                    <InputError class="mt-2" :message="form.errors.nik" />
                </div>
                <div>
                    <InputLabel for="nama" value="Nama lengkap" />
                    <TextInput id="nama" v-model="form.nama" type="text" class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.nama" />
                </div>
                <div>
                    <InputLabel for="panggilan" value="Panggilan" />
                    <TextInput id="panggilan" v-model="form.panggilan" type="text" class="mt-1 block w-full" placeholder="mis. Bu Sari" />
                    <InputError class="mt-2" :message="form.errors.panggilan" />
                </div>
                <div>
                    <InputLabel value="Jenis kelamin" />
                    <div class="mt-2 flex gap-4 text-sm">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" v-model="form.jenis_kelamin" value="L" class="text-sogan-500 focus:ring-sogan-500" />
                            <span>Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" v-model="form.jenis_kelamin" value="P" class="text-sogan-500 focus:ring-sogan-500" />
                            <span>Perempuan</span>
                        </label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.jenis_kelamin" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <InputLabel for="tempat_lahir" value="Tempat lahir" />
                        <TextInput id="tempat_lahir" v-model="form.tempat_lahir" type="text" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.tempat_lahir" />
                    </div>
                    <div>
                        <InputLabel for="tanggal_lahir" value="Tanggal lahir" />
                        <TextInput id="tanggal_lahir" v-model="form.tanggal_lahir" type="date" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.tanggal_lahir" />
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Kontak -->
        <fieldset class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-xs">
            <legend class="px-2 text-sm font-semibold text-sogan-500">Kontak</legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel for="alamat" value="Alamat" />
                    <textarea
                        id="alamat"
                        v-model="form.alamat"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.alamat" />
                </div>
                <div>
                    <InputLabel for="no_telp" value="No. telepon" />
                    <TextInput id="no_telp" v-model="form.no_telp" type="tel" class="mt-1 block w-full" placeholder="08xx" />
                    <InputError class="mt-2" :message="form.errors.no_telp" />
                </div>
                <div>
                    <InputLabel for="email" value="Email (opsional)" />
                    <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <InputLabel for="pekerjaan" value="Pekerjaan" />
                    <TextInput id="pekerjaan" v-model="form.pekerjaan" type="text" class="mt-1 block w-full" placeholder="mis. Pedagang, Petani, Karyawan" />
                    <InputError class="mt-2" :message="form.errors.pekerjaan" />
                </div>
            </div>
        </fieldset>

        <!-- Status keanggotaan -->
        <fieldset class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-xs">
            <legend class="px-2 text-sm font-semibold text-sogan-500">Status keanggotaan</legend>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="tanggal_masuk" value="Tanggal masuk" />
                    <TextInput id="tanggal_masuk" v-model="form.tanggal_masuk" type="date" class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="form.errors.tanggal_masuk" />
                </div>
                <div>
                    <InputLabel for="status" value="Status" />
                    <select
                        id="status"
                        v-model="form.status"
                        class="mt-1 block w-full rounded-lg border-cream-300 bg-white text-tanah-700 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
                        required
                    >
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-aktif</option>
                        <option value="keluar">Keluar</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.status" />
                </div>
            </div>
        </fieldset>

        <div class="flex items-center gap-3">
            <PrimaryButton :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
                {{ submitLabel }}
            </PrimaryButton>
            <Link :href="cancelHref" class="text-sm text-tanah-500 hover:text-tanah-700">Batal</Link>
        </div>
    </form>
</template>
