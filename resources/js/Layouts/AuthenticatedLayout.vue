<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const sidebarOpen = ref(false);

const nav = [
    { label: 'Beranda',  href: '/dashboard', match: 'dashboard' },
    { label: 'Anggota',  href: '/anggota',   match: 'anggota' },
    { label: 'Simpanan', href: '/simpanan',  match: 'simpanan' },
    { label: 'Pinjaman', href: '/pinjaman',  match: 'pinjaman' },
    { label: 'Cicilan',  href: '/cicilan',   match: 'cicilan' },
    { label: 'Laporan',  href: '/laporan',   match: 'laporan' },
];

function isActive(item) {
    const path = page.url.split('?')[0];
    return path === item.href || path.startsWith(item.href + '/');
}

const initials = computed(() => {
    if (!user.value?.name) return '?';
    return user.value.name.split(' ').map(p => p[0]).slice(0, 2).join('').toUpperCase();
});
</script>

<template>
    <div class="flex min-h-screen bg-cream-100 font-sans text-tanah-700">
        <!-- Mobile sidebar backdrop -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-wedel-900/50 backdrop-blur-sm md:hidden"
        />

        <!-- Sidebar -->
        <aside
            :class="['fixed inset-y-0 left-0 z-40 w-60 transform border-r border-cream-200 bg-white transition-transform md:translate-x-0',
                     sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0']"
        >
            <div class="flex h-16 items-center gap-2.5 border-b border-cream-200 px-5">
                <ApplicationLogo class="h-8 w-8" />
                <div>
                    <div class="font-display text-lg font-semibold leading-none text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Sembada</div>
                    <div class="mt-0.5 text-[9px] font-bold tracking-[0.18em] text-sogan-500">KOPERASI</div>
                </div>
            </div>

            <nav class="flex flex-col gap-0.5 p-3">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    @click="sidebarOpen = false"
                    :class="['flex items-center rounded-md px-3 py-2 text-sm transition-colors',
                             isActive(item)
                                ? 'bg-sogan-50 font-semibold text-sogan-500'
                                : 'text-tanah-700 hover:bg-cream-50']"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <div class="absolute inset-x-0 bottom-0 border-t border-cream-200 p-3">
                <Link
                    :href="route('profile.edit')"
                    class="flex items-center gap-3 rounded-md px-2 py-2 hover:bg-cream-50"
                >
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-sogan-500 text-xs font-bold text-cream-50">
                        {{ initials }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-sm font-semibold text-tanah-700">{{ user?.name }}</div>
                        <div class="truncate text-xs text-tanah-500">{{ user?.email }}</div>
                    </div>
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="mt-1 block w-full rounded-md px-3 py-2 text-left text-sm text-tanah-500 hover:bg-cream-50"
                >
                    Keluar
                </Link>
            </div>
        </aside>

        <!-- Main column -->
        <div class="flex min-w-0 flex-1 flex-col md:ml-60">
            <!-- Topbar -->
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-cream-200 bg-white/80 px-4 backdrop-blur sm:px-6">
                <div class="flex items-center gap-3">
                    <button
                        @click="sidebarOpen = true"
                        class="rounded-md p-2 text-tanah-500 hover:bg-cream-50 md:hidden"
                        aria-label="Buka menu"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
                    </button>
                    <slot name="header">
                        <h1 class="font-display text-xl font-semibold text-wedel-900" style="font-variation-settings: 'opsz' 36, 'SOFT' 30;">Sembada Admin</h1>
                    </slot>
                </div>
                <div class="hidden text-xs text-tanah-500 sm:block">
                    Dari anggota, oleh anggota, untuk anggota.
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
