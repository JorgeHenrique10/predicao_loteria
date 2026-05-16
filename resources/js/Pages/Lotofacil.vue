<template>
    <Head title="Lotofácil Predictor" />

    <!-- Toast Notifications -->
    <Transition name="toast">
        <div v-if="showToast" :class="['toast', toastType === 'success' ? 'toast-success' : 'toast-error']">
            <div class="flex items-center gap-2">
                <svg v-if="toastType === 'success'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M20 6L9 17l-5-5"/></svg>
                <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span>{{ toastMessage }}</span>
            </div>
        </div>
    </Transition>

    <div class="min-h-screen p-4 md:p-8 max-w-7xl mx-auto theme-lotofacil">
        <!-- ===== HEADER ===== -->
        <header class="mb-10 animate-fade-in-up">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <!-- Back to Menu -->
                    <Link href="/" class="inline-flex items-center gap-1.5 text-sm font-medium mb-3 transition-colors hover:opacity-80" style="color: var(--ms-text-muted)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Voltar ao Menu
                    </Link>
                    <div class="flex items-center gap-3 mb-2">
                        <!-- Logo icon -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center animate-float"
                             style="background: linear-gradient(135deg, var(--lf-purple), var(--lf-pink));">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight" style="color: var(--ms-text-primary)">
                            Lotofácil
                            <span class="bg-gradient-to-r from-violet-400 to-pink-400 bg-clip-text text-transparent">
                                Predictor
                            </span>
                        </h1>
                    </div>
                    <p class="text-sm" style="color: var(--ms-text-muted)">
                        Análise estatística e predição inteligente de números
                    </p>
                </div>

                <!-- Status + Sync -->
                <div class="flex items-center gap-3">
                    <div v-if="latestSorteio" class="glass-sm px-4 py-2.5 flex items-center gap-2.5">
                        <div class="w-2.5 h-2.5 rounded-full animate-pulse-glow" style="background: var(--lf-purple)"></div>
                        <div>
                            <div class="text-xs font-medium" style="color: var(--ms-text-secondary)">Último Concurso</div>
                            <div class="text-sm font-bold" style="color: var(--ms-text-primary)">
                                Nº {{ latestSorteio.concurso }}
                                <span class="font-normal" style="color: var(--ms-text-muted)">· {{ latestSorteio.data }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="glass-sm px-4 py-2.5 flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full" style="background: var(--ms-red)"></div>
                        <span class="text-sm" style="color: var(--ms-text-secondary)">Sem dados — sincronize primeiro</span>
                    </div>

                    <SyncButton syncUrl="/lotofacil/sync" />
                </div>
            </div>
        </header>

        <!-- ===== STATS ROW ===== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 animate-fade-in-up" style="animation-delay: 100ms">
            <!-- Total sorteios -->
            <div class="glass-sm p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(139,92,246,0.12);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--lf-purple)" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold" style="color: var(--ms-text-primary)">{{ totalSorteios.toLocaleString('pt-BR') }}</div>
                    <div class="text-xs" style="color: var(--ms-text-muted)">Sorteios na base</div>
                </div>
            </div>

            <!-- Último sorteio dezenas -->
            <div class="glass-sm p-4" v-if="latestSorteio">
                <div class="text-xs font-medium mb-2" style="color: var(--ms-text-muted)">Último Resultado</div>
                <div class="flex gap-1 flex-wrap">
                    <div v-for="d in latestSorteio.dezenas" :key="d"
                         class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold"
                         style="background: linear-gradient(145deg, var(--lf-purple), var(--lf-purple-dark)); color: white;">
                        {{ d }}
                    </div>
                </div>
            </div>
            <div v-else class="glass-sm p-4">
                <div class="text-xs font-medium mb-2" style="color: var(--ms-text-muted)">Último Resultado</div>
                <div class="text-sm" style="color: var(--ms-text-secondary)">—</div>
            </div>

            <!-- Status acumulou -->
            <div class="glass-sm p-4 flex items-center gap-4" v-if="latestSorteio">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                     :style="{ background: latestSorteio.acumulou ? 'rgba(236,72,153,0.12)' : 'rgba(139,92,246,0.12)' }">
                    <svg v-if="latestSorteio.acumulou" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--lf-pink)" stroke-width="2" stroke-linecap="round">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <svg v-else width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--lf-purple)" stroke-width="2" stroke-linecap="round">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-bold" :style="{ color: latestSorteio.acumulou ? 'var(--lf-pink)' : 'var(--lf-purple)' }">
                        {{ latestSorteio.acumulou ? 'ACUMULOU!' : 'HOUVE GANHADOR' }}
                    </div>
                    <div class="text-xs" style="color: var(--ms-text-muted)">Concurso {{ latestSorteio.concurso }}</div>
                </div>
            </div>
            <div v-else class="glass-sm p-4">
                <div class="text-xs" style="color: var(--ms-text-muted)">Status do prêmio</div>
                <div class="text-sm" style="color: var(--ms-text-secondary)">—</div>
            </div>
        </div>

        <!-- ===== PREDICTION FORM ===== -->
        <div class="glass p-6 mb-8 animate-fade-in-up" style="animation-delay: 200ms">
            <h2 class="text-lg font-bold mb-1" style="color: var(--ms-text-primary)">
                🎯 Gerar Predição
            </h2>
            <p class="text-sm mb-6" style="color: var(--ms-text-muted)">
                Configure sua estratégia e gere jogos baseados em análise estatística
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <!-- Slider: Quantidade de dezenas -->
                <div>
                    <label class="block text-sm font-medium mb-3" style="color: var(--ms-text-secondary)">
                        Dezenas por jogo
                    </label>
                    <div class="flex items-center gap-3">
                        <input
                            type="range"
                            v-model.number="form.qtd_dezenas"
                            min="15"
                            max="20"
                            class="flex-1"
                            id="dezenas-slider"
                        />
                        <div class="w-12 h-10 rounded-lg flex items-center justify-center font-bold text-lg"
                             style="background: rgba(139,92,246,0.12); color: var(--lf-purple);">
                            {{ form.qtd_dezenas }}
                        </div>
                    </div>
                    <div class="flex justify-between text-xs mt-1" style="color: var(--ms-text-muted)">
                        <span>15</span>
                        <span>20</span>
                    </div>
                </div>

                <!-- Input: Quantidade de jogos -->
                <div>
                    <label for="qtd-jogos" class="block text-sm font-medium mb-3" style="color: var(--ms-text-secondary)">
                        Quantidade de jogos
                    </label>
                    <input
                        id="qtd-jogos"
                        type="number"
                        v-model.number="form.qtd_jogos"
                        min="1"
                        max="10"
                        class="w-full"
                    />
                </div>

                <!-- Botão gerar -->
                <div>
                    <button
                        class="btn-primary w-full justify-center text-base"
                        :disabled="predicting || totalSorteios === 0"
                        @click="handlePredict"
                        id="btn-predict"
                    >
                        <svg v-if="predicting" class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity="0.3"/>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                        <span>{{ predicting ? 'Gerando...' : 'Gerar Predição' }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== GENERATED RESULTS ===== -->
        <div v-if="latestPrediction" class="mb-8">
            <div class="flex items-center gap-3 mb-5">
                <h2 class="text-lg font-bold" style="color: var(--ms-text-primary)">
                    🎰 Jogos Gerados
                </h2>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                      style="background: rgba(139,92,246,0.12); color: var(--lf-purple);">
                    {{ latestPrediction.jogos.length }} {{ latestPrediction.jogos.length === 1 ? 'jogo' : 'jogos' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <GameCard
                    v-for="(game, idx) in latestPrediction.jogos"
                    :key="idx"
                    :game="game"
                    :index="idx"
                    :topScores="topScoredDezenas"
                    accentColor="var(--lf-purple)"
                />
            </div>
        </div>

        <!-- ===== HISTORY ===== -->
        <div v-if="recentPredicoes.length > 0" class="animate-fade-in-up" style="animation-delay: 300ms">
            <h2 class="text-lg font-bold mb-4" style="color: var(--ms-text-primary)">
                📋 Histórico de Predições
            </h2>

            <div class="space-y-3">
                <div v-for="pred in displayedPredicoes" :key="pred.id" class="glass-sm p-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="text-xs font-medium px-2 py-1 rounded"
                                 style="background: rgba(255,255,255,0.06); color: var(--ms-text-secondary)">
                                {{ pred.created_at }}
                            </div>
                            <span class="text-xs" style="color: var(--ms-text-muted)">
                                {{ pred.jogos.length }} {{ pred.jogos.length === 1 ? 'jogo' : 'jogos' }} · {{ pred.qtd_dezenas }} dezenas
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            <template v-for="(game, gi) in pred.jogos" :key="gi">
                                <div class="flex gap-0.5 mr-3">
                                    <div v-for="d in game" :key="d"
                                         class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold"
                                         style="background: rgba(255,255,255,0.06); color: var(--ms-text-secondary)">
                                        {{ d }}
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== FOOTER ===== -->
        <footer class="mt-16 pb-8 text-center">
            <p class="text-xs" style="color: var(--ms-text-muted)">
                Sistema para fins de entretenimento e estudo estatístico. Não garante ganhos reais.
            </p>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import GameCard from '@/Components/GameCard.vue';
import SyncButton from '@/Components/SyncButton.vue';

// Props from Inertia
const props = defineProps({
    latestSorteio: Object,
    totalSorteios: {
        type: Number,
        default: 0,
    },
    recentPredicoes: {
        type: Array,
        default: () => [],
    },
});

// Form state
const form = ref({
    qtd_dezenas: 15,
    qtd_jogos: 3,
});

const predicting = ref(false);

// Toast
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('success');
let toastTimer = null;

// Computed
const latestPrediction = computed(() => {
    if (props.recentPredicoes.length > 0) {
        return props.recentPredicoes[0];
    }
    return null;
});

const displayedPredicoes = computed(() => {
    // Show all except the first one (which is displayed as cards)
    return props.recentPredicoes.slice(1);
});

const topScoredDezenas = computed(() => {
    // Highlight the first 10 dezenas that appear most in the latest prediction
    if (!latestPrediction.value) return [];
    const allDezenas = latestPrediction.value.jogos.flat();
    const freq = {};
    allDezenas.forEach(d => { freq[d] = (freq[d] || 0) + 1; });
    return Object.entries(freq)
        .sort(([,a], [,b]) => b - a)
        .slice(0, 10)
        .map(([d]) => d);
});

// Flash messages
const page = usePage();

function checkFlash() {
    const flash = page.props.flash;
    if (flash?.success) {
        showNotification(flash.success, 'success');
    }
    if (flash?.error) {
        showNotification(flash.error, 'error');
    }
}

function showNotification(message, type = 'success') {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        showToast.value = false;
    }, 5000);
}

// Watch for flash changes (Inertia navigations)
watch(() => page.props.flash, () => {
    checkFlash();
}, { deep: true });

onMounted(() => {
    checkFlash();
});

// Predict handler
function handlePredict() {
    if (predicting.value) return;

    predicting.value = true;
    router.post('/lotofacil/predict', {
        qtd_dezenas: form.value.qtd_dezenas,
        qtd_jogos: form.value.qtd_jogos,
    }, {
        preserveScroll: true,
        onFinish: () => {
            predicting.value = false;
        },
    });
}
</script>
