<template>
    <!-- Hero Section avec CTA premium -->
<section class="relative bg-diginova-blue text-white overflow-hidden">
  <!-- Arrière-plan dynamique -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute inset-0 bg-diginova-blue/95"></div>
    <div class="absolute inset-0 opacity-20">
      <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-diginova-red/50 mix-blend-screen filter blur-3xl opacity-70 animate-float1"></div>
    </div>
  </div>

  <div class="container mx-auto px-4 py-28 md:py-36 relative z-10">
    <div class="max-w-4xl mx-auto text-center">
      <!-- Logo avec halo -->
      <div class="flex justify-center mb-8">
        <div class="relative">
          <Logo class="h-16 md:h-20 text-white" />
          <div class="absolute inset-0 rounded-full bg-diginova-red/10 blur-md -z-10"></div>
        </div>
      </div>
      
      <!-- Titre principal -->
      <h1 class="diginova-uppercase text-4xl md:text-6xl font-bold mb-6 tracking-tight leading-tight">
        SOLUTIONS <span class="text-diginova-red">DIGITALES</span>
      </h1>
      
      <!-- Sous-titre animé -->
      <p class="text-xl md:text-2xl mb-10 opacity-90 leading-relaxed max-w-2xl mx-auto">
        Nous transformons vos idées en 
        <span class="highlight-text">solutions innovantes</span> et 
        <span class="highlight-text">expériences uniques</span>
      </p>
      
      <!-- Zone CTA améliorée -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 md:gap-6">
        <!-- Bouton principal - Version améliorée -->
        <a 
          href="/quote" 
          class="cta-primary group"
          @click.prevent="$inertia.visit('/quote')"
        >
          <span>DÉMARRER UN PROJET</span>
          <div class="cta-arrow">
            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1 7H17M17 7L11 1M17 7L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="cta-hover"></div>
        </a>
        
        <!-- Bouton secondaire - Version améliorée -->
        <a 
          href="/services" 
          class="cta-secondary group"
          @click.prevent="$inertia.visit('/services')"
        >
          <span>NOS EXPERTISES</span>
          <div class="cta-dots">
            <span></span><span></span><span></span>
          </div>
        </a>
      </div>

      <!-- Indicateur de confiance -->
      <div class="mt-12 flex flex-wrap justify-center items-center gap-x-8 gap-y-4 text-sm text-white/70">
        <div class="flex items-center">
          <div class="flex -space-x-2 mr-3">
            <img src="/avatars/avatar1.jpg" class="w-8 h-8 rounded-full border-2 border-white" alt="Client">
            <img src="/avatars/avatar2.jpg" class="w-8 h-8 rounded-full border-2 border-white" alt="Client">
            <img src="/avatars/avatar3.jpg" class="w-8 h-8 rounded-full border-2 border-white" alt="Client">
          </div>
          <span>+50 clients satisfaits</span>
        </div>
        <div class="flex items-center">
          <i class="bi bi-check-circle-fill text-diginova-red mr-2"></i>
          <span>Solutions sur mesure</span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Indicateur de scroll animé -->
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10">
    <div class="mouse-scroll"></div>
  </div>
  
  <!-- Transition vers le bas -->
  <div class="absolute bottom-0 left-0 right-0 h-16 bg-white transform -skew-y-2 origin-bottom-right translate-y-1/2 z-0"></div>
</section>
</template>

<style>
/* Animation des éléments de fond */
@keyframes float1 {
  0%, 100% { transform: translate(0, 0); }
  50% { transform: translate(-10px, -10px); }
}

/* Style des CTA premium */
.cta-primary {
  @apply relative inline-flex items-center justify-center px-8 py-4 md:px-10 md:py-4 bg-diginova-red text-white font-bold diginova-uppercase text-sm md:text-base rounded-lg overflow-hidden transition-all duration-300;
}

.cta-primary .cta-arrow {
  @apply ml-3 transition-transform duration-300;
}

.cta-primary .cta-hover {
  @apply absolute inset-0 bg-black/10 opacity-0 transition-opacity duration-300;
}

.cta-primary:hover {
  @apply shadow-xl transform -translate-y-0.5;
}

.cta-primary:hover .cta-arrow {
  @apply transform translate-x-1;
}

.cta-primary:hover .cta-hover {
  @apply opacity-100;
}

.cta-secondary {
  @apply relative inline-flex items-center justify-center px-8 py-4 md:px-10 md:py-4 bg-transparent text-white font-bold diginova-uppercase text-sm md:text-base rounded-lg border-2 border-white overflow-hidden transition-all duration-300;
}

.cta-secondary .cta-dots span {
  @apply inline-block w-1 h-1 bg-white rounded-full ml-1 opacity-70;
}

.cta-secondary:hover {
  @apply bg-white/10 transform -translate-y-0.5 shadow-lg;
}

.cta-secondary:hover .cta-dots span {
  animation: dotBounce 0.6s infinite alternate;
}

.cta-secondary:hover .cta-dots span:nth-child(2) {
  animation-delay: 0.2s;
}

.cta-secondary:hover .cta-dots span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes dotBounce {
  to { transform: translateY(-3px); opacity: 1; }
}

/* Indicateur de scroll */
.mouse-scroll {
  @apply w-6 h-10 border-2 border-white rounded-full relative;
}

.mouse-scroll::before {
  @apply absolute top-2 left-1/2 w-1 h-2 bg-white rounded-full;
  content: '';
  transform: translateX(-50%);
  animation: scrollIndicator 2s infinite;
}

@keyframes scrollIndicator {
  0% { opacity: 1; transform: translate(-50%, 0); }
  100% { opacity: 0; transform: translate(-50%, 10px); }
}

/* Effet de surlignement */
.highlight-text {
  @apply relative inline-block;
}

.highlight-text::after {
  @apply absolute bottom-0 left-0 w-full h-1 bg-diginova-red transform scale-x-0 origin-left transition-transform duration-500;
  content: '';
}

.highlight-text:hover::after {
  @apply scale-x-100;
}

/* Animation de fond */
.animate-float1 {
  animation: float1 8s ease-in-out infinite;
}
</style>
