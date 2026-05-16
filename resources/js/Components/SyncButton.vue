<template>
    <button
        class="btn-primary"
        :disabled="loading"
        @click="handleSync"
    >
        <!-- Loading spinner -->
        <svg v-if="loading" class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-linecap="round" opacity="0.3"/>
            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
        </svg>
        <!-- Sync icon -->
        <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
        </svg>
        <span>{{ loading ? 'Sincronizando...' : 'Sincronizar Resultados' }}</span>
    </button>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    syncUrl: {
        type: String,
        default: '/megasena/sync',
    },
});

const loading = ref(false);
let debounceTimer = null;

function handleSync() {
    if (loading.value) return;

    // Debounce de 2 segundos (RN03)
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    loading.value = true;

    debounceTimer = setTimeout(() => {
        router.post(props.syncUrl, {}, {
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        });
    }, 300);
}
</script>
