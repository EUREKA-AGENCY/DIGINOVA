<template>
  <button
    :class="[
      'relative group inline-flex items-center justify-center overflow-hidden transition-all duration-300 ease-out rounded-lg',
      'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-diginova-red/50',
      sizeClasses[variant][size],
      variantClasses[variant],
      disabled ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg'
    ]"
    :disabled="disabled"
    @click="$emit('click')"
  >
    <!-- Fond animé -->
    <span 
      :class="[
        'absolute inset-0 transform transition-all duration-500 ease-in-out',
        'group-hover:scale-100 group-hover:opacity-100',
        variantBackground[variant]
      ]"
      :style="{
        transform: 'scale(0)',
        opacity: 0
      }"
    ></span>

    <!-- Contenu -->
    <span class="relative z-10 flex items-center">
      <!-- Icône (si fournie) -->
      <i 
        v-if="icon" 
        :class="[
          'bi',
          icon,
          sizeIcon[size],
          { 'mr-2': text, 'ml-2': iconRight && text }
        ]"
      ></i>
      
      <!-- Texte -->
      <span v-if="text" class="whitespace-nowrap">{{ text }}</span>
    </span>

    <!-- Effet de bordure animée -->
    <span 
      v-if="variant.includes('outline')"
      :class="[
        'absolute inset-0 border-2 rounded-lg pointer-events-none transition-all duration-700 ease-in-out',
        variantBorder[variant]
      ]"
      style="clip-path: polygon(0% 0%, 0% 100%, 100% 100%, 100% 0%)"
    ></span>
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  text: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'red', // 'red' | 'outline-red' | 'outline-white' | 'white'
    validator: value => ['red', 'outline-red', 'outline-white', 'white'].includes(value)
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg'
    validator: value => ['sm', 'md', 'lg'].includes(value)
  },
  icon: {
    type: String,
    default: ''
  },
  iconRight: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

defineEmits(['click'])

// Classes dynamiques
const sizeClasses = {
  red: {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  },
  'outline-red': {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  },
  'outline-white': {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  },
  white: {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  }
}

const sizeIcon = {
  sm: 'text-sm',
  md: 'text-base',
  lg: 'text-lg'
}

const variantClasses = {
  red: 'bg-diginova-red text-white',
  'outline-red': 'bg-transparent text-diginova-red',
  'outline-white': 'bg-transparent text-white',
  white: 'bg-white text-diginova-blue'
}

const variantBackground = {
  red: 'bg-diginova-red/90',
  'outline-red': 'bg-diginova-red/10',
  'outline-white': 'bg-white/10',
  white: 'bg-diginova-blue/10'
}

const variantBorder = {
  'outline-red': 'border-diginova-red group-hover:border-diginova-red/50',
  'outline-white': 'border-white group-hover:border-white/50'
}

// Animation au hover
const hoverAnimation = computed(() => {
  if (props.disabled) return ''
  return props.variant.includes('outline') 
    ? 'hover:scale-105 hover:shadow-diginova-red/20' 
    : 'hover:scale-[1.02] hover:shadow-diginova-red/30'
})
</script>

<style scoped>
/* Animation de la bordure pour les boutons outline */
button:hover span:last-child {
  animation: borderAnimation 1.5s ease-in-out infinite;
}

@keyframes borderAnimation {
  0%, 100% {
    clip-path: polygon(0% 0%, 0% 100%, 100% 100%, 100% 0%);
  }
  25% {
    clip-path: polygon(0% 0%, 0% 100%, 0% 100%, 0% 0%);
  }
  50% {
    clip-path: polygon(100% 0%, 100% 100%, 100% 100%, 100% 0%);
  }
  75% {
    clip-path: polygon(0% 0%, 0% 0%, 100% 0%, 100% 0%);
  }
}

/* Transition du fond */
button span:first-child {
  transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), 
              opacity 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

/* Effet "press" */
button:active:not(:disabled) {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}
</style>
