<template>
  <div class="relative">
    <!-- Ligne de timeline -->
    <div class="absolute left-6 top-0 bottom-0 w-1 bg-diginova-red/10 rounded-full z-0"></div>
    
    <!-- Éléments de timeline -->
    <div 
      v-for="(item, index) in items" 
      :key="index"
      class="relative pl-16 mb-12 last:mb-0 group"
      :style="{ '--delay': `${index * 100}ms` }"
    >
      <!-- Point de timeline -->
      <div class="absolute left-0 z-10">
        <div class="timeline-dot transform group-hover:scale-110 group-hover:bg-diginova-red/20">
          <i :class="['bi', item.icon, 'text-diginova-red text-lg']"></i>
        </div>
      </div>
      
      <!-- Carte de contenu -->
      <div class="timeline-card transform transition-all duration-500 ease-out group-hover:-translate-y-1">
        <div class="diginova-uppercase text-sm font-semibold text-diginova-red mb-1">
          {{ item.year }}
        </div>
        <h3 class="diginova-uppercase text-xl font-bold text-diginova-blue mb-2">
          {{ item.title }}
        </h3>
        <p class="text-gray-600">
          {{ item.description }}
        </p>
        
        <!-- Effet de hover -->
        <div class="absolute inset-0 rounded-xl bg-diginova-red/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
defineProps({
  items: {
    type: Array,
    required: true,
    validator: items => items.every(item => 
      item.year && item.title && item.description && item.icon
    )
  }
})

// Animation d'entrée
onMounted(() => {
  const timelineItems = document.querySelectorAll('.timeline-card')
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = 1
        entry.target.style.transform = 'translateY(0)'
      }
    })
  }, { threshold: 0.1 })

  timelineItems.forEach(item => {
    item.style.opacity = 0
    item.style.transform = 'translateY(20px)'
    item.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)'
    item.style.transitionDelay = item.parentElement.style.getPropertyValue('--delay')
    observer.observe(item)
  })
})
</script>

<style scoped>
.timeline-dot {
  @apply w-12 h-12 rounded-full bg-diginova-red/10 flex items-center justify-center transition-all duration-300;
}

.timeline-card {
  @apply bg-white p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300;
}

/* Animation des éléments enfants */
.timeline-card:hover h3 {
  @apply text-diginova-red;
  transition: color 0.3s ease;
}

.timeline-card:hover .timeline-dot {
  @apply shadow-lg;
}
</style>