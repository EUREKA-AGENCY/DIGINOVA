<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">Devis #{{ item.id }}</h1>
        <div class="bg-gray-50 border rounded-xl p-5 mb-6">
          <div><strong>Contact:</strong> {{ item.contact_name }} ({{ item.email }})</div>
          <div><strong>TÃ©lÃ©phone:</strong> {{ item.phone || 'â€”' }}</div>
          <div><strong>Entreprise:</strong> {{ item.company_name || 'â€”' }}</div>
        </div>
        <div class="bg-white border rounded-xl p-5 mb-6">
          <div><strong>Projet:</strong> {{ item.project_name || 'â€”' }}</div>
          <div><strong>Type:</strong> {{ item.project_type || 'â€”' }}</div>
          <div><strong>Budget:</strong> {{ item.budget_range || 'â€”' }}</div>
          <div><strong>DÃ©lai:</strong> {{ item.deadline || 'â€”' }}</div>
        </div>
        <div class="bg-white border rounded-xl p-5">
          <p class="whitespace-pre-wrap">{{ item.details }}</p>
        </div>
        <div class="mt-6 flex items-center gap-3">
          <select v-model="status" class="border rounded px-3 py-2">
            <option value="new">Nouveau</option>
            <option value="in_progress">En cours</option>
            <option value="quoted">Devis envoyÃ©</option>
            <option value="closed">FermÃ©</option>
          </select>
          <button class="px-4 py-2 bg-diginova-red text-white rounded" @click="updateStatus">Mettre Ã  jour</button>
          <a href="/admin/quote-requests" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Retour</a>
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
  window.Inertia.put(`/admin/quote-requests/${props.item.id}`, { status: status.value })
}
</script>

