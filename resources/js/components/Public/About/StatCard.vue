<template>
  <div 
    class="stat-card bg-white p-8 rounded-xl shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
    :style="{ '--delay': `${index * 100}ms` }"
  >
    <!-- Chiffre avec animation -->
    <div class="text-5xl md:text-6xl font-bold mb-4 text-diginova-red">
      <span class="counter">{{ prefix }}{{ displayValue }}{{ suffix }}</span>
    </div>
    
    <!-- Libellé -->
    <h3 class="diginova-uppercase text-lg font-bold text-diginova-blue">
      {{ label }}
    </h3>
    
    <!-- Barre de progression décorative -->
    <div class="mt-6 h-1 bg-gray-100 rounded-full overflow-hidden">
      <div 
        class="h-full bg-diginova-red transition-all duration-1000 ease-out" 
        :style="{ width: `${(displayValue / maxValue) * 100}%` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  prefix: {
    type: String,
    default: ''
  },
  suffix: {
    type: String,
    default: ''
  },
  index: {
    type: Number,
    default: 0
  },
  maxValue: {
    type: Number,
    default: 100
  }
})

const displayValue = ref(0)

// Animation du compteur
onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateValue()
        observer.unobserve(entry.target)
      }
    })
  })

  observer.observe(document.querySelector('.stat-card'))

  const animateValue = () => {
    const duration = 1500
    const start = 0
    const end = props.value
    const increment = end / (duration / 16)
    let current = start

    const updateCounter = () => {
      current += increment
      if (current < end) {
        displayValue.value = Math.floor(current)
        requestAnimationFrame(updateCounter)
      } else {
        displayValue.value = end
      }
    }

    setTimeout(() => {
      updateCounter()
    }, props.index * 100)
  }
})
</script>

<style scoped>
.stat-card {
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.6s forwards;
  animation-delay: var(--delay);
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.counter {
  display: inline-block;
  min-width: 1em;
}
</style>
