<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">{{ item?.id ? 'Modifier' : 'CrÃ©er' }} un tÃ©moignage</h1>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Auteur</label>
                <input v-model="form.author_name" class="border rounded px-3 py-2 w-full" required />
              </div>
              <div>
                <label class="block text-sm text-gray-600">RÃ´le</label>
                <input v-model="form.author_role" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Entreprise</label>
                <input v-model="form.company" class="border rounded px-3 py-2 w-full" />
              </div>
              <div>
                <label class="block text-sm text-gray-600">Note (1-5)</label>
                <input type="number" min="1" max="5" v-model.number="form.rating" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Contenu</label>
              <textarea v-model="form.content" class="border rounded px-3 py-2 w-full" rows="5" required />
            </div>
            <div class="flex items-center gap-2">
              <input id="is_published" type="checkbox" v-model="form.is_published" />
              <label for="is_published">PubliÃ©</label>
            </div>
          </div>
          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-diginova-red text-white rounded">Enregistrer</button>
            <a href="/admin/testimonials" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Annuler</a>
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
  author_name: props.item.author_name || '',
  author_role: props.item.author_role || '',
  company: props.item.company || '',
  rating: props.item.rating ?? null,
  content: props.item.content || '',
  is_published: !!props.item.is_published,
})
const submit = () => {
  const method = props.item?.id ? 'put' : 'post'
  const url = props.item?.id ? `/admin/testimonials/${props.item.id}` : '/admin/testimonials'
  window.Inertia[method](url, form)
}
</script>

