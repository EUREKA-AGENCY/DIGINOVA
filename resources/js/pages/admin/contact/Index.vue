<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Demandes de contact</h1>
        <table class="w-full border text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left">Nom</th>
              <th class="p-2 text-left">Email</th>
              <th class="p-2 text-left">Sujet</th>
              <th class="p-2 text-left">Statut</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in items.data" :key="r.id" class="border-t">
              <td class="p-2">{{ r.first_name }} {{ r.last_name }}</td>
              <td class="p-2">{{ r.email }}</td>
              <td class="p-2">{{ r.topic }}</td>
              <td class="p-2">{{ r.status }}</td>
              <td class="p-2 text-right">
                <a :href="`/admin/contact-requests/${r.id}`" class="text-diginova-red">Voir</a>
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
defineProps({ items: { type: Object, required: true } })
</script>

