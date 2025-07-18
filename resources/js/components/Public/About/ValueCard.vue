<template>
  <div 
    ref="cardElement"
    class="value-card relative bg-white rounded-xl overflow-hidden transition-all duration-500 ease-out hover:transform hover:-translate-y-1"
    :style="{ '--delay': `${delay}ms` }"
  >
    <!-- Fond animé -->
    <div class="absolute inset-0 bg-gradient-to-br from-diginova-blue/5 to-white/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <!-- Contenu -->
    <div class="relative z-10 p-8">
      <!-- Icône animée -->
      <div class="icon-container mb-6">
        <div class="icon-wrapper" :style="{ color: color }">
          <i :class="['bi', icon, 'text-3xl']"></i>
        </div>
      </div>
      
      <!-- Titre -->
      <h3 class="diginova-uppercase text-lg font-bold text-diginova-blue mb-3">
        {{ title }}
      </h3>
      
      <!-- Description -->
      <p class="text-gray-600">
        {{ description }}
      </p>
      
      <!-- Effet de hover -->
      <div class="absolute inset-0 rounded-xl bg-diginova-red/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
    </div>
    
    <!-- Bordure animée -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-diginova-red to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: '#E12428' // diginova-red par défaut
  },
  delay: {
    type: Number,
    default: 0
  }
})

const cardElement = ref(null)

// Animation d'entrée
onMounted(() => {
  if (!cardElement.value) return
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = 1
        entry.target.style.transform = 'translateY(0)'
        animateIcon(entry.target.querySelector('.icon-wrapper'))
        observer.unobserve(entry.target)
      }
    })
  }, { threshold: 0.1 })

  observer.observe(cardElement.value)
})

const animateIcon = (iconElement) => {
  if (!iconElement) return
  
  setTimeout(() => {
    iconElement.style.transform = 'scale(1)'
    iconElement.style.opacity = 1
  }, props.delay)
}
</script>

<style scoped>
.value-card {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), 
              transform 0.6s cubic-bezier(0.16, 1, 0.3, 1),
              box-shadow 0.3s ease;
  transition-delay: var(--delay);
  box-shadow: 0 4px 6px rgba(12, 17, 34, 0.05);
}

.value-card:hover {
  box-shadow: 0 10px 25px rgba(12, 17, 34, 0.1);
}

.icon-container {
  @apply relative w-16 h-16;
}

.icon-wrapper {
  @apply absolute inset-0 rounded-full flex items-center justify-center bg-opacity-10;
  transform: scale(0.5);
  opacity: 0;
  transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55), 
              opacity 0.4s ease,
              background-color 0.3s ease;
  transition-delay: var(--delay);
}

.value-card:hover .icon-wrapper {
  @apply bg-opacity-20;
}
</style>