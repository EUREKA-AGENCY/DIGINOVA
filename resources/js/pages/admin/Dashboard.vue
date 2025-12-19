<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Administration</h1>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-gray-50 border rounded-xl p-5">
            <div class="text-gray-500">Services</div>
            <div class="text-3xl font-bold">{{ counts.services }}</div>
            <a href="/admin/services" class="text-diginova-red">Gérer</a>
          </div>
          <div class="bg-gray-50 border rounded-xl p-5">
            <div class="text-gray-500">Produits</div>
            <div class="text-3xl font-bold">{{ counts.products }}</div>
            <a href="/admin/products" class="text-diginova-red">Gérer</a>
          </div>
          <div class="bg-gray-50 border rounded-xl p-5">
            <div class="text-gray-500">Articles</div>
            <div class="text-3xl font-bold">{{ counts.posts }}</div>
            <a href="/admin/posts" class="text-diginova-red">Gérer</a>
          </div>
          <div class="bg-gray-50 border rounded-xl p-5">
            <div class="text-gray-500">Témoignages</div>
            <div class="text-3xl font-bold">{{ counts.testimonials }}</div>
            <a href="/admin/testimonials" class="text-diginova-red">Gérer</a>
          </div>
        </div>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
defineProps({ counts: { type: Object, required: true } })
</script>
