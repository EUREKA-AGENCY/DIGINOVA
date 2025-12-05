<template>
  <AppLayout>
    <section class="bg-gray-50 py-14">
      <div class="container mx-auto max-w-5xl px-4">
        <!-- En-tête profil -->
        <header
          class="mb-8 flex flex-col gap-4 rounded-2xl bg-gradient-to-r from-diginova-blue via-diginova-blue/95 to-diginova-red/80 px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-7"
        >
          <div class="flex items-center gap-4">
            <div
              class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-base font-semibold text-white"
            >
              {{ member.name.charAt(0) }}
            </div>
            <div class="leading-tight">
              <p class="text-xs font-medium tracking-[0.25em] text-white/60 diginova-uppercase">
                PROFIL MEMBRE
              </p>
              <h1 class="mt-1 text-xl font-semibold tracking-tight">
                {{ member.name }}
              </h1>
              <p class="mt-1 text-xs text-white/80">
                Activité publique sur le forum DIGINOVA.
              </p>
            </div>
          </div>
          <div class="flex flex-wrap gap-3">
            <PrimaryButton
              v-if="$page.props.auth?.user && $page.props.auth.user.id === member.id"
              class="rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-wide"
              @click="$inertia.visit(route('blogs.index', { new: 1 }))"
            >
              Lancer un nouveau sujet
            </PrimaryButton>
          </div>
        </header>

        <!-- Contenu -->
        <div class="grid gap-6 md:grid-cols-2">
          <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
            <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
              Sujets créés
            </h2>
            <div class="mt-4 space-y-3 text-sm">
              <div
                v-for="thread in threads"
                :key="thread.id"
                class="rounded-xl border border-neutral-200 bg-neutral-50/80 p-4 shadow-sm transition hover:border-diginova-red/40 hover:bg-white"
              >
                <RouterLink
                  :href="route('blogs.show', thread.id)"
                  class="font-medium text-neutral-900 hover:text-diginova-red"
                >
                  {{ thread.title }}
                </RouterLink>
                <div class="mt-1 text-[11px] text-neutral-500">
                  {{ thread.replies_count }} réponse<span v-if="thread.replies_count !== 1">s</span>
                  • {{ thread.created_at }}
                </div>
              </div>

              <p v-if="!threads.length" class="text-xs text-neutral-500">
                Aucun sujet créé pour le moment.
              </p>
            </div>
          </section>

          <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
            <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
              Dernières réponses
            </h2>
            <div class="mt-4 space-y-3 text-sm">
              <div
                v-for="reply in replies"
                :key="reply.id"
                class="rounded-xl border border-neutral-200 bg-neutral-50/80 p-4 shadow-sm transition hover:border-diginova-red/40 hover:bg-white"
              >
                <p class="mb-1 text-neutral-800">
                  {{ reply.body }}
                </p>
                <div class="mt-1 text-[11px] text-neutral-500">
                  Sur
                  <RouterLink
                    :href="route('blogs.show', reply.thread.id)"
                    class="font-medium text-diginova-red hover:underline"
                  >
                    {{ reply.thread.title }}
                  </RouterLink>
                  • {{ reply.created_at }}
                </div>
              </div>

              <p v-if="!replies.length" class="text-xs text-neutral-500">
                Aucune réponse pour le moment.
              </p>
            </div>
          </section>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import { Link as RouterLink } from '@inertiajs/vue3'
import PrimaryButton from '@/components/PrimaryButton.vue'

defineProps({
  member: {
    type: Object,
    required: true,
  },
  threads: {
    type: Array,
    default: () => [],
  },
  replies: {
    type: Array,
    default: () => [],
  },
})
</script>
