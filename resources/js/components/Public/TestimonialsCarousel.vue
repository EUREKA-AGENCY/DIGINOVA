<template>
  <div class="testimonials-carousel relative">
    <!-- Carousel Container -->
    <div 
      ref="carousel"
      class="carousel-container overflow-hidden"
      @touchstart="handleTouchStart"
      @touchmove="handleTouchMove"
    >
      <div 
        class="carousel-track flex transition-transform duration-500 ease-out"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
        <!-- Item de témoignage -->
        <div 
          v-for="(testimonial, index) in testimonials"
          :key="testimonial.id"
          class="carousel-slide flex-shrink-0 w-full px-4"
          :class="{ 'active': currentIndex === index }"
        >
          <TestimonialCard 
            :testimonial="testimonial"
            :active="currentIndex === index"
          />
        </div>
      </div>
    </div>

    <!-- Contrôles de navigation -->
    <div class="carousel-controls mt-12 flex justify-center items-center gap-6">
      <button 
        @click="prev"
        class="p-2 text-diginova-blue hover:text-diginova-red transition-colors"
        aria-label="Témoignage précédent"
      >
        <i class="bi bi-chevron-left text-2xl"></i>
      </button>
      
      <!-- Pagination -->
      <div class="flex gap-2">
        <button
          v-for="(_, index) in testimonials"
          :key="index"
          @click="goTo(index)"
          class="w-3 h-3 rounded-full transition-all"
          :class="{
            'bg-diginova-red w-6': currentIndex === index,
            'bg-diginova-blue/20': currentIndex !== index
          }"
          :aria-label="`Aller au témoignage ${index + 1}`"
        />
      </div>
      
      <button 
        @click="next"
        class="p-2 text-diginova-blue hover:text-diginova-red transition-colors"
        aria-label="Témoignage suivant"
      >
        <i class="bi bi-chevron-right text-2xl"></i>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import TestimonialCard from '@/components/Public/TestimonialCard.vue'

interface Testimonial {
  id: number
  author: string
  role: string
  company: string
  content: string
  rating: number
  avatar?: string
}

const props = defineProps<{
  testimonials: Testimonial[]
  autoplay?: boolean
  interval?: number
}>()

const currentIndex = ref(0)
const carousel = ref<HTMLElement | null>(null)
let touchStartX = 0
let autoplayInterval: number | null = null

// Navigation
const next = () => {
  currentIndex.value = (currentIndex.value + 1) % props.testimonials.length
}

const prev = () => {
  currentIndex.value = (currentIndex.value - 1 + props.testimonials.length) % props.testimonials.length
}

const goTo = (index: number) => {
  currentIndex.value = index
}

// Gestures tactiles
const handleTouchStart = (e: TouchEvent) => {
  touchStartX = e.touches[0].clientX
  stopAutoplay()
}

const handleTouchMove = (e: TouchEvent) => {
  if (!touchStartX) return
  const touchX = e.touches[0].clientX
  const diff = touchStartX - touchX
  
  if (diff > 50) next()  // Swipe gauche
  if (diff < -50) prev() // Swipe droite
  
  touchStartX = 0
}

// Autoplay
const startAutoplay = () => {
  if (props.autoplay) {
    autoplayInterval = window.setInterval(next, props.interval || 5000)
  }
}

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval)
    autoplayInterval = null
  }
}

// Lifecycle
onMounted(() => {
  startAutoplay()
})

onUnmounted(() => {
  stopAutoplay()
})
</script>

<style scoped>
.testimonials-carousel {
  @apply max-w-7xl mx-auto;
}

.carousel-container {
  @apply relative;
  touch-action: pan-y;
}

.carousel-track {
  @apply w-full;
}

.carousel-slide {
  @apply transition-opacity duration-300;
  opacity: 0.3;
  
  &.active {
    opacity: 1;
  }
}

/* Animation des slides */
.carousel-slide-enter-active,
.carousel-slide-leave-active {
  transition: all 0.5s ease;
}
.carousel-slide-enter-from,
.carousel-slide-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
