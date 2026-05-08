<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);

const tabs = [
    { label: 'Beranda',  href: '/me',          match: '/me' },
    { label: 'Simpanan', href: '/me/simpanan', match: '/me/simpanan' },
    { label: 'Pinjaman', href: '/me/pinjaman', match: '/me/pinjaman' },
    { label: 'Profil',   href: '/me/profil',   match: '/me/profil' },
];

function isActive(tab) {
    const path = page.url.split('?')[0];
    if (tab.match === '/me') return path === '/me';
    return path === tab.match || path.startsWith(tab.match + '/');
}

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="min-h-screen bg-cream-100 font-sans text-tanah-700">
        <!-- Mobile-style container, centered on desktop -->
        <div class="relative mx-auto flex min-h-screen max-w-md flex-col bg-cream-100 shadow-warm-md md:my-6 md:min-h-0 md:max-h-[calc(100vh-3rem)] md:rounded-3xl md:overflow-hidden">
            <!-- Sticky header -->
            <header class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-cream-200 bg-cream-100/90 px-4 backdrop-blur">
                <div class="flex items-center gap-2">
                    <ApplicationLogo class="h-7 w-7" />
                    <slot name="title">
                        <span class="font-display text-base font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 24, 'SOFT' 30;">Sembada</span>
                    </slot>
                </div>
                <button
                    @click="logout"
                    class="rounded-md px-2 py-1 text-xs text-tanah-500 hover:bg-cream-200 hover:text-bata-700"
                    title="Keluar"
                >
                    Keluar
                </button>
            </header>

            <!-- Main scroll area -->
            <main class="flex-1 overflow-y-auto px-4 pb-24 pt-4">
                <slot />
            </main>

            <!-- Fixed bottom nav -->
            <nav class="absolute inset-x-0 bottom-0 z-10 flex h-16 items-stretch border-t border-cream-200 bg-white shadow-warm-sm">
                <Link
                    v-for="t in tabs"
                    :key="t.href"
                    :href="t.href"
                    :class="['flex flex-1 flex-col items-center justify-center gap-0.5 text-[11px] transition-colors',
                             isActive(t) ? 'font-semibold text-sogan-500' : 'text-tanah-500 hover:text-tanah-700']"
                >
                    <span :class="['h-1 w-6 rounded-full', isActive(t) ? 'bg-sogan-500' : 'bg-transparent']" />
                    {{ t.label }}
                </Link>
            </nav>
        </div>
    </div>
</template>
