<!-- resources/js/Pages/Public/Contact.vue -->
<template>
  <AppLayout>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label>Nom complet</label>
        <input v-model="form.name" type="text">
        <p v-if="errors.name">{{ errors.name }}</p>
      </div>
      <!-- Autres champs -->
      <button type="submit" :disabled="form.processing">Envoyer</button>
    </form>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  message: ''
})

const submit = () => {
  form.post(route('contact.submit'))
}
</script>
