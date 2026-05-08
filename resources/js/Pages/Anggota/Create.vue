<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AnggotaForm from './Partials/AnggotaForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    nomor_anggota: { type: String, required: true },
});

const form = useForm({
    nomor_anggota: props.nomor_anggota,
    nik: '',
    nama: '',
    panggilan: '',
    jenis_kelamin: 'L',
    tempat_lahir: '',
    tanggal_lahir: '',
    alamat: '',
    no_telp: '',
    email: '',
    pekerjaan: '',
    tanggal_masuk: new Date().toISOString().slice(0, 10),
    status: 'aktif',
});

const submit = () => form.post(route('anggota.store'));
</script>

<template>
    <Head title="Tambah anggota" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Tambah anggota</h1>
        </template>

        <p class="mb-6 max-w-xl text-sm text-tanah-500">
            Lengkapi data identitas, kontak, dan status keanggotaan. Nomor anggota sudah disiapkan otomatis — bisa diubah kalau perlu.
        </p>

        <AnggotaForm :form="form" submit-label="Simpan anggota" @submit="submit" />
    </AuthenticatedLayout>
</template>
