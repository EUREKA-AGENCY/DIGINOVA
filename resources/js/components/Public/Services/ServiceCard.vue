<template>
  <article
    class="service-card bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer"
    @click="$emit('click')"
  >
    <!-- Header avec couleur de catégorie -->
    <div 
      class="category-bar h-2"
      :class="{
        'bg-diginova-red': service.category === 'dev',
        'bg-diginova-blue': service.category === 'hardware',
        'bg-gray-500': service.category === 'support'
      }"
    ></div>

    <div class="p-6">
      <!-- Icône de catégorie -->
      <div class="icon mb-4 text-4xl">
        <i 
          class="bi"
          :class="{
            'bi-code-slash': service.category === 'dev',
            'bi-pc-display': service.category === 'hardware',
            'bi-headset': service.category === 'support'
          }"
        ></i>
      </div>

      <!-- Titre et description -->
      <h3 class="text-xl font-bold text-diginova-blue mb-2 diginova-uppercase">
        {{ service.name }}
      </h3>
      <p class="text-gray-600 mb-4 line-clamp-3">
        {{ service.description }}
      </p>

      <!-- Badge de catégorie -->
      <span 
        class="category-badge inline-block px-3 py-1 rounded-full text-xs font-bold diginova-uppercase"
        :class="{
          'bg-diginova-red/10 text-diginova-red': service.category === 'dev',
          'bg-diginova-blue/10 text-diginova-blue': service.category === 'hardware',
          'bg-gray-100 text-gray-600': service.category === 'support'
        }"
      >
        {{ 
          service.category === 'dev' ? 'Développement' :
          service.category === 'hardware' ? 'Matériel' : 'Support'
        }}
      </span>
    </div>
  </article>
</template>

<script setup lang="ts">
defineProps<{
  service: {
    id: number
    name: string
    description: string
    category: 'dev' | 'hardware' | 'support'
  }
}>()

defineEmits(['click'])
</script>