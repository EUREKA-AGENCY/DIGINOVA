<template>
  <AdminLayout>
    <section class="py-10 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <h1 class="text-2xl font-bold text-diginova-blue mb-6">{{ item?.id ? 'Modifier' : 'CrÃ©er' }} un produit</h1>
        <form @submit.prevent="submit">
          <div class="grid gap-4">
            <div>
              <label class="block text-sm text-gray-600">Nom</label>
              <input v-model="form.name" class="border rounded px-3 py-2 w-full" required />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Slug</label>
                <input v-model="form.slug" class="border rounded px-3 py-2 w-full" required />
              </div>
              <div>
                <label class="block text-sm text-gray-600">Type</label>
                <select v-model="form.product_type" class="border rounded px-3 py-2 w-full">
                  <option value="sale">Vente</option>
                  <option value="rental">Location</option>
                  <option value="service">Service</option>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">CatÃ©gorie</label>
                <select v-model="form.category_id" class="border rounded px-3 py-2 w-full">
                  <option :value="null">â€”</option>
                  <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm text-gray-600">SKU</label>
                <input v-model="form.sku" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Prix</label>
                <input type="number" step="0.01" v-model.number="form.price" class="border rounded px-3 py-2 w-full" />
              </div>
              <div>
                <label class="block text-sm text-gray-600">Loyer mensuel</label>
                <input type="number" step="0.01" v-model.number="form.rent_price" class="border rounded px-3 py-2 w-full" />
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm text-gray-600">Stock</label>
                <input type="number" v-model.number="form.stock" class="border rounded px-3 py-2 w-full" />
              </div>
              <div class="flex items-center gap-2 mt-6">
                <input id="is_active" type="checkbox" v-model="form.is_active" />
                <label for="is_active">Actif</label>
              </div>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Description courte</label>
              <textarea v-model="form.short_description" class="border rounded px-3 py-2 w-full" rows="2" />
            </div>
            <div>
              <label class="block text-sm text-gray-600">Description</label>
              <textarea v-model="form.description" class="border rounded px-3 py-2 w-full" rows="6" />
            </div>
          </div>
          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-diginova-red text-white rounded">Enregistrer</button>
            <a href="/admin/products" class="px-4 py-2 border border-diginova-red text-diginova-red rounded">Annuler</a>
          </div>
        </form>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { reactive } from 'vue'
const props = defineProps({ item: { type: Object, default: () => ({}) }, categories: { type: Array, default: () => [] } })
const form = reactive({
  name: props.item.name || '',
  slug: props.item.slug || '',
  product_type: props.item.product_type || 'sale',
  category_id: props.item.category_id || null,
  sku: props.item.sku || '',
  price: props.item.price ?? null,
  rent_price: props.item.rent_price ?? null,
  stock: props.item.stock ?? 0,
  short_description: props.item.short_description || '',
  description: props.item.description || '',
  is_active: !!props.item.is_active,
})
const submit = () => {
  const method = props.item?.id ? 'put' : 'post'
  const url = props.item?.id ? `/admin/products/${props.item.id}` : '/admin/products'
  window.Inertia[method](url, form)
}
</script>

