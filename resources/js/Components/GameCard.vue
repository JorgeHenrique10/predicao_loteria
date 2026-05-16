<template>
    <div class="glass animate-fade-in-up" :style="{ animationDelay: `${index * 120}ms` }">
        <div class="p-5">
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--ms-emerald)">
                        Jogo {{ index + 1 }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background: rgba(255,255,255,0.06); color: var(--ms-text-secondary)">
                        {{ game.length }} dezenas
                    </span>
                </div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                     style="background: linear-gradient(135deg, var(--ms-emerald), var(--ms-emerald-dark)); color: white;">
                    {{ index + 1 }}
                </div>
            </div>

            <!-- Dezenas Grid -->
            <div class="flex flex-wrap gap-2.5 justify-center">
                <LotteryBall
                    v-for="(dezena, idx) in game"
                    :key="dezena"
                    :number="dezena"
                    :highlighted="isTopScore(dezena)"
                    :delay="idx * 60"
                    class="animate-pop-in"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import LotteryBall from './LotteryBall.vue';

const props = defineProps({
    game: {
        type: Array,
        required: true,
    },
    index: {
        type: Number,
        default: 0,
    },
    topScores: {
        type: Array,
        default: () => [],
    },
});

function isTopScore(dezena) {
    return props.topScores.includes(dezena);
}
</script>
