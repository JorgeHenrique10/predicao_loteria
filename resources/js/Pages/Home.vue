<template>
    <Head title="Menu Principal" />

    <div class="min-h-screen flex flex-col items-center justify-center p-4 md:p-8">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center animate-float"
                     style="background: linear-gradient(135deg, var(--ms-emerald), var(--lf-purple));">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
                        <circle cx="12" cy="12" r="3"/>
                        <circle cx="12" cy="12" r="9" stroke-dasharray="4 3"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2" style="color: var(--ms-text-primary)">
                Predição
                <span class="bg-gradient-to-r from-emerald-400 via-violet-400 to-pink-400 bg-clip-text text-transparent">
                    Loteria
                </span>
            </h1>
            <p class="text-sm md:text-base" style="color: var(--ms-text-muted)">
                Escolha sua loteria e gere predições inteligentes
            </p>
        </div>

        <!-- Lottery Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl animate-fade-in-up" style="animation-delay: 150ms">
            <!-- Mega-Sena Card -->
            <Link href="/megasena" class="lottery-menu-card megasena" id="card-megasena">
                <div class="lottery-menu-icon" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(245,158,11,0.1));">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--ms-emerald)" stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="3"/>
                        <circle cx="12" cy="12" r="9" stroke-dasharray="4 3"/>
                    </svg>
                </div>

                <h2 class="text-xl font-bold mb-1" style="color: var(--ms-text-primary)">
                    Mega-Sena
                </h2>
                <p class="text-sm mb-4" style="color: var(--ms-text-muted)">
                    6 dezenas de 01 a 60
                </p>

                <!-- Stats -->
                <div class="flex items-center gap-3">
                    <div class="glass-sm px-3 py-1.5 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full" :style="{ background: megaSenaTotal > 0 ? 'var(--ms-emerald)' : 'var(--ms-red)' }"></div>
                        <span class="text-xs font-medium" style="color: var(--ms-text-secondary)">
                            {{ megaSenaTotal.toLocaleString('pt-BR') }} sorteios
                        </span>
                    </div>
                    <div class="glass-sm px-3 py-1.5">
                        <span class="text-xs" style="color: var(--ms-text-muted)">Qua · Sáb</span>
                    </div>
                </div>

                <!-- Arrow -->
                <div class="lottery-menu-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ms-text-muted)" stroke-width="2" stroke-linecap="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </Link>

            <!-- Lotofácil Card -->
            <Link href="/lotofacil" class="lottery-menu-card lotofacil" id="card-lotofacil">
                <div class="lottery-menu-icon" style="background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(236,72,153,0.1));">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--lf-purple)" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>

                <h2 class="text-xl font-bold mb-1" style="color: var(--ms-text-primary)">
                    Lotofácil
                </h2>
                <p class="text-sm mb-4" style="color: var(--ms-text-muted)">
                    15 dezenas de 01 a 25
                </p>

                <!-- Stats -->
                <div class="flex items-center gap-3">
                    <div class="glass-sm px-3 py-1.5 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full" :style="{ background: lotofacilTotal > 0 ? 'var(--lf-purple)' : 'var(--ms-red)' }"></div>
                        <span class="text-xs font-medium" style="color: var(--ms-text-secondary)">
                            {{ lotofacilTotal.toLocaleString('pt-BR') }} sorteios
                        </span>
                    </div>
                    <div class="glass-sm px-3 py-1.5">
                        <span class="text-xs" style="color: var(--ms-text-muted)">Seg · Qua · Sex</span>
                    </div>
                </div>

                <!-- Arrow -->
                <div class="lottery-menu-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ms-text-muted)" stroke-width="2" stroke-linecap="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </Link>
        </div>

        <!-- Footer -->
        <footer class="mt-16 text-center animate-fade-in-up" style="animation-delay: 300ms">
            <p class="text-xs" style="color: var(--ms-text-muted)">
                Sistema para fins de entretenimento e estudo estatístico. Não garante ganhos reais.
            </p>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    megaSenaTotal: {
        type: Number,
        default: 0,
    },
    lotofacilTotal: {
        type: Number,
        default: 0,
    },
});
</script>
