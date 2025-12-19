<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Produits</h1>
        <table class="w-full border text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-2 text-left">Nom</th>
              <th class="p-2 text-left">Type</th>
              <th class="p-2 text-left">Prix</th>
              <th class="p-2 text-left">Stock</th>
              <th class="p-2 text-left">Actif</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in items.data" :key="p.id" class="border-t">
              <td class="p-2">{{ p.name }}</td>
              <td class="p-2">{{ p.product_type }}</td>
              <td class="p-2">{{ p.product_type==='rental' ? (p.rent_price+' /mois') : p.price }}</td>
              <td class="p-2">{{ p.stock }}</td>
              <td class="p-2">{{ p.is_active ? 'Oui' : 'Non' }}</td>
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

