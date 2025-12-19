<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Articles</h1>
        <table class="w-full border text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left">Titre</th>
              <th class="p-2 text-left">CatÃ©gorie</th>
              <th class="p-2 text-left">PubliÃ©</th>
              <th class="p-2 text-left">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in items.data" :key="p.id" class="border-t">
              <td class="p-2">{{ p.title }}</td>
              <td class="p-2">{{ p.category }}</td>
              <td class="p-2">{{ p.is_published ? 'Oui' : 'Non' }}</td>
              <td class="p-2">{{ p.published_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
defineProps({ items: { type: Object, required: true } })
</script>

