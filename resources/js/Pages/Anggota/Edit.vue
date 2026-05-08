<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AnggotaForm from './Partials/AnggotaForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    anggota: { type: Object, required: true },
});

const form = useForm({
    nomor_anggota: props.anggota.nomor_anggota,
    nik: props.anggota.nik,
    nama: props.anggota.nama,
    panggilan: props.anggota.panggilan ?? '',
    jenis_kelamin: props.anggota.jenis_kelamin,
    tempat_lahir: props.anggota.tempat_lahir ?? '',
    tanggal_lahir: props.anggota.tanggal_lahir ? props.anggota.tanggal_lahir.slice(0, 10) : '',
    alamat: props.anggota.alamat,
    no_telp: props.anggota.no_telp ?? '',
    email: props.anggota.email ?? '',
    pekerjaan: props.anggota.pekerjaan ?? '',
    tanggal_masuk: props.anggota.tanggal_masuk ? props.anggota.tanggal_masuk.slice(0, 10) : '',
    status: props.anggota.status,
});

const submit = () => form.put(route('anggota.update', props.anggota.id));
</script>

<template>
    <Head :title="`Ubah ${anggota.nama}`" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Ubah anggota</h1>
        </template>

        <p class="mb-6 text-sm text-tanah-500">{{ anggota.nomor_anggota }} · {{ anggota.nama }}</p>

        <AnggotaForm
            :form="form"
            submit-label="Simpan perubahan"
            :cancel-href="`/anggota/${anggota.id}`"
            @submit="submit"
        />
    </AuthenticatedLayout>
</template>
