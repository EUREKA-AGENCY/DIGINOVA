<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">{{ item?.id ? 'Modifier' : 'CrÃ©er' }} un service</h1>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div>
              <label class="block text-sm text-gray-600">Titre</label>
              <input v-model="form.title" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Slug</label>
              <input v-model="form.slug" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">CatÃ©gorie</label>
                <input v-model="form.category" class="border rounded px-3 py-2 w-full" />
              </div>
              <div>
                <label class="block text-sm text-gray-600">IcÃ´ne (bootstrap-icons)</label>
                <input v-model="form.icon" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Extrait</label>
              <textarea v-model="form.excerpt" class="border rounded px-3 py-2 w-full" rows="2" />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Contenu</label>
              <textarea v-model="form.content" class="border rounded px-3 py-2 w-full" rows="6" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Ordre</label>
                <input type="number" v-model.number="form.order" class="border rounded px-3 py-2 w-full" />
              </div>
              <div class="flex items-center gap-2 mt-6">
                <input id="is_active" type="checkbox" v-model="form.is_active" />
                <label for="is_active">Actif</label>
              </div>
            </div>
          </div>
          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-diginova-red text-white rounded">Enregistrer</button>
            <a href="/admin/services" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Annuler</a>
          </div>
        </form>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { reactive } from 'vue'
const props = defineProps({ item: { type: Object, default: () => ({}) } })
const form = reactive({
  title: props.item.title || '',
  slug: props.item.slug || '',
  category: props.item.category || '',
  icon: props.item.icon || '',
  excerpt: props.item.excerpt || '',
  content: props.item.content || '',
  order: props.item.order ?? 0,
  is_active: !!props.item.is_active,
})

const submit = () => {
  const routeName = props.item?.id ? 'admin.services.update' : 'admin.services.store'
  const method = props.item?.id ? 'put' : 'post'
  const url = props.item?.id ? `/admin/services/${props.item.id}` : '/admin/services'
  window.Inertia[method](url, form)
}
</script>

