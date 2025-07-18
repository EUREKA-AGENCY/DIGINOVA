<template>
  <AppLayout>
    <!-- Hero Section - Version améliorée -->
    <section class="relative bg-diginova-blue text-white overflow-hidden h-screen max-h-[800px] flex items-center">
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-diginova-blue to-diginova-blue-dark/90 backdrop-blur-sm"></div>
        <div class="absolute inset-0 opacity-30">
          <div class="shape-float-1"></div>
          <div class="shape-float-2"></div>
          <div class="shape-float-3"></div>
          <div class="shape-float-4"></div>
        </div>
      </div>

      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-4/5 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
          <div class="mb-8 inline-block animate-fade-in-down" style="animation-delay: 200ms">
            <Logo class="h-16 sm:h-20 text-white opacity-90 hover:opacity-100 transition-opacity duration-500 hover:scale-110 transform transition-transform" />
          </div>
          
          <h1 class="diginova-uppercase text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 leading-tight animate-fade-in-up" style="animation-delay: 300ms">
            <span class="text-gradient-animated">Notre ADN</span> digital
          </h1>
          
          <p class="text-xl sm:text-2xl max-w-2xl mx-auto opacity-90 leading-relaxed mb-10 animate-fade-in-up" style="animation-delay: 500ms">
            L'innovation au service de <span class="highlight-text-animated">votre réussite</span>
          </p>
          
          <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in" style="animation-delay: 700ms">
            <InteractiveButton 
              text="Découvrir notre équipe"
              variant="outline-white"
              icon="bi-arrow-down"
              icon-animation="bounce"
              @click="scrollToTeam"
              class="hover:bg-white/10 hover:scale-105 transform transition-transform"
            />
            <InteractiveButton 
              text="Contactez-nous"
              variant="red"
              icon="bi-chat-dots"
              icon-animation="pulse"
              @click="$inertia.visit('/contact')"
              class="hover:scale-105 hover:shadow-lg transform transition-transform"
            />
          </div>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 right-0 h-24 bg-white transform -skew-y-3 origin-bottom-right translate-y-1/2 z-0"></div>
      
      <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
        <MouseScrollIndicator class="hover:scale-125 transform transition-transform" />
      </div>
    </section>

    <!-- Section Histoire - Version enrichie -->
    <section class="py-20 sm:py-28 bg-white relative overflow-hidden" id="histoire">
      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-3/4">
        <SectionTitle
          title="Notre parcours"
          subtitle="De 2015 à aujourd'hui"
          align="left"
          class="animate-fade-in"
        />

        <div class="grid lg:grid-cols-2 gap-10 sm:gap-16 items-center mt-12 sm:mt-16">
          <div class="relative h-full min-h-[350px] sm:min-h-[400px] group perspective-1000">
            <div class="absolute inset-0 bg-gray-100 rounded-2xl overflow-hidden transform transition-all duration-700 group-hover:rotate-1 group-hover:shadow-2xl animate-fade-in-right">
              <div class="h-full w-full bg-diginova-blue/5 pattern-dots-lg">
                <div class="absolute inset-0 flex items-center justify-center p-6 sm:p-10">
                  <div class="text-center transform transition-all duration-500 group-hover:scale-105">
                    <div class="text-7xl sm:text-8xl font-bold text-diginova-red mb-4 hover:text-diginova-blue transition-colors duration-300 cursor-default">2015</div>
                    <div class="diginova-uppercase text-lg font-bold text-diginova-blue group-hover:text-diginova-red transition-colors duration-300">
                      L'année fondatrice
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="absolute -inset-3 sm:-inset-4 rounded-2xl border-2 border-diginova-red/20 opacity-0 group-hover:opacity-100 transition-all duration-500 -z-10"></div>
          </div>
          
          <div class="relative animate-fade-in-left">
            <InteractiveTimeline 
              :items="timeline" 
              class="timeline-custom"
            />
            <div class="absolute -left-8 sm:-left-10 top-1/2 transform -translate-y-1/2 text-diginova-red opacity-30 hidden lg:block">
              <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 5L21 12M21 12L14 19M21 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Stats - Version dynamique -->
    <section class="py-20 sm:py-28 bg-diginova-blue/3 relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-full opacity-10">
        <div class="absolute top-1/4 left-1/4 w-40 h-40 rounded-full bg-diginova-red/20 blur-3xl animate-float-slow"></div>
        <div class="absolute bottom-1/3 right-1/4 w-32 h-32 rounded-full bg-diginova-blue/20 blur-3xl animate-float-slow" style="animation-delay: 2s"></div>
      </div>

      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-3/4 relative z-10">
        <SectionTitle
          title="Notre impact"
          subtitle="En quelques chiffres"
          class="animate-fade-in"
        />

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12 sm:mt-16">
          <StatCard 
            v-for="(stat, index) in stats" 
            :key="index"
            :value="stat.value"
            :label="stat.label"
            :prefix="stat.prefix"
            :suffix="stat.suffix"
            :delay="index * 100"
            class="hover:shadow-lg hover:border-diginova-red/30 transition-all hover:scale-[1.03] animate-fade-in-up"
            :style="`animation-delay: ${index * 50 + 100}ms`"
          />
        </div>

        <div class="text-center mt-10 sm:mt-12 text-diginova-blue/70 text-sm animate-fade-in" style="animation-delay: 600ms">
          <span class="inline-block px-6 sm:px-10 w-full sm:w-3/4 py-2 rounded-full bg-white/50 backdrop-blur-sm border border-diginova-blue/10 transition-all hover:bg-white/70 hover:shadow-sm">
            Chiffres mis à jour en 2023
          </span>
        </div>
      </div>
    </section>

    <!-- Section Valeurs - Version interactive -->
    <section class="py-20 sm:py-28 bg-white relative">
      <div class="absolute top-0 right-0 w-1/3 h-full bg-diginova-blue/5 -z-10 clip-path-polygon"></div>

      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-3/4">
        <SectionTitle
          title="Nos convictions"
          subtitle="Ce qui nous anime au quotidien"
          class="animate-fade-in"
        />

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12 sm:mt-16">
          <ValueCard 
            v-for="(value, index) in values" 
            :key="index"
            :title="value.title"
            :description="value.description"
            :icon="value.icon"
            :color="value.color"
            :delay="index * 100"
            class="hover:scale-[1.03] transition-transform hover:shadow-md animate-fade-in-up"
            :style="`animation-delay: ${index * 50 + 100}ms`"
          />
        </div>
      </div>
    </section>

    <!-- Section CTA - Version immersive -->
    <section class="relative py-24 sm:py-32 bg-diginova-blue text-white overflow-hidden">
      <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="particle absolute rounded-full bg-white/10" v-for="i in 15" :key="i" 
          :style="{
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            width: `${Math.random() * 20 + 10}px`,
            height: `${Math.random() * 20 + 10}px`,
            animation: `float ${Math.random() * 10 + 10}s linear infinite`,
            animationDelay: `${Math.random() * 5}s`
          }">
        </div>
      </div>

      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-3/4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="diginova-uppercase text-3xl sm:text-4xl md:text-5xl font-bold mb-6 animate-fade-in">
            Prêt à <span class="text-diginova-red animate-pulse">transformer</span> vos idées ?
          </h2>
          <p class="text-xl sm:text-2xl max-w-2xl mx-auto opacity-90 mb-8 sm:mb-10 animate-fade-in" style="animation-delay: 100ms">
            Parlons de votre projet et créons ensemble quelque chose d'exceptionnel
          </p>
          
          <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 animate-fade-in" style="animation-delay: 200ms">
            <InteractiveButton 
              text="Prendre rendez-vous"
              variant="red"
              size="lg"
              icon="bi-calendar-check"
              icon-animation="pulse"
              @click="$inertia.visit('/contact')"
              class="hover:-translate-y-1 hover:shadow-xl transition-all hover:scale-105"
            />
            <InteractiveButton 
              text="Voir nos réalisations"
              variant="outline-white"
              size="lg"
              icon="bi-eye"
              @click="$inertia.visit('/portfolio')"
              class="hover:bg-white/10 hover:-translate-y-1 transition-all hover:scale-105"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Section Équipe - Version améliorée -->
    <section class="py-20 sm:py-28 bg-gray-50 relative" id="equipe">
      <div class="absolute inset-0 bg-gradient-to-br from-white to-diginova-blue/5 -z-10"></div>

      <div class="container mx-auto px-6 sm:px-10 w-full lg:w-3/4">
        <SectionTitle
          title="Rencontrez l'équipe"
          subtitle="Des experts passionnés"
          class="animate-fade-in"
        />

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mt-12 sm:mt-16">
          <TeamMemberCard 
            v-for="(member, index) in team" 
            :key="index"
            :name="member.name"
            :position="member.position"
            :bio="member.bio"
            :skills="member.skills"
            :socials="member.socials"
            :delay="index * 100"
            class="hover:shadow-xl hover:border-diginova-red/30 transition-all hover:scale-[1.03] animate-fade-in-up"
            :style="`animation-delay: ${index * 50 + 100}ms`"
          />
        </div>

        <div class="text-center mt-16 sm:mt-20 animate-fade-in" style="animation-delay: 300ms">
          <p class="text-lg text-diginova-blue max-w-2xl mx-auto px-4 py-3 inline-block rounded-lg bg-white/50 backdrop-blur-sm border border-diginova-blue/10 transition-all hover:bg-white/70 hover:shadow-sm">
            Une équipe pluridisciplinaire à votre service pour concrétiser vos ambitions digitales.
          </p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
