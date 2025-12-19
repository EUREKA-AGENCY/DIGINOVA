<template>
  <AppLayout>
    <section class="py-20 bg-gray-50">
      <div class="container mx-auto px-4 text-center max-w-2xl">
        <h1 class="text-3xl font-bold text-diginova-blue mb-4">Votre demande de devis a été envoyée</h1>
        <p class="text-gray-600">Nos équipes reviennent vers vous rapidement avec une estimation.</p>
        <div class="mt-8 flex justify-center gap-3">
          <a href="/products" class="px-5 py-2 border border-diginova-red text-diginova-red rounded-md">Voir nos produits</a>
          <a href="/" class="px-5 py-2 bg-diginova-red text-white rounded-md">Retour à l’accueil</a>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue'
</script>
