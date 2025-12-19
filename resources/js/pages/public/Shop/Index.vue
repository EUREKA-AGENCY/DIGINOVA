<template>
  <AppLayout>
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-diginova-blue mb-6">Boutique</h1>
        <p v-if="filter==='rental'" class="text-gray-600 mb-6">Matériel disponible à la location</p>

        <div class="flex flex-wrap items-center gap-3 mb-4">
          <button @click="goFilter('')" :class="pillClass(!filter)" class="px-3 py-1 rounded-full border">Tous</button>
          <button @click="goFilter('sale')" :class="pillClass(filter==='sale')" class="px-3 py-1 rounded-full border">Vente</button>
          <button @click="goFilter('rental')" :class="pillClass(filter==='rental')" class="px-3 py-1 rounded-full border">Location</button>
        </div>

        <div v-if="categories?.length" class="flex flex-wrap gap-2 mb-6">
          <button v-for="c in categories" :key="c.id" @click="goCategory(c.slug)" :class="pillClass(currentCategory===c.slug)" class="px-3 py-1 rounded-full border">
            {{ c.name }}
          </button>
        </div>

        <div class="flex items-end gap-4 mb-8">
          <div class="flex-1">
            <label class="block text-sm text-gray-500">Rechercher</label>
            <input type="text" class="border rounded px-3 py-2 w-full" v-model="q" @keyup.enter="applySearch" placeholder="Ex: laptop, serveur..." />
          </div>
          <div>
            <label class="block text-sm text-gray-500">Prix min</label>
            <input type="number" class="border rounded px-3 py-2 w-32" v-model.number="priceMin" @change="applyRange" />
          </div>
          <div>
            <label class="block text-sm text-gray-500">Prix max</label>
            <input type="number" class="border rounded px-3 py-2 w-32" v-model.number="priceMax" @change="applyRange" />
          </div>
          <div class="ml-auto">
            <label class="block text-sm text-gray-500">Trier par</label>
            <select class="border rounded px-3 py-2" v-model="sort" @change="applySort">
              <option value="">Nom (A→Z)</option>
              <option value="price_asc">Prix (croissant)</option>
              <option value="price_desc">Prix (décroissant)</option>
              <option value="created_desc">Nouveautés</option>
            </select>
          </div>
        </div>

        <div v-if="items.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="p in items" :key="p.id" class="bg-white border rounded-xl p-5 shadow-sm">
            <div class="text-sm uppercase text-gray-500 mb-2">{{ typeLabel(p.product_type) }}</div>
            <h3 class="text-lg font-semibold text-diginova-blue">
              <a :href="`/products/${p.slug}`" class="hover:underline">{{ p.name }}</a>
            </h3>
            <div class="mt-2 text-gray-600" v-if="p.short_description">{{ p.short_description }}</div>
            <div class="mt-4 font-bold" v-if="p.product_type==='sale' && p.price">{{ formatPrice(p.price) }}</div>
            <div class="mt-4 font-bold" v-else-if="p.product_type==='rental' && p.rent_price">{{ formatPrice(p.rent_price) }} / mois</div>
            <div class="mt-4 flex gap-2">
              <button class="px-4 py-2 bg-diginova-red text-white rounded-md" @click="$inertia.visit('/contact')">Contacter</button>
              <button class="px-4 py-2 border border-diginova-red text-diginova-red rounded-md" @click="$inertia.visit('/quote')">Devis</button>
            </div>
          </div>
        </div>
        <div v-else class="text-gray-500">Aucun produit disponible.</div>

        <div v-if="products?.links" class="mt-8 flex justify-center gap-2 flex-wrap">
          <a v-for="l in products.links" :key="l.url + (l.label || '')"
             :href="l.url || '#'"
             :class="['px-3 py-1 rounded border', l.active ? 'bg-diginova-red text-white border-diginova-red' : 'text-diginova-red border-diginova-red', !l.url ? 'opacity-40 pointer-events-none' : '']"
             v-html="l.label"></a>
        </div>
      </div>
    </section>
  </AppLayout>
  </template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import { computed, ref, onMounted } from 'vue'

const props = defineProps({
  products: { type: [Array, Object], default: () => [] },
  categories: { type: Array, default: () => [] },
  filter: { type: String, default: '' },
  currentCategory: { type: String, default: '' },
})

const formatPrice = (v) => new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(Number(v || 0))
const typeLabel = (t) => ({ sale: 'Vente', rental: 'Location', service: 'Service' }[t] || 'Produit')
const pillClass = (active) => active ? 'bg-diginova-red text-white border-diginova-red' : 'text-diginova-red border-diginova-red'

const items = computed(() => Array.isArray(props.products) ? props.products : (props.products.data || []))

const goFilter = (type) => {
  const url = new URL(window.location.href)
  if (type) url.searchParams.set('type', type); else url.searchParams.delete('type')
  window.location.assign(url.toString())
}
const goCategory = (slug) => {
  const url = new URL(window.location.href)
  if (slug) url.searchParams.set('category', slug); else url.searchParams.delete('category')
  window.location.assign(url.toString())
}

const priceMin = ref('')
const priceMax = ref('')
const sort = ref('')
const q = ref('')

onMounted(() => {
  const url = new URL(window.location.href)
  priceMin.value = url.searchParams.get('price_min') || ''
  priceMax.value = url.searchParams.get('price_max') || ''
  sort.value = url.searchParams.get('sort') || ''
  q.value = url.searchParams.get('q') || ''
})

const applyRange = () => {
  const url = new URL(window.location.href)
  if (priceMin.value !== '' && priceMin.value != null) url.searchParams.set('price_min', String(priceMin.value)); else url.searchParams.delete('price_min')
  if (priceMax.value !== '' && priceMax.value != null) url.searchParams.set('price_max', String(priceMax.value)); else url.searchParams.delete('price_max')
  window.location.assign(url.toString())
}

const applySort = () => {
  const url = new URL(window.location.href)
  if (sort.value) url.searchParams.set('sort', sort.value); else url.searchParams.delete('sort')
  window.location.assign(url.toString())
}

const applySearch = () => {
  const url = new URL(window.location.href)
  if (q.value) url.searchParams.set('q', q.value); else url.searchParams.delete('q')
  window.location.assign(url.toString())
}
</script>
