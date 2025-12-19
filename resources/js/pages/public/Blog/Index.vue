<template>
  <AppLayout>
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 max-w-5xl">
        <h1 class="text-3xl font-bold text-diginova-blue mb-8">Blog</h1>
        <div class="flex items-end gap-4 mb-8">
          <div class="flex-1">
            <label class="block text-sm text-gray-500">Rechercher</label>
            <input v-model="q" @keyup.enter="applyFilters" type="text" class="border rounded px-3 py-2 w-full" placeholder="Rechercher un article..." />
          </div>
          <div>
            <label class="block text-sm text-gray-500">Catégorie</label>
            <select v-model="category" @change="applyFilters" class="border rounded px-3 py-2">
              <option value="">Toutes</option>
              <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>
        </div>
        <div v-if="items.length" class="space-y-8">
          <article v-for="p in items" :key="p.id" class="border-b pb-6">
            <h2 class="text-2xl font-semibold text-diginova-blue">
              <a :href="`/blog/${p.slug}`" class="hover:underline">{{ p.title }}</a>
            </h2>
            <div class="text-gray-500 text-sm mt-1" v-if="p.published_at">{{ new Date(p.published_at).toLocaleDateString('fr-FR') }}</div>
            <img v-if="p.cover_image" :src="p.cover_image" alt="" class="mt-4 rounded-lg border" />
            <p class="mt-3 text-gray-700" v-if="p.excerpt">{{ p.excerpt }}</p>
          </article>
        </div>
        <div v-else class="text-gray-500">Aucun article publié.</div>

        <div v-if="posts?.links" class="mt-8 flex justify-center gap-2 flex-wrap">
          <a v-for="l in posts.links" :key="l.url + (l.label || '')"
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
const props = defineProps({ posts: { type: [Array, Object], default: () => [] }, categories: { type: Array, default: () => [] }, filters: { type: Object, default: () => ({}) } })
const items = computed(() => Array.isArray(props.posts) ? props.posts : (props.posts.data || []))

const q = ref('')
const category = ref('')
onMounted(() => {
  const url = new URL(window.location.href)
  q.value = url.searchParams.get('q') || (props.filters.q || '')
  category.value = url.searchParams.get('category') || (props.filters.category || '')
})

const applyFilters = () => {
  const url = new URL(window.location.href)
  if (q.value) url.searchParams.set('q', q.value); else url.searchParams.delete('q')
  if (category.value) url.searchParams.set('category', category.value); else url.searchParams.delete('category')
  window.location.assign(url.toString())
}
</script>
