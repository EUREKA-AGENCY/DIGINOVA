<template>
  <div class="services-grid">
    <!-- Filtres par catégorie -->
    <div v-if="showFilters" class="filters mb-12">
      <div class="flex flex-wrap gap-3 justify-center">
        <button
          v-for="category in categories"
          :key="category.value"
          @click="activeCategory = category.value"
          class="category-filter"
          :class="{ 'active': activeCategory === category.value }"
        >
          {{ category.label }}
        </button>
      </div>
    </div>

    <!-- Grille de services -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <ServiceCard
        v-for="service in filteredServices"
        :key="service.id"
        :service="service"
        @click="emit('serviceSelected', service)"
      />
    </div>

    <!-- Empty state -->
    <div
      v-if="filteredServices.length === 0"
      class="empty-state py-12 text-center"
    >
      <div class="empty-icon mx-auto mb-6">
        <i class="bi bi-search text-5xl text-diginova-blue/30"></i>
      </div>
      <h3 class="text-xl font-bold text-diginova-blue mb-2">
        Aucun service trouvé
      </h3>
      <p class="text-gray-500 max-w-md mx-auto">
        Nous n'avons pas de services correspondant à cette catégorie pour le moment.
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import type { Service } from '@/types'

interface Props {
  services: Service[]
  showFilters?: boolean
}

const props = defineProps<Props>()
const emit = defineEmits(['serviceSelected'])

// Gestion des filtres
const activeCategory = ref<string>('all')

const categories = [
  { value: 'all', label: 'TOUS' },
  { value: 'dev', label: 'DÉVELOPPEMENT' },
  { value: 'hardware', label: 'MATÉRIEL' },
  { value: 'support', label: 'SUPPORT' }
]

const filteredServices = computed(() => {
  if (activeCategory.value === 'all') return props.services
  return props.services.filter(s => s.category === activeCategory.value)
})
</script>

<style scoped>
.services-grid {
  @apply relative;
}

.category-filter {
  @apply diginova-uppercase px-5 py-2 text-sm font-bold rounded-full border-2 
         border-diginova-blue/20 text-diginova-blue/80 transition-all 
         duration-200 hover:border-diginova-red hover:text-diginova-red;

  &.active {
    @apply bg-diginova-red text-white border-diginova-red;
  }
}

.empty-state {
  @apply bg-gray-50 rounded-xl;
}

/* Animation des cartes */
.service-card-enter-active {
  transition: all 0.3s ease-out;
}

.service-card-enter-from {
  opacity: 0;
  transform: translateY(20px);
}
</style>