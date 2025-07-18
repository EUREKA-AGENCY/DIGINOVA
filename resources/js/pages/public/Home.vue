<template>
  <AppLayout>
    <Hero />

    <!-- Section Services -->
    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <SectionTitle
          title="NOS EXPERTISES"
          subtitle="Des solutions sur mesure pour votre entreprise"
          align="center"
        />
        
        <ServicesGrid 
          v-if="services.length > 0"
          :services="services" 
          class="mt-16" 
        />
        <div v-else class="text-center py-12">
          <p class="text-gray-500">Aucun service disponible pour le moment</p>
        </div>
        
        <div class="text-center mt-16">
          <CallToAction 
            text="TOUS NOS SERVICES" 
            variant="outline-blue" 
            to="/services"
            icon="arrow_forward"
          />
        </div>
      </div>
    </section>

    <!-- Section Témoignages -->
    <section class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <SectionTitle
          title="ILS NOUS FONT CONFIANCE"
          subtitle="Ce que nos clients disent de nous"
          align="center"
        />
        
        <!-- Ajouter ici le composant TestimonialsCarousel -->
      </div>
    </section>

    <!-- Section CTA finale -->
    <section class="py-20 bg-diginova-red text-white">
      <div class="container mx-auto px-4 text-center">
        <h2 class="diginova-uppercase text-3xl font-bold mb-6">
          PRÊT À TRANSFORMER VOTRE BUSINESS ?
        </h2>
        <p class="text-xl max-w-2xl mx-auto mb-10">
          Contactez-nous pour une consultation gratuite et découvrez comment nous pouvons vous aider.
        </p>
        <CallToAction 
          text="NOUS CONTACTER" 
          variant="white" 
          class="px-10 py-4 text-lg mx-auto"
          @click="$inertia.visit('/quote')"
        />
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue' // Correction du chemin
import Hero from '@/components/Public/Hero.vue'
import CallToAction from '@/components/Public/CallToAction.vue' // Correction de la casse
import SectionTitle from '@/components/Public/SectionTitle.vue' // Correction de la casse
import ServicesGrid from '@/components/Public/Services/ServicesGrid.vue' // Correction de la casse

defineProps({
  services: {
    type: Array,
    required: true,
    default: () => [], // Valeur par défaut ajoutée
    validator: (value) => value.every(item => item?.id && item?.name && item?.category)
  }
})
</script>

<style scoped>
/* Animation d'entrée */
.hero-enter-active {
  transition: all 0.5s ease-out;
}
.hero-enter-from {
  opacity: 0;
  transform: translateY(20px);
}
</style>
