<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-lg border border-cream-300 bg-white px-4 py-2.5 text-sm font-semibold text-tanah-700 shadow-warm-xs transition-colors duration-150 ease-in-out hover:bg-cream-50 focus:outline-none focus:ring-2 focus:ring-sogan-500 focus:ring-offset-2 focus:ring-offset-cream-100 disabled:opacity-50"
    >
        <slot />
    </button>
</template>
