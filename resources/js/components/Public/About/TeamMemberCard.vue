<template>
  <div 
    class="team-member-card relative bg-white rounded-xl overflow-hidden transition-all duration-500 ease-out"
    :style="{ '--delay': `${delay}ms` }"
  >
    <!-- Avatar dynamique -->
    <div 
      class="avatar-placeholder w-full h-60 flex items-center justify-center text-white text-5xl font-bold"
      :style="{ backgroundColor: stringToColor(name) }"
    >
      {{ getInitials(name) }}
    </div>
    
    <!-- Contenu -->
    <div class="p-6 relative">
      <!-- Nom et position -->
      <div class="mb-3">
        <h3 class="diginova-uppercase font-bold text-lg text-diginova-blue">
          {{ name }}
        </h3>
        <p class="text-sm text-diginova-red">
          {{ position }}
        </p>
      </div>
      
      <!-- Compétences -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span 
          v-for="(skill, index) in skills" 
          :key="index"
          class="skill-tag text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700"
        >
          {{ skill }}
        </span>
      </div>
      
      <!-- Bio avec effet "lire plus" -->
      <div class="text-sm text-gray-600 mb-4 line-clamp-3">
        {{ bio }}
      </div>
      
      <!-- Réseaux sociaux (apparaissent au survol) -->
      <div class="social-icons flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <a 
          v-for="(social, index) in socials" 
          :key="index"
          :href="social.link" 
          class="social-icon w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-diginova-red hover:text-white transition-all duration-300"
          target="_blank"
          :aria-label="`${name} - ${social.name}`"
        >
          <i :class="['bi', social.icon]"></i>
        </a>
      </div>
      
      <!-- Indicateur de spécialité -->
      <div 
        class="absolute top-4 right-4 w-3 h-3 rounded-full"
        :style="{ backgroundColor: stringToColor(position) }"
      ></div>
    </div>
    
    <!-- Effet de hover -->
    <div class="absolute inset-0 rounded-xl bg-diginova-blue/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  position: {
    type: String,
    required: true
  },
  bio: {
    type: String,
    required: true
  },
  skills: {
    type: Array,
    default: () => []
  },
  socials: {
    type: Array,
    default: () => []
  },
  delay: {
    type: Number,
    default: 0
  }
})

// Génère une couleur à partir d'une string
const stringToColor = (str) => {
  let hash = 0
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash)
  }
  const hue = Math.abs(hash % 360)
  return `hsl(${hue}, 70%, 50%)`
}

// Extrait les initiales du nom
const getInitials = (name) => {
  return name.split(' ')
    .map(part => part[0])
    .join('')
    .toUpperCase()
}

// Animation d'entrée
onMounted(() => {
  const card = document.querySelector('.team-member-card')
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = 1
        entry.target.style.transform = 'translateY(0)'
        observer.unobserve(entry.target)
      }
    })
  }, { threshold: 0.1 })

  card.style.opacity = 0
  card.style.transform = 'translateY(20px)'
  card.style.transition = 'opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)'
  card.style.transitionDelay = `${props.delay}ms`
  observer.observe(card)
})
</script>

<style scoped>
.team-member-card {
  box-shadow: 0 4px 6px rgba(12, 17, 34, 0.05);
}

.team-member-card:hover {
  box-shadow: 0 20px 25px rgba(12, 17, 34, 0.1);
  transform: translateY(-5px);
}

.avatar-placeholder {
  background: linear-gradient(135deg, var(--diginova-blue) 0%, var(--diginova-red) 100%);
}

.skill-tag {
  transition: all 0.3s ease;
}

.team-member-card:hover .skill-tag {
  background-color: rgba(225, 36, 40, 0.1);
  color: var(--diginova-red);
  transform: translateY(-2px);
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>