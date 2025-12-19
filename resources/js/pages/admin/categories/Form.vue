<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">{{ item?.id ? 'Modifier' : 'CrÃ©er' }} une catÃ©gorie</h1>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div>
              <label class="block text-sm text-gray-600">Nom</label>
              <input v-model="form.name" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Slug</label>
              <input v-model="form.slug" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Parent</label>
              <select v-model="form.parent_id" class="border rounded px-3 py-2 w-full">
                <option :value="null">â€”</option>
                <option v-for="c in parents" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
          </div>
          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-diginova-red text-white rounded">Enregistrer</button>
            <a href="/admin/categories" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Annuler</a>
          </div>
        </form>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { reactive } from 'vue'
const props = defineProps({ item: { type: Object, default: () => ({}) }, parents: { type: Array, default: () => [] } })
const form = reactive({
  name: props.item.name || '',
  slug: props.item.slug || '',
  parent_id: props.item.parent_id || null,
})
const submit = () => {
  const method = props.item?.id ? 'put' : 'post'
  const url = props.item?.id ? `/admin/categories/${props.item.id}` : '/admin/categories'
  window.Inertia[method](url, form)
}
</script>