// Les imports et le script setup restent identiques
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import Logo from '@/components/Logo.vue'
import SectionTitle from '@/components/Public/SectionTitle.vue'
import InteractiveButton from '@/components/Public/InteractiveButton.vue'
import InteractiveTimeline from '@/components/Public/About/InteractiveTimeline.vue'
import StatCard from '@/components/Public/About/StatCard.vue'
import ValueCard from '@/components/Public/About/ValueCard.vue'
import TeamMemberCard from '@/components/Public/About/TeamMemberCard.vue'
import MouseScrollIndicator from '@/components/Public/MouseScrollIndicator.vue'

const scrollToTeam = () => {
  const element = document.getElementById('equipe')
  element?.scrollIntoView({ behavior: 'smooth' })
}

// Les données (timeline, stats, values, team) restent identiques
// Timeline historique
const timeline = [
  {
    year: "2015",
    title: "Fondation",
    description: "Création de DIGINOVA avec une petite équipe de passionnés du digital.",
    icon: "bi-rocket"
  },
  {
    year: "2017",
    title: "Premier grand projet",
    description: "Lancement d'une plateforme SaaS innovante pour un client international.",
    icon: "bi-trophy"
  },
  {
    year: "2019",
    title: "Expansion internationale",
    description: "Ouverture de nos premiers bureaux à l'étranger et recrutement d'experts multilingues.",
    icon: "bi-globe"
  },
  {
    year: "2021",
    title: "Labellisation",
    description: "Obtention du label French Tech et reconnaissance de notre expertise.",
    icon: "bi-award"
  },
  {
    year: "2023",
    title: "Nouveau siège",
    description: "Installation dans nos nouveaux locaux high-tech au cœur de Paris.",
    icon: "bi-building"
  }
]

