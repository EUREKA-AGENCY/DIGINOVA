<template>
  <AppLayout>
    <section class="bg-gray-50 py-14">
      <div class="container mx-auto max-w-6xl px-4">
        <!-- En-tête forum -->
        <header
          class="mb-8 flex flex-col gap-4 rounded-2xl bg-gradient-to-r from-diginova-blue via-diginova-blue/95 to-diginova-red/80 px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-7"
        >
          <div>
            <p class="text-xs font-semibold tracking-[0.25em] text-white/60">
              FORUM COMMUNAUTAIRE
            </p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight">
              Échanges de la communauté DIGINOVA
            </h1>
            <p class="mt-1 text-sm text-white/80">
              Posez vos questions, partagez vos retours d’expérience et bénéficiez des conseils des autres membres.
            </p>
            <p
              v-if="!canCreate"
              class="mt-3 text-xs text-white/80"
            >
              <RouterLink :href="route('login')" class="font-semibold text-white hover:underline">
                Connectez-vous
              </RouterLink>
              ou
              <RouterLink :href="route('register')" class="font-semibold text-white hover:underline">
                créez un compte
              </RouterLink>
              pour lancer un sujet ou répondre.
            </p>
          </div>
          <div class="flex flex-col gap-3 sm:items-end">
            <PrimaryButton
              v-if="canCreate"
              class="inline-flex items-center justify-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-wide"
              @click="showNewThread = true"
            >
              Lancer un nouveau sujet
            </PrimaryButton>
            <p class="text-[11px] text-white/70">
              {{ threads.data.length }} sujet<span v-if="threads.data.length !== 1">s</span> affiché<span v-if="threads.data.length !== 1">s</span>
            </p>
          </div>
        </header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)]">
          <!-- Colonne principale : formulaire + liste -->
          <div class="space-y-6">
            <!-- Formulaire de nouveau sujet -->
            <div
              v-if="canCreate && showNewThread"
              class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm"
            >
              <h2 class="mb-2 text-sm font-semibold text-neutral-900 diginova-uppercase">
                Créer un nouveau sujet
              </h2>
              <p class="mb-4 text-xs text-neutral-500">
                Présentez votre contexte et votre question de manière claire pour obtenir des réponses utiles.
              </p>
              <form @submit.prevent="submitNewThread" class="space-y-4">
                <div>
                  <label class="mb-1 block text-xs font-medium text-neutral-700">Titre du sujet</label>
                  <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-lg border border-neutral-300 bg-white text-sm shadow-sm focus:border-diginova-red focus:ring-diginova-red"
                    placeholder="Ex: Retour d’expérience sur l’intégration d’un CRM avec DIGINOVA"
                  />
                  <p v-if="form.errors.title" class="mt-1 text-[11px] text-red-600">
                    {{ form.errors.title }}
                  </p>
                </div>
                <div>
                  <label class="mb-1 block text-xs font-medium text-neutral-700">Message</label>
                  <textarea
                    v-model="form.body"
                    rows="5"
                    class="w-full rounded-lg border border-neutral-300 bg-white text-sm shadow-sm focus:border-diginova-red focus:ring-diginova-red"
                    placeholder="Décrivez votre contexte, vos objectifs et les points sur lesquels vous souhaitez des retours..."
                  />
                  <p v-if="form.errors.body" class="mt-1 text-[11px] text-red-600">
                    {{ form.errors.body }}
                  </p>
                </div>
                <div class="flex items-center justify-end gap-3">
                  <button
                    type="button"
                    class="text-xs font-medium text-neutral-500 hover:text-neutral-700"
                    @click="cancelNewThread"
                  >
                    Annuler
                  </button>
                  <PrimaryButton :disabled="form.processing" class="px-5 py-2 text-xs font-semibold uppercase tracking-wide">
                    Publier
                  </PrimaryButton>
                </div>
              </form>
            </div>

            <!-- Liste des sujets -->
            <div class="space-y-3">
              <div
                v-for="thread in threads.data"
                :key="thread.id"
                class="group rounded-2xl border border-neutral-200 bg-white p-5 text-sm shadow-sm transition hover:-translate-y-0.5 hover:border-diginova-red/40 hover:shadow-md"
              >
                <RouterLink
                  :href="route('blogs.show', thread.id)"
                  class="block"
                >
                  <h2 class="text-base font-semibold text-neutral-900 group-hover:text-diginova-red">
                    {{ thread.title }}
                  </h2>
                  <p class="mt-2 line-clamp-2 text-xs text-neutral-600">
                    {{ thread.excerpt }}
                  </p>
                </RouterLink>

                <div class="mt-3 flex items-center justify-between text-[11px] text-neutral-500">
                  <div class="flex items-center gap-2">
                    <span>Par</span>
                    <RouterLink
                      :href="route('members.show', thread.user.id)"
                      class="font-medium text-diginova-red hover:underline"
                    >
                      {{ thread.user.name }}
                    </RouterLink>
                    <span class="text-neutral-400">•</span>
                    <span>Publié {{ thread.created_at }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fas fa-comments text-neutral-400"></i>
                    <span>
                      {{ thread.replies_count }} réponse<span v-if="thread.replies_count !== 1">s</span>
                    </span>
                  </div>
                </div>
              </div>

              <p v-if="!threads.data.length" class="py-10 text-center text-sm text-neutral-500">
                Aucun sujet pour le moment. Soyez le premier à lancer une discussion !
              </p>
            </div>
          </div>

          <!-- Colonne latérale -->
          <aside class="space-y-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
              <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Conseils pour un bon sujet
              </h2>
              <ul class="mt-3 space-y-1.5 text-xs text-neutral-600">
                <li>• Choisissez un titre précis et descriptif.</li>
                <li>• Expliquez le contexte de votre projet.</li>
                <li>• Détaillez votre question ou votre objectif.</li>
                <li>• Ajoutez ce que vous avez déjà essayé.</li>
              </ul>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
              <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Naviguer dans le forum
              </h2>
              <div class="mt-3 space-y-2 text-xs">
                <RouterLink
                  :href="route('blogs.index')"
                  class="flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 font-medium text-neutral-800 hover:border-diginova-red hover:text-diginova-red"
                >
                  <span>Voir tous les sujets</span>
                  <span class="text-neutral-400">&rarr;</span>
                </RouterLink>
                <RouterLink
                  v-if="canCreate"
                  :href="route('blogs.index', { new: 1 })"
                  class="flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 font-medium text-neutral-800 hover:border-diginova-red hover:text-diginova-red"
                >
                  <span>Lancer une nouvelle discussion</span>
                  <span class="text-neutral-400">&rarr;</span>
                </RouterLink>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router as inertiaRouter } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { Link as RouterLink } from '@inertiajs/vue3'

const props = defineProps({
  threads: {
    type: Object,
    required: true,
  },
  canCreate: {
    type: Boolean,
    default: false,
  },
  showNewForm: {
    type: Boolean,
    default: false,
  },
})

const showNewThread = ref(props.showNewForm)

const form = useForm({
  title: '',
  body: '',
})

const submitNewThread = () => {
  form.post(route('blogs.store'), {
    onSuccess: () => {
      form.reset()
      showNewThread.value = false
    },
  })
}

const cancelNewThread = () => {
  form.reset()
  showNewThread.value = false
}
</script>
