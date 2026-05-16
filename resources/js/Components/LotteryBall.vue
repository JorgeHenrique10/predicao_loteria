<template>
    <div
        class="lottery-ball"
        :class="{ highlighted: highlighted, gold: gold }"
        :style="{ animationDelay: `${delay}ms` }"
        :title="`Dezena ${number}`"
    >
        {{ number }}
    </div>
</template>

<script setup>
defineProps({
    number: {
        type: String,
        required: true,
    },
    highlighted: {
        type: Boolean,
        default: false,
    },
    gold: {
        type: Boolean,
        default: false,
    },
    delay: {
        type: Number,
        default: 0,
    },
});
</script>
