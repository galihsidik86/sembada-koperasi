<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-lg bg-sogan-500 px-4 py-2.5 text-sm font-semibold text-cream-50 shadow-warm-sm transition-colors duration-150 ease-in-out hover:bg-sogan-600 active:bg-sogan-700 focus:outline-none focus:ring-2 focus:ring-sogan-500 focus:ring-offset-2 focus:ring-offset-cream-100 disabled:opacity-50"
    >
        <slot />
    </button>
</template>
