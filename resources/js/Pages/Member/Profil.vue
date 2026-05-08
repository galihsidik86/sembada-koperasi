<script setup>
import MemberLayout from '@/Layouts/MemberLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    anggota: { type: Object, required: true },
    user:    { type: Object, required: true },
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitPassword = () => passwordForm.put(route('me.password'), {
    onSuccess: () => passwordForm.reset(),
});

function fmtDate(d) {
    if (!d) return '—';
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}
</script>

<template>
    <Head title="Profil" />

    <MemberLayout>
        <FlashBanner />

        <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Profil</h1>

        <!-- Identitas (read-only) -->
        <section class="mt-4 rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
            <h2 class="text-sm font-semibold text-sogan-500">Data anggota</h2>
            <dl class="mt-3 space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-tanah-500">Nomor anggota</dt>
                    <dd class="font-mono text-tanah-700">{{ anggota.nomor_anggota }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-tanah-500">Nama</dt>
                    <dd class="text-tanah-700">{{ anggota.nama }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-tanah-500">No. telp</dt>
                    <dd class="text-tanah-700">{{ anggota.no_telp || '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-tanah-500">Tanggal masuk</dt>
                    <dd class="text-tanah-700">{{ fmtDate(anggota.tanggal_masuk) }}</dd>
                </div>
            </dl>
            <p class="mt-4 text-xs text-tanah-500">
                Mau update data? Hubungi pengurus koperasi, ya.
            </p>
        </section>

        <!-- Akun -->
        <section class="mt-4 rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
            <h2 class="text-sm font-semibold text-sogan-500">Akun login</h2>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-tanah-500">Email</dt>
                    <dd class="font-mono text-xs text-tanah-700">{{ user.email }}</dd>
                </div>
            </dl>
        </section>

        <!-- Ganti password -->
        <section class="mt-4 rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
            <h2 class="text-sm font-semibold text-sogan-500">Ganti kata sandi</h2>
            <form class="mt-3 space-y-3" @submit.prevent="submitPassword">
                <div>
                    <InputLabel for="current_password" value="Kata sandi saat ini" />
                    <TextInput id="current_password" v-model="passwordForm.current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" required />
                    <InputError class="mt-1" :message="passwordForm.errors.current_password" />
                </div>
                <div>
                    <InputLabel for="password" value="Kata sandi baru" />
                    <TextInput id="password" v-model="passwordForm.password" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                    <InputError class="mt-1" :message="passwordForm.errors.password" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" value="Ulangi kata sandi baru" />
                    <TextInput id="password_confirmation" v-model="passwordForm.password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" required />
                    <InputError class="mt-1" :message="passwordForm.errors.password_confirmation" />
                </div>
                <PrimaryButton class="w-full" :disabled="passwordForm.processing" :class="{ 'opacity-50': passwordForm.processing }">Simpan kata sandi baru</PrimaryButton>
            </form>
        </section>
    </MemberLayout>
</template>
