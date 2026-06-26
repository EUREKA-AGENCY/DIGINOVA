<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
