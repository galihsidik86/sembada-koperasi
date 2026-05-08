<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const dismissed = ref(false);

const success = computed(() => page.props.flash?.success);
const error   = computed(() => page.props.flash?.error);

watch([success, error], () => {
    dismissed.value = false;
});
</script>

<template>
    <div v-if="!dismissed && (success || error)" class="mb-4">
        <div
            v-if="success"
            class="flex items-start gap-3 rounded-lg border border-padi-300 bg-padi-100 px-4 py-3 text-sm text-padi-700"
        >
            <span class="font-semibold">Berhasil.</span>
            <span class="flex-1">{{ success }}</span>
            <button @click="dismissed = true" class="text-padi-700/70 hover:text-padi-700">×</button>
        </div>

        <div
            v-if="error"
            class="flex items-start gap-3 rounded-lg border border-bata-300 bg-bata-100 px-4 py-3 text-sm text-bata-700"
        >
            <span class="font-semibold">Gagal.</span>
            <span class="flex-1">{{ error }}</span>
            <button @click="dismissed = true" class="text-bata-700/70 hover:text-bata-700">×</button>
        </div>
    </div>
</template>