// Statistiques
const stats = [
  { value: 8, label: "Années d'expertise", prefix: "+" },
  { value: 120, label: "Projets livrés", prefix: "+", suffix: "+" },
  { value: 95, label: "Satisfaction client", prefix: "", suffix: "%" },
  { value: 15, label: "Experts spécialisés", prefix: "+" },
  { value: 42, label: "Technologies maîtrisées", prefix: "+" },
  { value: 24, label: "Pays desservis", prefix: "+" }
]

// Valeurs
const values = [
  {
    title: "Innovation",
    description: "Nous repoussons les limites du possible pour créer des solutions avant-gardistes.",
    icon: "bi-lightbulb",
    color: "text-purple-500"
  },
  {
    title: "Excellence",
    description: "Nous visons la perfection dans chaque détail pour des résultats exceptionnels.",
    icon: "bi-award",
    color: "text-diginova-blue"
  },
  {
    title: "Agilité",
    description: "Méthodologies flexibles pour s'adapter à vos besoins spécifiques.",
    icon: "bi-speedometer2",
    color: "text-green-500"
  },
  {
    title: "Transparence",
    description: "Communication claire et honnête à chaque étape du projet.",
    icon: "bi-eye",
    color: "text-blue-400"
  },
  {
    title: "Collaboration",
    description: "Votre vision devient notre mission grâce à un partenariat étroit.",
    icon: "bi-people-fill",
    color: "text-orange-400"
  },
  {
    title: "Passion",
    description: "Nous mettons tout notre cœur dans chaque projet qui nous est confié.",
    icon: "bi-heart-fill",
    color: "text-diginova-red"
  }
]

