<template>
  <div class="mouse-scroll-indicator flex flex-col items-center">
    <!-- Souris -->
    <div class="mouse w-6 h-10 border-2 border-white rounded-full flex justify-center items-start p-1">
      <!-- Roulette -->
      <div class="wheel w-1 h-2 bg-white rounded-full animate-scroll"></div>
    </div>
    
    <!-- Flèche -->
    <div class="arrow mt-2 animate-bounce">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 5V19M12 19L19 12M12 19L5 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    
    <!-- Texte optionnel -->
    <p v-if="text" class="text-white text-xs mt-2 opacity-80">{{ text }}</p>
  </div>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    default: 'Scroll'
  }
})
</script>

<style scoped>
/* Animation de la roulette */
@keyframes scroll {
  0% {
    transform: translateY(0);
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    transform: translateY(5px);
    opacity: 0;
  }
}

/* Animation de la flèche */
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(5px);
  }
}

.animate-scroll {
  animation: scroll 1.5s infinite;
}

.animate-bounce {
  animation: bounce 2s infinite;
}

.mouse {
  perspective: 100px;
}

.mouse:hover .wheel {
  animation-play-state: paused;
}
</style>
