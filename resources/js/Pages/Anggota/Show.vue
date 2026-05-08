<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashBanner from '@/Components/FlashBanner.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    anggota: { type: Object, required: true },
});

const confirming = ref(false);

function fmtDate(d) {
    if (!d) return '—';
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const dt = new Date(d);
    return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
}

function destroy() {
    router.delete(route('anggota.destroy', props.anggota.id));
}

function buatkanAkun() {
    if (!confirm('Buatkan akun login untuk anggota ini? Sistem akan generate kata sandi sementara.')) return;
    router.post(route('anggota.akun.store', props.anggota.id), {}, { preserveScroll: true });
}

function hapusAkun() {
    if (!confirm('Hapus akun login anggota ini? Anggota tidak bisa login lagi.')) return;
    router.delete(route('anggota.akun.destroy', props.anggota.id), { preserveScroll: true });
}

const statusBadge = {
    'aktif':     'bg-padi-100 text-padi-700',
    'non-aktif': 'bg-emas-100 text-emas-700',
    'keluar':    'bg-bata-100 text-bata-700',
};
</script>

<template>
    <Head :title="anggota.nama" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center gap-3">
                <Link href="/anggota" class="text-sm text-tanah-500 hover:text-sogan-500">← Anggota</Link>
                <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">{{ anggota.nama }}</h1>
                <span :class="['rounded-full px-2 py-0.5 text-xs font-semibold capitalize', statusBadge[anggota.status]]">{{ anggota.status }}</span>
            </div>
        </template>

        <FlashBanner />

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Identity card -->
            <section class="overflow-hidden rounded-xl border border-cream-200 bg-white shadow-warm-sm lg:col-span-2">
                <header class="flex items-center justify-between border-b border-cream-200 bg-cream-50 px-5 py-3">
                    <h2 class="text-sm font-semibold text-sogan-500">Identitas anggota</h2>
                    <Link :href="route('anggota.edit', anggota.id)">
                        <SecondaryButton type="button">Ubah data</SecondaryButton>
                    </Link>
                </header>
                <dl class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Nomor anggota</dt>
                        <dd class="mt-1 font-mono text-sm text-wedel-900">{{ anggota.nomor_anggota }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">NIK</dt>
                        <dd class="mt-1 font-mono text-sm text-wedel-900">{{ anggota.nik }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Panggilan</dt>
                        <dd class="mt-1 text-sm text-tanah-700">{{ anggota.panggilan || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Jenis kelamin</dt>
                        <dd class="mt-1 text-sm text-tanah-700">{{ anggota.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Tempat / tanggal lahir</dt>
                        <dd class="mt-1 text-sm text-tanah-700">
                            {{ anggota.tempat_lahir || '—' }}{{ anggota.tanggal_lahir ? `, ${fmtDate(anggota.tanggal_lahir)}` : '' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Pekerjaan</dt>
                        <dd class="mt-1 text-sm text-tanah-700">{{ anggota.pekerjaan || '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wider text-tanah-500">Alamat</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm text-tanah-700">{{ anggota.alamat }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Side info -->
            <section class="space-y-6">
                <div class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
                    <h2 class="text-sm font-semibold text-sogan-500">Kontak</h2>
                    <dl class="mt-3 space-y-3 text-sm">
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-tanah-500">No. telepon</dt>
                            <dd class="mt-1 text-tanah-700">{{ anggota.no_telp || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-tanah-500">Email</dt>
                            <dd class="mt-1 text-tanah-700">{{ anggota.email || '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
                    <h2 class="text-sm font-semibold text-sogan-500">Keanggotaan</h2>
                    <dl class="mt-3 space-y-3 text-sm">
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-tanah-500">Tanggal masuk</dt>
                            <dd class="mt-1 text-tanah-700">{{ fmtDate(anggota.tanggal_masuk) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-xl border border-cream-200 bg-white p-5 shadow-warm-sm">
                    <h2 class="text-sm font-semibold text-sogan-500">Akun login anggota</h2>
                    <div v-if="anggota.user" class="mt-3 space-y-2 text-sm">
                        <p class="text-tanah-700">
                            Sudah punya akun: <span class="font-mono text-xs">{{ anggota.user.email }}</span>
                        </p>
                        <button
                            @click="hapusAkun"
                            class="text-xs text-bata-700/80 hover:text-bata-700 hover:underline"
                        >Hapus akun login</button>
                    </div>
                    <div v-else class="mt-3 space-y-2">
                        <p class="text-xs text-tanah-500">
                            Anggota belum bisa login ke aplikasi. Buatkan akun supaya bisa cek saldo, ajukan pinjaman, dan bayar cicilan sendiri.
                        </p>
                        <PrimaryButton type="button" @click="buatkanAkun" class="w-full">Buatkan akun login</PrimaryButton>
                    </div>
                </div>

                <div class="rounded-xl border border-bata-300 bg-bata-100/50 p-5">
                    <h2 class="text-sm font-semibold text-bata-700">Hapus anggota</h2>
                    <p class="mt-1 text-xs text-bata-700/85">
                        Sekali dihapus, data anggota tidak bisa dikembalikan. Pakai status <strong>keluar</strong> kalau cuma ingin nonaktifkan.
                    </p>
                    <div class="mt-3">
                        <DangerButton v-if="!confirming" @click="confirming = true">Hapus</DangerButton>
                        <div v-else class="space-y-2">
                            <p class="text-xs text-bata-700">Yakin? Tindakan ini permanen.</p>
                            <div class="flex gap-2">
                                <DangerButton @click="destroy">Ya, hapus</DangerButton>
                                <SecondaryButton type="button" @click="confirming = false">Batal</SecondaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