// Équipe
const team = [
  {
    name: "Jean Dupont",
    position: "CEO & Fondateur",
    bio: "15 ans d'expérience en stratégie digitale. Passionné par l'innovation et la transformation des entreprises.",
    skills: ["Stratégie", "Leadership", "Innovation"],
    socials: [
      { icon: "bi-linkedin", link: "#", name: "LinkedIn" },
      { icon: "bi-twitter-x", link: "#", name: "Twitter" }
    ]
  },
  {
    name: "Marie Lambert",
    position: "Directrice Technique",
    bio: "Expert en architecture cloud et solutions scalables. Ancienne lead developer chez Google.",
    skills: ["Cloud", "DevOps", "Scalabilité"],
    socials: [
      { icon: "bi-github", link: "#", name: "GitHub" },
      { icon: "bi-stack-overflow", link: "#", name: "Stack Overflow" }
    ]
  },
  {
    name: "Thomas Martin",
    position: "Directeur Artistique",
    bio: "Designer primé spécialisé en expérience utilisateur et interfaces innovantes.",
    skills: ["UI/UX", "Motion Design", "Branding"],
    socials: [
      { icon: "bi-behance", link: "#", name: "Behance" },
      { icon: "bi-dribbble", link: "#", name: "Dribbble" }
    ]
  },
  {
    name: "Emma Bertrand",
    position: "Cheffe de Projet",
    bio: "Garante du bon déroulement de vos projets et de votre satisfaction client.",
    skills: ["Agile", "SCRUM", "Planification"],
    socials: [
      { icon: "bi-linkedin", link: "#", name: "LinkedIn" },
      { icon: "bi-slack", link: "#", name: "Slack" }
    ]
  }
]
</script>

<style scoped>
/* Animation des formes de fond améliorée */
.shape-float-1 {
  @apply absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-diginova-red/30 mix-blend-screen filter blur-3xl;
  animation: float 12s ease-in-out infinite;
}

.shape-float-2 {
  @apply absolute top-1/3 right-1/3 w-80 h-80 rounded-full bg-white/20 mix-blend-screen filter blur-3xl;
  animation: float 15s ease-in-out infinite reverse;
}

.shape-float-3 {
  @apply absolute bottom-1/4 right-1/4 w-72 h-72 rounded-full bg-diginova-red/40 mix-blend-screen filter blur-3xl;
  animation: float 10s ease-in-out infinite 2s;
}

.shape-float-4 {
  @apply absolute top-2/3 left-1/3 w-60 h-60 rounded-full bg-diginova-blue/30 mix-blend-screen filter blur-3xl;
  animation: float 14s ease-in-out infinite 1s;
}

/* Dégradé de texte animé */
.text-gradient-animated {
  background: linear-gradient(90deg, #E12428 0%, #FF6B6B 50%, #3B82F6 100%);
  background-size: 200% 200%;
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  animation: gradient-shift 5s ease infinite;
}

/* Effet de surlignement animé */
.highlight-text-animated {
  @apply relative inline-block;
}

.highlight-text-animated::after {
  @apply absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-diginova-red to-diginova-blue transform scale-x-0 origin-left transition-transform duration-500;
  content: '';
}

.highlight-text-animated:hover::after {
  @apply scale-x-100;
}

/* Pattern de points plus visible */
.pattern-dots-lg {
  background-image: radial-gradient(currentColor 0.8px, transparent 0.8px);
  background-size: 15px 15px;
}

/* Timeline personnalisée */
.timeline-custom {
  @apply border-l-2 border-diginova-blue/20 pl-8;
}

/* Animations améliorées */
@keyframes float {
  0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
  50% { transform: translate(-15px, -15px) rotate(2deg) scale(1.02); }
}

@keyframes float-slow {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-15px) scale(1.03); }
}

@keyframes gradient-shift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-down {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-left {
  from { opacity: 0; transform: translateX(-30px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes fade-in-right {
  from { opacity: 0; transform: translateX(30px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.8; transform: scale(1.05); }
}

.animate-float { animation: float 10s ease-in-out infinite; }
.animate-float-slow { animation: float-slow 15s ease-in-out infinite; }
.animate-fade-in { animation: fade-in 0.8s ease-out forwards; }
.animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
.animate-fade-in-down { animation: fade-in-down 0.8s ease-out forwards; }
.animate-fade-in-left { animation: fade-in-left 0.8s ease-out forwards; }
.animate-fade-in-right { animation: fade-in-right 0.8s ease-out forwards; }
.animate-pulse { animation: pulse 2s ease-in-out infinite; }

.clip-path-polygon {
  clip-path: polygon(0 0, 100% 0, 100% 100%, 30% 100%);
}

.particle {
  animation: float 15s linear infinite;
}

.perspective-1000 {
  perspective: 1000px;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .clip-path-polygon {
    clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
    width: 100%;
  }
  
  .timeline-custom {
    @apply border-l-0 border-t-2 border-diginova-blue/20 pl-0 pt-8 mt-8;
  }
}
</style>
