<template>
  <div class="section-title" :class="[`text-${align}`, { 'with-subtitle': subtitle }]">
    <!-- Ligne décorative rouge -->
    <div 
      v-if="decorativeLine"
      class="decorative-line"
      :class="`line-${align}`"
    ></div>
    
    <!-- Titre principal -->
    <h2 class="title">
      <span class="title-text">{{ title }}</span>
      <span 
        v-if="accentWord"
        class="accent-word"
      >{{ accentWord }}</span>
    </h2>
    
    <!-- Sous-titre optionnel -->
    <p 
      v-if="subtitle"
      class="subtitle"
    >
      {{ subtitle }}
    </p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string;
  subtitle?: string;
  align?: 'left' | 'center' | 'right';
  decorativeLine?: boolean;
  accentWord?: string;
}

withDefaults(defineProps<Props>(), {
  align: 'left',
  decorativeLine: true,
});
</script>

<style scoped>
.section-title {
  @apply mb-10;
  
  &.text-center {
    @apply mx-auto;
  }
  
  &.text-right {
    @apply ml-auto;
  }
}

.decorative-line {
  @apply h-1 w-16 bg-diginova-red mb-4;
  
  &.line-center {
    @apply mx-auto;
  }
  
  &.line-right {
    @apply ml-auto;
  }
}

.title {
  @apply diginova-uppercase text-3xl md:text-4xl font-bold text-diginova-blue mb-2;
  
  .title-text {
    @apply relative;
  }
  
  .accent-word {
    @apply text-diginova-red;
  }
}

.subtitle {
  @apply text-lg text-gray-600 max-w-3xl;
  
  .text-center & {
    @apply mx-auto;
  }
  
  .text-right & {
    @apply ml-auto;
  }
  
  .with-subtitle & {
    @apply mt-3;
  }
}
</style>