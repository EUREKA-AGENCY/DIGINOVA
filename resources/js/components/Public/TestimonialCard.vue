<template>
  <div 
    class="testimonial-card bg-white p-8 rounded-xl shadow-md transition-all duration-500"
    :class="{ 
      'scale-105 shadow-lg': active,
      'hover:shadow-md': !active
    }"
  >
    <!-- Note -->
    <div class="rating flex mb-4">
      <i 
        v-for="i in 5"
        :key="i"
        class="bi text-xl"
        :class="{
          'bi-star-fill text-diginova-red': i <= testimonial.rating,
          'bi-star text-diginova-blue/20': i > testimonial.rating
        }"
      ></i>
    </div>
    
    <!-- Citation -->
    <blockquote class="text-lg italic text-diginova-blue mb-6">
      "{{ testimonial.content }}"
    </blockquote>
    
    <!-- Auteur -->
    <div class="author flex items-center">
      <div 
        v-if="testimonial.avatar"
        class="avatar mr-4"
      >
        <img 
          :src="testimonial.avatar" 
          :alt="testimonial.author"
          class="w-12 h-12 rounded-full object-cover"
        >
      </div>
      <div>
        <p class="font-bold text-diginova-blue">{{ testimonial.author }}</p>
        <p class="text-sm text-gray-500">
          {{ testimonial.role }} • {{ testimonial.company }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  testimonial: {
    id: number
    author: string
    role: string
    company: string
    content: string
    rating: number
    avatar?: string
  }
  active?: boolean
}>()
</script>