<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">TÃ©moignages</h1>
        <table class="w-full border text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left">Auteur</th>
              <th class="p-2 text-left">RÃ´le</th>
              <th class="p-2 text-left">Entreprise</th>
              <th class="p-2 text-left">PubliÃ©</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="t in items.data" :key="t.id" class="border-t">
              <td class="p-2">{{ t.author_name }}</td>
              <td class="p-2">{{ t.author_role }}</td>
              <td class="p-2">{{ t.company }}</td>
              <td class="p-2">{{ t.is_published ? 'Oui' : 'Non' }}</td>
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

