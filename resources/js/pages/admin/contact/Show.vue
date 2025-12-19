<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Demande #{{ item.id }}</h1>
        <div class="bg-gray-50 border rounded-xl p-5 mb-6">
          <div><strong>Nom:</strong> {{ item.first_name }} {{ item.last_name }}</div>
          <div><strong>Email:</strong> {{ item.email }}</div>
          <div><strong>TÃ©lÃ©phone:</strong> {{ item.phone || 'â€”' }}</div>
          <div><strong>Sujet:</strong> {{ item.topic }}</div>
        </div>
        <div class="bg-white border rounded-xl p-5">
          <p class="whitespace-pre-wrap">{{ item.message }}</p>
        </div>
        <div class="mt-6 flex items-center gap-3">
          <select v-model="status" class="border rounded px-3 py-2">
            <option value="new">Nouveau</option>
            <option value="in_progress">En cours</option>
            <option value="closed">FermÃ©</option>
          </select>
          <button class="px-4 py-2 bg-diginova-red text-white rounded" @click="updateStatus">Mettre Ã  jour</button>
          <a href="/admin/contact-requests" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Retour</a>
        </div>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref } from 'vue'
const props = defineProps({ item: { type: Object, required: true } })
const status = ref(props.item.status || 'new')
const updateStatus = () => {
  window.Inertia.put(`/admin/contact-requests/${props.item.id}`, { status: status.value })
}
</script>

