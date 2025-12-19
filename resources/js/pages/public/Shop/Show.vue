<template>
  <AppLayout>
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <a href="/products" class="text-diginova-red">← Retour à la boutique</a>
        <h1 class="text-3xl font-bold text-diginova-blue mt-2">{{ product.name }}</h1>
        <div class="text-sm text-gray-500 mt-1 uppercase">{{ typeLabel(product.product_type) }}</div>
        <div class="mt-4 text-gray-700" v-if="product.short_description">{{ product.short_description }}</div>
        <div class="mt-4" v-if="product.description" v-html="product.description"></div>
        <div class="mt-6 font-bold text-xl" v-if="product.product_type==='sale' && product.price">{{ formatPrice(product.price) }}</div>
        <div class="mt-6 font-bold text-xl" v-else-if="product.product_type==='rental' && product.rent_price">{{ formatPrice(product.rent_price) }} / mois</div>
        <div class="mt-6 flex gap-3">
          <button class="px-5 py-2 bg-diginova-red text-white rounded-md" @click="$inertia.visit('/contact')">Contacter</button>
          <button class="px-5 py-2 border border-diginova-red text-diginova-red rounded-md" @click="$inertia.visit('/quote')">Demander un devis</button>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue'
defineProps({ product: { type: Object, required: true } })
const formatPrice = (v) => new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(Number(v || 0))
const typeLabel = (t) => ({ sale: 'Vente', rental: 'Location', service: 'Service' }[t] || 'Produit')
</script>
