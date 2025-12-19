<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">{{ item?.id ? 'Modifier' : 'CrÃ©er' }} un article</h1>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div>
              <label class="block text-sm text-gray-600">Titre</label>
              <input v-model="form.title" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Slug</label>
                <input v-model="form.slug" class="border rounded px-3 py-2 w-full" required />
              </div>
              <div>
                <label class="block text-sm text-gray-600">CatÃ©gorie</label>
                <input v-model="form.category" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Extrait</label>
              <textarea v-model="form.excerpt" class="border rounded px-3 py-2 w-full" rows="2" />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Contenu (Markdown/HTML)</label>
              <textarea v-model="form.content" class="border rounded px-3 py-2 w-full" rows="8" required />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Image de couverture (URL)</label>
              <input v-model="form.cover_image" class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Publication</label>
                <input type="datetime-local" v-model="form.published_at" class="border rounded px-3 py-2 w-full" />
              </div>
              <div class="flex items-center gap-2 mt-6">
                <input id="is_published" type="checkbox" v-model="form.is_published" />
                <label for="is_published">PubliÃ©</label>
              </div>
            </div>
          </div>
          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-diginova-red text-white rounded">Enregistrer</button>
            <a href="/admin/posts" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Annuler</a>
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
  excerpt: props.item.excerpt || '',
  content: props.item.content || '',
  cover_image: props.item.cover_image || '',
  published_at: props.item.published_at ? props.item.published_at.substring(0,16) : '',
  is_published: !!props.item.is_published,
})
const submit = () => {
  const method = props.item?.id ? 'put' : 'post'
  const url = props.item?.id ? `/admin/posts/${props.item.id}` : '/admin/posts'
  window.Inertia[method](url, form)
}
</script>

