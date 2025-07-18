<template>
  <component
    :is="to ? 'Link' : 'button'"
    :href="to"
    :type="to ? undefined : 'button'"
    class="cta-element"
    :class="[
      variantClasses,
      sizeClasses,
      { 'with-icon': icon, 'full-width': fullWidth }
    ]"
    @click="!to && $emit('click')"
  >
    <i v-if="icon && iconPosition === 'left'" class="bi" :class="`bi-${icon}`"></i>
    
    <span class="cta-text">
      <slot>{{ text }}</slot>
    </span>
    
    <i v-if="icon && iconPosition === 'right'" class="bi" :class="`bi-${icon}`"></i>
  </component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
  text?: string;
  to?: string;
  variant?: 'red' | 'blue' | 'white' | 'outline-red' | 'outline-blue' | 'outline-white';
  size?: 'sm' | 'md' | 'lg';
  icon?: string;
  iconPosition?: 'left' | 'right';
  fullWidth?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'red',
  size: 'md',
  iconPosition: 'right',
  fullWidth: false
});

const emit = defineEmits(['click']);

// Classes dynamiques
const variantClasses = computed(() => {
  const classes = {
    red: 'bg-diginova-red text-white hover:bg-diginova-red/90 focus:ring-diginova-red/50',
    blue: 'bg-diginova-blue text-white hover:bg-diginova-blue/90 focus:ring-diginova-blue/50',
    white: 'bg-white text-diginova-blue hover:bg-gray-50 focus:ring-white/50',
    'outline-red': 'border-2 border-diginova-red text-diginova-red hover:bg-diginova-red/10 focus:ring-diginova-red/50',
    'outline-blue': 'border-2 border-diginova-blue text-diginova-blue hover:bg-diginova-blue/10 focus:ring-diginova-blue/50',
    'outline-white': 'border-2 border-white text-white hover:bg-white/10 focus:ring-white/50'
  };
  return classes[props.variant];
});

const sizeClasses = computed(() => {
  const classes = {
    sm: 'px-4 py-2 text-sm gap-2',
    md: 'px-6 py-3 text-base gap-3',
    lg: 'px-8 py-4 text-lg gap-4'
  };
  return classes[props.size];
});
</script>

<style scoped>
.cta-element {
  @apply diginova-uppercase font-bold rounded-lg transition-all duration-200 ease-in-out 
         focus:outline-none focus:ring-4 focus:ring-opacity-50 inline-flex items-center 
         justify-center text-center whitespace-nowrap;
  
  &.full-width {
    @apply w-full;
  }
  
  &.with-icon {
    @apply inline-flex items-center;
  }

  & .bi {
    @apply flex-shrink-0;
    font-size: 1.2em;
  }
}

/* Animation au hover */
.cta-element:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.cta-element:active {
  transform: translateY(0);
}

/* Spécifique pour la variante rouge */
.bg-diginova-red:hover {
  box-shadow: 0 4px 12px rgba(225, 36, 40, 0.3);
}
</style>
