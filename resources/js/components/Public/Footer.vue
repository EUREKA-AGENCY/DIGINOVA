<template>
  <footer class="bg-diginova-blue text-white relative overflow-hidden">
    <!-- Wave separator animée -->
    <div class="wave-separator">
      <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path 
          d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" 
          opacity=".25" 
          fill="currentColor"
          class="wave-animate"
          style="animation-delay: 0.1s"
        ></path>
        <path 
          d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" 
          opacity=".5" 
          fill="currentColor"
          class="wave-animate"
          style="animation-delay: 0.3s"
        ></path>
        <path 
          d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" 
          fill="currentColor"
          class="wave-animate"
          style="animation-delay: 0.5s"
        ></path>
      </svg>
    </div>

    <!-- Effet de particules -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
      <div 
        v-for="i in 15" 
        :key="i"
        class="particle absolute rounded-full bg-white"
        :style="{
          left: `${Math.random() * 100}%`,
          top: `${Math.random() * 100}%`,
          width: `${Math.random() * 4 + 1}px`,
          height: `${Math.random() * 4 + 1}px`,
          animation: `float ${Math.random() * 20 + 10}s linear infinite`,
          animationDelay: `${Math.random() * 5}s`
        }"
      ></div>
    </div>

    <div class="container mx-auto px-4 py-16 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12">
        <!-- Brand Column -->
        <div class="lg:col-span-2">
          <div class="flex items-center mb-6 transform hover:scale-105 transition-transform duration-300">
            <Logo class="h-10 text-white" />
            <span class="ml-3 text-xl font-bold">DIGINOVA</span>
          </div>
          
          <p class="text-white/80 mb-6 leading-relaxed animate-fade-in">
            Nous transformons vos idées en solutions digitales performantes pour propulser votre business.
          </p>
          
          <!-- Newsletter améliorée -->
          <div class="newsletter animate-fade-in" style="animation-delay: 0.2s">
            <h4 class="diginova-uppercase text-sm font-bold mb-3 flex items-center">
              <i class="bi bi-envelope-plus-fill text-diginova-red mr-2"></i>
              NEWSLETTER
            </h4>
            <div class="flex shadow-lg rounded-lg overflow-hidden">
              <input 
                type="email" 
                placeholder="Votre email" 
                class="flex-grow px-4 py-3 focus:outline-none text-diginova-blue focus:ring-2 focus:ring-diginova-red/50"
              >
              <button class="bg-diginova-red hover:bg-diginova-red-dark px-4 py-3 transition-all duration-300 flex items-center">
                <i class="bi bi-send-fill text-white mr-2"></i>
                <span class="text-sm font-medium">S'inscrire</span>
              </button>
            </div>
            <p class="text-xs text-white/50 mt-2">
              Nous ne partagerons jamais votre email.
            </p>
          </div>
        </div>

        <!-- Navigation Links -->
        <div class="animate-fade-in" style="animation-delay: 0.3s">
          <h3 class="footer-heading">Navigation</h3>
          <ul class="space-y-3 mt-6">
            <li v-for="(item, index) in navigation" :key="item.name">
              <Link 
                :href="item.href"
                class="footer-link group flex items-center"
              >
                <i class="bi bi-chevron-right text-diginova-red mr-2 text-xs opacity-0 group-hover:opacity-100 transition-all duration-200"></i>
                <span class="link-underline">{{ item.name }}</span>
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact amélioré -->
        <div class="animate-fade-in" style="animation-delay: 0.4s">
          <h3 class="footer-heading">Contact</h3>
          <ul class="space-y-4 mt-6">
            <li class="flex items-start group">
              <div class="bg-diginova-red/20 p-2 rounded-full mr-3 group-hover:bg-diginova-red transition-colors duration-300">
                <i class="bi bi-geo-alt-fill text-diginova-red group-hover:text-white"></i>
              </div>
              <div>
                <span class="block">INTENDANCE,</span>
                <span>YAOUNDE CAMEROUN</span>
              </div>
            </li>
            <li class="flex items-center group">
              <div class="bg-diginova-red/20 p-2 rounded-full mr-3 group-hover:bg-diginova-red transition-colors duration-300">
                <i class="bi bi-telephone-fill text-diginova-red group-hover:text-white"></i>
              </div>
              <a href="tel:+123456789" class="hover:text-white transition-colors">+1 23 45 67 89</a>
            </li>
            <li class="flex items-center group">
              <div class="bg-diginova-red/20 p-2 rounded-full mr-3 group-hover:bg-diginova-red transition-colors duration-300">
                <i class="bi bi-envelope-fill text-diginova-red group-hover:text-white"></i>
              </div>
              <a href="mailto:contact@diginova.com" class="hover:text-white transition-colors">contact@diginova.com</a>
            </li>
          </ul>
        </div>

        <!-- Social & Legal amélioré -->
        <div class="space-y-8 animate-fade-in" style="animation-delay: 0.5s">
          <div>
            <h3 class="footer-heading flex items-center">
              <i class="bi bi-people-fill text-diginova-red mr-2"></i>
              Réseaux sociaux
            </h3>
            <div class="flex space-x-3 mt-6">
              <a 
                v-for="(social, index) in socials"
                :key="index"
                :href="social.link"
                class="social-icon group"
                :aria-label="social.name"
                :style="`transition-delay: ${index * 50}ms`"
              >
                <i :class="`${social.icon} group-hover:scale-110 transition-transform`"></i>
                <span class="social-tooltip">{{ social.name }}</span>
              </a>
            </div>
          </div>

          <div>
            <h3 class="footer-heading flex items-center">
              <i class="bi bi-shield-lock text-diginova-red mr-2"></i>
              Legal
            </h3>
            <ul class="space-y-3 mt-6">
              <li>
                <a href="#" class="footer-link group flex items-center">
                  <i class="bi bi-file-earmark-text text-diginova-red/50 mr-2 text-xs group-hover:text-diginova-red transition-colors"></i>
                  <span class="link-underline">Mentions légales</span>
                </a>
              </li>
              <li>
                <a href="#" class="footer-link group flex items-center">
                  <i class="bi bi-lock text-diginova-red/50 mr-2 text-xs group-hover:text-diginova-red transition-colors"></i>
                  <span class="link-underline">Politique de confidentialité</span>
                </a>
              </li>
              <li>
                <a href="#" class="footer-link group flex items-center">
                  <i class="bi bi-file-earmark-check text-diginova-red/50 mr-2 text-xs group-hover:text-diginova-red transition-colors"></i>
                  <span class="link-underline">CGV</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Copyright amélioré -->
      <div class="border-t border-white/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center animate-fade-in" style="animation-delay: 0.6s">
        <p class="text-sm text-white/60 hover:text-white transition-colors flex items-center">
          <i class="bi bi-c-circle mr-1"></i>
          © {{ new Date().getFullYear() }} DIGINOVA. Tous droits réservés.
        </p>
        <div class="flex space-x-6 mt-4 md:mt-0">
          <a href="#" class="text-sm text-white/60 hover:text-white transition-colors flex items-center">
            <i class="bi bi-file-text mr-1"></i>
            Conditions d'utilisation
          </a>
          <a href="#" class="text-sm text-white/60 hover:text-white transition-colors flex items-center">
            <i class="bi bi-cookie mr-1"></i>
            Politique des cookies
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Logo from '../Logo.vue'

