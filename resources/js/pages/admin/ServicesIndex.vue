<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-2xl font-bold text-diginova-blue">Services</h1>
          <a href="/admin/services/create" class="px-4 py-2 bg-diginova-red text-white rounded">Nouveau</a>
        </div>
        <table class="w-full border text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left">Ordre</th>
              <th class="p-2 text-left">Titre</th>
              <th class="p-2 text-left">CatÃ©gorie</th>
              <th class="p-2 text-left">Actif</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="s in items.data" :key="s.id" class="border-t">
              <td class="p-2">{{ s.order }}</td>
              <td class="p-2">{{ s.title }}</td>
              <td class="p-2">{{ s.category }}</td>
              <td class="p-2">{{ s.is_active ? 'Oui' : 'Non' }}</td>
              <td class="p-2 text-right">
                <a :href="`/admin/services/${s.id}/edit`" class="text-diginova-red">Modifier</a>
                <button class="ml-3 text-gray-500" @click="destroy(s.id)">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
const props = defineProps({ items: { type: Object, required: true } })
const destroy = (id) => {
  if (confirm('Supprimer ce service ?')) window.Inertia.delete(`/admin/services/${id}`)
}
</script>

