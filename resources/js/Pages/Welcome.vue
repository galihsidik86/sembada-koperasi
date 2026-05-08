<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
});
</script>

<template>
    <Head title="Sembada — Koperasi Simpan Pinjam" />

    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-gradient-to-br from-sogan-700 to-sogan-500 px-6 text-cream-50">
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.07]"
            style="background-image: url('/design-system/assets/patterns/kawung.svg'); background-size: 140px;"
        />

        <div class="relative flex items-center gap-3">
            <ApplicationLogo class="h-14 w-14" />
            <div>
                <div class="font-display text-3xl font-semibold" style="font-variation-settings: 'opsz' 72, 'SOFT' 30; letter-spacing: -0.01em;">Sembada</div>
                <div class="text-[11px] font-bold tracking-[0.2em] text-emas-300">KOPERASI SIMPAN PINJAM</div>
            </div>
        </div>

        <h1 class="relative mt-10 max-w-2xl text-center font-display text-4xl font-semibold leading-tight sm:text-5xl" style="font-variation-settings: 'opsz' 96, 'SOFT' 30; letter-spacing: -0.02em;">
            Dari anggota, oleh anggota, untuk anggota.
        </h1>

        <p class="relative mt-4 max-w-xl text-center text-base text-cream-100/85">
            Kelola simpanan, pinjaman, dan SHU koperasimu di satu tempat — hangat, ramah, dan dapat dipercaya.
        </p>

        <div class="relative mt-10 flex flex-wrap items-center justify-center gap-3">
            <Link
                v-if="canLogin"
                :href="route('login')"
                class="inline-flex items-center justify-center rounded-lg bg-cream-50 px-5 py-3 text-sm font-semibold text-sogan-500 shadow-warm-sm transition hover:bg-white"
            >
                Masuk
            </Link>
            <Link
                v-if="canRegister"
                :href="route('register')"
                class="inline-flex items-center justify-center rounded-lg border border-cream-100/40 px-5 py-3 text-sm font-semibold text-cream-50 transition hover:bg-cream-100/10"
            >
                Daftar pengurus
            </Link>
        </div>

        <a
            href="/design-system/"
            class="relative mt-12 text-xs text-cream-100/70 underline-offset-2 hover:text-cream-50 hover:underline"
        >
            Lihat referensi design system →
        </a>
    </div>
</template>