defineProps<{
  navigation: Array<{
    name: string
    href: string
  }>
}>()

const socials = [
  { name: 'LinkedIn', icon: 'bi bi-linkedin', link: '#' },
  { name: 'Twitter', icon: 'bi bi-twitter-x', link: '#' },
  { name: 'Facebook', icon: 'bi bi-facebook', link: '#' },
  { name: 'Instagram', icon: 'bi bi-instagram', link: '#' },
  { name: 'YouTube', icon: 'bi bi-youtube', link: '#' },
]
</script>

<style scoped>
.wave-separator {
  @apply w-full h-20 transform -translate-y-px overflow-hidden;
  color: theme('colors.diginova.blue');
}

.wave-animate {
  animation: wave-move 20s linear infinite;
}

@keyframes wave-move {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.footer-heading {
  @apply diginova-uppercase text-sm font-bold pb-2 flex items-center;
  
  &::after {
    @apply absolute bottom-0 left-0 w-10 h-0.5;
    background: linear-gradient(90deg, theme('colors.diginova.red'), transparent);
    content: '';
  }
}

.footer-link {
  @apply text-white/80 hover:text-white transition-colors duration-200;
}

.link-underline {
  @apply relative;
  
  &::after {
    @apply absolute bottom-0 left-0 w-0 h-px transition-all duration-300;
    background: linear-gradient(90deg, theme('colors.diginova.red'), transparent);
    content: '';
  }
  
  .footer-link:hover &::after {
    @apply w-full;
  }
}

.social-icon {
  @apply w-10 h-10 rounded-full bg-white/10 hover:bg-diginova-red flex items-center justify-center text-lg relative overflow-hidden transition-all duration-300;
  
  &::before {
    @apply absolute inset-0 bg-diginova-red opacity-0 transition-opacity duration-300;
    content: '';
    z-index: 0;
  }
  
  &:hover::before {
    @apply opacity-100;
  }
  
  i {
    @apply relative z-10;
  }
}

.social-tooltip {
  @apply absolute -bottom-8 left-1/2 transform -translate-x-1/2 bg-diginova-red text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 transition-opacity duration-200;
  
  .social-icon:hover & {
    @apply opacity-100;
  }
}

.newsletter {
  input {
    @apply focus:ring-2 focus:ring-diginova-red/50 focus:border-transparent;
    min-width: 0;
  }
  
  button {
    @apply transition-all duration-300 hover:shadow-lg;
  }
}

.particle {
  animation: float 20s linear infinite;
}

@keyframes float {
  0% { transform: translateY(0) translateX(0); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(-100px) translateX(20px); opacity: 0; }
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out forwards;
}
</style>
