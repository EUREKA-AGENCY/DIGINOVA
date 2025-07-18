<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative bg-diginova-blue py-28 text-white overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('https://assets-global.website-files.com/5f5f5f5f5f5f5f5f5f5f5f5f/5f5f5f5f5f5f5f5f5f5f5f5f/pattern-grid.svg')]"></div>
      </div>
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl text-center relative z-10">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 animate-fade-in">
          Nos réalisations
        </h1>
        <p class="text-xl sm:text-2xl max-w-3xl mx-auto opacity-90 animate-fade-in delay-100">
          Découvrez une sélection de nos projets phares qui illustrent notre expertise et notre savoir-faire.
        </p>
      </div>
    </section>

    <!-- Projects Filter -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex flex-wrap justify-center gap-3">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="filterProjects(category.id)"
            class="px-5 py-2 rounded-full border transition-all"
            :class="{
              'bg-diginova-red text-white border-diginova-red': activeFilter === category.id,
              'bg-white text-diginova-blue border-gray-300 hover:border-diginova-red hover:text-diginova-red': activeFilter !== category.id
            }"
          >
            {{ category.label }}
          </button>
        </div>
      </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-12 bg-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="project in filteredProjects"
            :key="project.id"
            class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300"
            @click="openModal(project)"
          >
            <div class="aspect-w-16 aspect-h-9 overflow-hidden">
              <img 
                :src="project.image" 
                :alt="project.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              >
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
              <div class="translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <div class="flex flex-wrap gap-2 mb-3">
                  <span 
                    v-for="tag in project.tags"
                    :key="tag"
                    class="px-3 py-1 bg-white/90 text-xs font-medium rounded-full text-diginova-blue"
                  >
                    {{ tag }}
                  </span>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">{{ project.title }}</h3>
                <p class="text-white/90 text-sm">{{ project.excerpt }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12" v-if="visibleProjects < filteredProjects.length">
          <button
            @click="loadMore"
            class="px-8 py-3 bg-diginova-red text-white rounded-lg hover:bg-diginova-blue transition-colors flex items-center mx-auto"
          >
            <i class="bi bi-plus-lg mr-2"></i> Voir plus de projets
          </button>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl">
        <h2 class="text-3xl font-bold text-center text-diginova-blue mb-12">Ils nous ont fait confiance</h2>
        
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <div 
              v-for="testimonial in testimonials"
              :key="testimonial.id"
              class="swiper-slide bg-white rounded-2xl shadow-md p-8 border border-gray-100"
            >
              <div class="flex items-center mb-6">
                <img :src="testimonial.avatar" :alt="testimonial.name" class="w-14 h-14 rounded-full object-cover mr-4">
                <div>
                  <h4 class="font-bold text-diginova-blue">{{ testimonial.name }}</h4>
                  <p class="text-gray-600 text-sm">{{ testimonial.position }}, {{ testimonial.company }}</p>
                </div>
              </div>
              <div class="text-gray-700 mb-6">
                <i class="bi bi-quote text-3xl text-gray-300 float-left mr-3 -mt-2"></i>
                <p>{{ testimonial.content }}</p>
              </div>
              <div class="flex text-diginova-red">
                <i class="bi bi-star-fill" v-for="i in testimonial.rating" :key="i"></i>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-diginova-blue text-white">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl text-center">
        <h2 class="text-3xl font-bold mb-6">Prêt à transformer vos idées en réalité ?</h2>
        <p class="text-xl opacity-90 mb-8">Notre équipe est à votre disposition pour discuter de votre projet et vous proposer des solutions sur mesure.</p>
        <div class="flex flex-wrap justify-center gap-4">
          <a 
            href="/contact" 
            class="px-8 py-3 bg-diginova-red text-white rounded-lg hover:bg-white hover:text-diginova-blue transition-colors flex items-center"
          >
            <i class="bi bi-chat-square-text mr-2"></i> Contactez-nous
          </a>
          <a 
            href="/services" 
            class="px-8 py-3 bg-white text-diginova-blue rounded-lg hover:bg-gray-100 transition-colors flex items-center"
          >
            <i class="bi bi-journal-bookmark mr-2"></i> Nos services
          </a>
        </div>
      </div>
    </section>

    <!-- Project Modal -->
    <div 
      v-if="selectedProject"
      class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
        <button 
          @click="closeModal"
          class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-full flex items-center justify-center text-diginova-red hover:bg-gray-100 transition-colors shadow-lg"
        >
          <i class="bi bi-x-lg"></i>
        </button>
        
        <div class="grid lg:grid-cols-2 gap-8">
          <div class="sticky top-0 h-full">
            <div class="swiper-container-modal h-full">
              <div class="swiper-wrapper">
                <div 
                  v-for="(image, index) in selectedProject.images"
                  :key="index"
                  class="swiper-slide"
                >
                  <img 
                    :src="image" 
                    :alt="`${selectedProject.title} - Image ${index + 1}`"
                    class="w-full h-full object-cover"
                  >
                </div>
              </div>
              <div class="swiper-pagination-modal"></div>
              <div class="swiper-button-prev-modal"></div>
              <div class="swiper-button-next-modal"></div>
            </div>
          </div>
          
          <div class="p-8 lg:p-12">
            <div class="flex flex-wrap gap-2 mb-4">
              <span 
                v-for="tag in selectedProject.tags"
                :key="tag"
                class="px-3 py-1 bg-diginova-red/10 text-diginova-red text-xs font-medium rounded-full"
              >
                {{ tag }}
              </span>
            </div>
            <h2 class="text-3xl font-bold text-diginova-blue mb-4">{{ selectedProject.title }}</h2>
            <p class="text-gray-600 mb-6">{{ selectedProject.client }}</p>
            
            <div class="prose max-w-none mb-8" v-html="selectedProject.description"></div>
            
            <div class="grid sm:grid-cols-2 gap-6 mb-8">
              <div>
                <h3 class="font-bold text-diginova-blue mb-2">Défis</h3>
                <ul class="list-disc pl-5 text-gray-700 space-y-1">
                  <li v-for="(challenge, index) in selectedProject.challenges" :key="index">{{ challenge }}</li>
                </ul>
              </div>
              <div>
                <h3 class="font-bold text-diginova-blue mb-2">Solutions</h3>
                <ul class="list-disc pl-5 text-gray-700 space-y-1">
                  <li v-for="(solution, index) in selectedProject.solutions" :key="index">{{ solution }}</li>
                </ul>
              </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
              <h3 class="font-bold text-diginova-blue mb-3">Technologies utilisées</h3>
              <div class="flex flex-wrap gap-3">
                <div 
                  v-for="tech in selectedProject.technologies"
                  :key="tech"
                  class="flex items-center px-4 py-2 bg-gray-100 rounded-lg"
                >
                  <img 
                    :src="`/images/tech-icons/${tech.toLowerCase()}.svg`" 
                    :alt="tech"
                    class="w-5 h-5 mr-2"
                    onerror="this.src='/images/tech-icons/default.svg'"
                  >
                  <span class="text-sm">{{ tech }}</span>
                </div>
              </div>
            </div>
            
            <div class="mt-8">
              <a 
                :href="selectedProject.projectUrl"
                target="_blank"
                class="inline-flex items-center px-6 py-3 bg-diginova-red text-white rounded-lg hover:bg-diginova-blue transition-colors"
              >
                <i class="bi bi-box-arrow-up-right mr-2"></i> Voir le projet
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import Swiper from 'swiper'
import 'swiper/css'

// Projects Data
const projects = ref([
  {
    id: 1,
    title: "Application de gestion RH",
    client: "Groupe Financia",
    excerpt: "Solution complète de gestion des ressources humaines avec tableau de bord analytique",
    description: `
      <p>Nous avons développé une application web sur mesure pour le Groupe Financia permettant de centraliser et d'automatiser leurs processus RH. La solution inclut :</p>
      <ul>
        <li>Gestion des congés et absences</li>
        <li>Suivi des entretiens annuels</li>
        <li>Tableau de bord analytique en temps réel</li>
        <li>Portail employé avec accès sécurisé</li>
      </ul>
      <p>L'application a permis de réduire de 40% le temps consacré aux tâches administratives RH.</p>
    `,
    category: 'web',
    tags: ['Application Web', 'RH', 'Dashboard'],
    technologies: ['Vue.js', 'Laravel', 'Tailwind', 'MySQL'],
    challenges: [
      "Intégration avec plusieurs systèmes existants",
      "Gestion des permissions complexes",
      "Traitement des données sensibles"
    ],
    solutions: [
      "API unifiée pour l'intégration des systèmes",
      "Système de rôles et permissions granulaires",
      "Chiffrement des données sensibles"
    ],
    images: [
      '/images/projects/financia-1.jpg',
      '/images/projects/financia-2.jpg',
      '/images/projects/financia-3.jpg'
    ],
    image: '/images/projects/financia-thumb.jpg',
    projectUrl: 'https://example.com'
  },
  {
    id: 2,
    title: "Plateforme e-commerce B2B",
    client: "Industrial Parts",
    excerpt: "Marketplace spécialisée pour les professionnels de l'industrie",
    description: `
      <p>Plateforme e-commerce B2B permettant aux fournisseurs de pièces industrielles de présenter leurs catalogues et aux acheteurs de passer des commandes en volume.</p>
      <p>Fonctionnalités clés :</p>
      <ul>
        <li>Catalogue produits multi-fournisseurs</li>
        <li>Système de devis et commandes en gros</li>
        <li>Gestion des comptes clients avec limites de crédit</li>
        <li>Intégration avec les ERP clients</li>
      </ul>
    `,
    category: 'web',
    tags: ['E-commerce', 'B2B', 'Marketplace'],
    technologies: ['React', 'Node.js', 'MongoDB', 'Stripe'],
    challenges: [
      "Gestion des stocks multi-fournisseurs",
      "Calcul des prix en fonction des volumes",
      "Expérience utilisateur adaptée aux professionnels"
    ],
    solutions: [
      "Système de stock distribué",
      "Moteur de règles de pricing flexible",
      "Interface optimisée pour les commandes répétitives"
    ],
    images: [
      '/images/projects/industrial-1.jpg',
      '/images/projects/industrial-2.jpg'
    ],
    image: '/images/projects/industrial-thumb.jpg',
    projectUrl: 'https://example.com'
  },
  {
    id: 3,
    title: "Application mobile de livraison",
    client: "QuickDeliver",
    excerpt: "Solution complète pour livreurs et restaurateurs avec suivi en temps réel",
    description: `
      <p>Application mobile cross-platform pour les livreurs et les restaurateurs avec suivi GPS en temps réel, gestion des tournées et analyse des performances.</p>
      <p>Principales fonctionnalités :</p>
      <ul>
        <li>Optimisation des tournées de livraison</li>
        <li>Communication client-livreur</li>
        <li>Analyse des temps de livraison</li>
        <li>Gestion des paiements</li>
      </ul>
    `,
    category: 'mobile',
    tags: ['Mobile', 'Livraison', 'GPS'],
    technologies: ['Flutter', 'Firebase', 'Google Maps API'],
    challenges: [
      "Précision du suivi GPS en milieu urbain",
      "Optimisation de la consommation batterie",
      "Synchronisation hors connexion"
    ],
    solutions: [
      "Combinaison GPS + réseaux mobiles",
      "Optimisation des mises à jour de position",
      "Système de cache intelligent"
    ],
    images: [
      '/images/projects/quickdeliver-1.jpg',
      '/images/projects/quickdeliver-2.jpg',
      '/images/projects/quickdeliver-3.jpg'
    ],
    image: '/images/projects/quickdeliver-thumb.jpg',
    projectUrl: 'https://example.com'
  },
  {
    id: 4,
    title: "Site vitrine premium",
    client: "Luxury Estates",
    excerpt: "Site web haut de gamme avec visites virtuelles pour agence immobilière",
    description: `
      <p>Site vitrine premium pour une agence immobilière spécialisée dans les biens de luxe, mettant en valeur les propriétés grâce à des visites virtuelles et des médias haute résolution.</p>
      <p>Points forts :</p>
      <ul>
        <li>Visites virtuelles 360°</li>
        <li>Galerie fullscreen haute résolution</li>
        <li>Système de filtres avancés</li>
        <li>Interface élégante et épurée</li>
      </ul>
    `,
    category: 'web',
    tags: ['Site vitrine', 'Immobilier', 'Luxe'],
    technologies: ['Next.js', 'Three.js', 'Contentful'],
    challenges: [
      "Affichage fluide des médias haute résolution",
      "Intégration des visites 360°",
      "Expérience utilisateur premium"
    ],
    solutions: [
      "Optimisation des images avec lazy loading",
      "Intégration du SDK Matterport",
      "Design minimaliste et animations subtiles"
    ],
    images: [
      '/images/projects/luxury-1.jpg',
      '/images/projects/luxury-2.jpg'
    ],
    image: '/images/projects/luxury-thumb.jpg',
    projectUrl: 'https://example.com'
  },
  {
    id: 5,
    title: "Outil de reporting BI",
    client: "Retail Analytics",
    excerpt: "Tableaux de bord interactifs pour l'analyse des ventes en temps réel",
    description: `
      <p>Outil de business intelligence permettant aux équipes de Retail Analytics de créer des tableaux de bord personnalisés pour leurs clients, avec des données mises à jour en temps réel.</p>
      <p>Fonctionnalités principales :</p>
      <ul>
        <li>Connecteurs vers multiples sources de données</li>
        <li>Éditeur de rapports visuel</li>
        <li>Partage sécurisé des tableaux de bord</li>
        <li>Alertes personnalisables</li>
      </ul>
    `,
    category: 'web',
    tags: ['BI', 'Analytics', 'Dashboard'],
    technologies: ['React', 'D3.js', 'Python', 'BigQuery'],
    challenges: [
      "Traitement de gros volumes de données",
      "Création d'un éditeur visuel intuitif",
      "Performances avec des datasets complexes"
    ],
    solutions: [
      "Architecture microservices scalable",
      "Système de templates modulaires",
      "Optimisation des requêtes et mise en cache"
    ],
    images: [
      '/images/projects/retail-1.jpg',
      '/images/projects/retail-2.jpg'
    ],
    image: '/images/projects/retail-thumb.jpg',
    projectUrl: 'https://example.com'
  },
  {
    id: 6,
    title: "Jeux mobile éducatif",
    client: "EduPlay",
    excerpt: "Application gamifiée pour l'apprentissage des mathématiques",
    description: `
      <p>Jeu mobile éducatif destiné aux enfants de 6 à 10 ans pour apprendre les mathématiques de manière ludique, avec un système de progression et de récompenses.</p>
      <p>Éléments clés :</p>
      <ul>
        <li>Parcours d'apprentissage personnalisé</li>
        <li>Mini-jeux variés</li>
        <li>Suivi des progrès pour les parents</li>
        <li>Environnement sécurisé sans publicité</li>
      </ul>
    `,
    category: 'mobile',
    tags: ['Mobile', 'Éducation', 'Gamification'],
    technologies: ['Unity', 'C#', 'Firebase'],
    challenges: [
      "Adapter le contenu au niveau de l'enfant",
      "Maintenir l'engagement sur le long terme",
      "Interface adaptée aux jeunes enfants"
    ],
    solutions: [
      "Algorithme d'adaptation du contenu",
      "Système de récompenses progressif",
      "Design coloré et interactions simples"
    ],
    images: [
      '/images/projects/eduplay-1.jpg',
      '/images/projects/eduplay-2.jpg'
    ],
    image: '/images/projects/eduplay-thumb.jpg',
    projectUrl: 'https://example.com'
  }
])

// Testimonials Data
const testimonials = ref([
  {
    id: 1,
    name: "Sophie Martin",
    position: "Directrice Marketing",
    company: "Groupe Financia",
    content: "Diginova a parfaitement compris nos besoins et a livré une application RH qui a transformé notre façon de travailler. Leur professionnalisme et leur réactivité sont remarquables.",
    rating: 5,
    avatar: '/images/testimonials/sophie-martin.jpg'
  },
  {
    id: 2,
    name: "Thomas Leroy",
    position: "PDG",
    company: "Industrial Parts",
    content: "La plateforme e-commerce développée par Diginova a permis à notre entreprise de multiplier nos ventes en ligne par 3 en seulement 6 mois. Un partenaire technologique de confiance.",
    rating: 5,
    avatar: '/images/testimonials/thomas-leroy.jpg'
  },
  {
    id: 3,
    name: "Amélie Dubois",
    position: "Directrice Générale",
    company: "QuickDeliver",
    content: "L'application mobile développée par Diginova est devenue un outil indispensable pour nos livreurs. L'interface intuitive et les performances sont au rendez-vous.",
    rating: 4,
    avatar: '/images/testimonials/amelie-dubois.jpg'
  }
])

// Categories
const categories = ref([
  { id: 'all', label: 'Tous' },
  { id: 'web', label: 'Développement Web' },
  { id: 'mobile', label: 'Applications Mobile' },
  { id: 'ecommerce', label: 'E-commerce' },
  { id: 'design', label: 'Design UI/UX' }
])

// State
const activeFilter = ref('all')
const visibleProjects = ref(6)
const selectedProject = ref(null)
let swiper = null
let modalSwiper = null

// Computed
const filteredProjects = computed(() => {
  if (activeFilter.value === 'all') {
    return projects.value
  }
  return projects.value.filter(project => project.category === activeFilter.value)
})

// Methods
const filterProjects = (categoryId) => {
  activeFilter.value = categoryId
  visibleProjects.value = 6
}

const loadMore = () => {
  visibleProjects.value += 3
}

const openModal = (project) => {
  selectedProject.value = project
  document.body.style.overflow = 'hidden'
  
  nextTick(() => {
    modalSwiper = new Swiper('.swiper-container-modal', {
      loop: true,
      pagination: {
        el: '.swiper-pagination-modal',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next-modal',
        prevEl: '.swiper-button-prev-modal',
      },
    })
  })
}

const closeModal = () => {
  selectedProject.value = null
  document.body.style.overflow = ''
  if (modalSwiper) {
    modalSwiper.destroy()
    modalSwiper = null
  }
}

// Lifecycle
onMounted(() => {
  swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 30,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      }
    }
  })
})
</script>

<style scoped>
/* Animations */
@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
}

.animate-fade-in.delay-100 {
  animation-delay: 0.1s;
}

/* Project Card Hover Effect */
.group:hover .group-hover\:translate-y-0 {
  transform: translateY(0);
}

.group:hover .group-hover\:opacity-100 {
  opacity: 1;
}

/* Swiper Custom Styles */
.swiper-pagination-bullet {
  @apply w-3 h-3 bg-gray-300 opacity-100;
}

.swiper-pagination-bullet-active {
  @apply bg-diginova-red;
}

/* Modal Transition */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  @apply bg-gray-100;
}

::-webkit-scrollbar-thumb {
  @apply bg-diginova-red rounded-full;
}

::-webkit-scrollbar-thumb:hover {
  @apply bg-diginova-blue;
}
</style>