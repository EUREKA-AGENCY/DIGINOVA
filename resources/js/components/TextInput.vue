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
        class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
        v-model="model"
        ref="input"
    />
</template>
