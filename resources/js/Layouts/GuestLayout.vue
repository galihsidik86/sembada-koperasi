<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center justify-center bg-cream-100 px-4 py-10">
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.04]"
            style="background-image: url('/design-system/assets/patterns/kawung.svg'); background-size: 120px;"
        />

        <div class="relative flex items-center gap-3">
            <Link href="/" class="flex items-center gap-3">
                <ApplicationLogo class="h-12 w-12" />
                <div>
                    <div class="font-display text-2xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30; letter-spacing: -0.01em;">Sembada</div>
                    <div class="text-[10px] font-bold tracking-[0.18em] text-sogan-500">KOPERASI</div>
                </div>
            </Link>
        </div>

        <div class="relative mt-8 w-full overflow-hidden rounded-2xl bg-white px-8 py-8 shadow-warm-md ring-1 ring-cream-200 sm:max-w-md">
            <slot />
        </div>

        <p class="relative mt-6 text-xs text-tanah-500">
            Dari anggota, oleh anggota, untuk anggota.
        </p>
    </div>
</template>
