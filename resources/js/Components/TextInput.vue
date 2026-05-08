<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-lg border-cream-300 bg-white text-tanah-700 placeholder-tanah-300 shadow-warm-xs focus:border-sogan-500 focus:ring-sogan-500"
        v-model="model"
        ref="input"
    />
</template>
