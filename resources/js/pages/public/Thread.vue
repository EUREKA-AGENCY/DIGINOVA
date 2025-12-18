<template>
  <AppLayout>
    <section class="bg-gray-50 py-14">
      <div class="container mx-auto max-w-5xl px-4">
        <!-- En-tête du sujet -->
        <header class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-neutral-100">
          <p class="text-xs font-semibold tracking-[0.25em] text-diginova-red/80 diginova-uppercase">
            SUJET DE LA COMMUNAUTÉ
          </p>
          <h1 class="mt-2 text-2xl font-semibold leading-snug text-neutral-900">
            {{ thread.title }}
          </h1>
          <div class="mt-3 flex flex-wrap items-center justify-between gap-3 text-xs text-neutral-500">
            <div class="flex items-center gap-2">
              <div
                class="flex h-7 w-7 items-center justify-center rounded-full bg-diginova-blue/10 text-[11px] font-semibold text-diginova-blue"
              >
                {{ thread.user.name.charAt(0) }}
              </div>
              <div class="leading-tight">
                <div class="flex items-center gap-1.5">
                  <span>Par</span>
                  <RouterLink
                    :href="route('members.show', thread.user.id)"
                    class="font-medium text-diginova-red hover:underline"
                  >
                    {{ thread.user.name }}
                  </RouterLink>
                </div>
                <p class="text-[11px] text-neutral-500">
                  Publié {{ thread.created_at }}
                </p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                type="button"
                class="inline-flex items-center gap-1.5 rounded-full bg-neutral-50 px-3 py-1 text-[11px] font-medium text-neutral-600 hover:bg-neutral-100"
                :class="{
                  'cursor-not-allowed opacity-60': !canReply,
                }"
                :disabled="!canReply || likeForm.processing"
                @click="toggleThreadLike"
              >
                <i
                  class="fas fa-heart text-[12px]"
                  :class="thread.liked ? 'text-diginova-red' : 'text-neutral-400'"
                ></i>
                <span>
                  {{ thread.likes_count }}
                  j'aime
                </span>
              </button>
              <div class="rounded-full bg-neutral-50 px-3 py-1 text-[11px] text-neutral-500">
                {{ replies.length }} réponse<span v-if="replies.length !== 1">s</span> dans cette discussion
              </div>
            </div>
          </div>
        </header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)]">
          <!-- Colonne principale : sujet + réponses -->
          <div class="space-y-5">
            <!-- Contenu du sujet -->
            <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-neutral-100">
              <p class="whitespace-pre-line text-sm leading-relaxed text-neutral-800">
                {{ thread.body }}
              </p>
            </article>

            <!-- Formulaire de réponse -->
            <div
              v-if="canReply"
              class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100"
            >
              <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Répondre au sujet
              </h2>
              <p class="mt-1 text-xs text-neutral-500">
                Partagez votre expérience, une piste de solution ou une question complémentaire.
              </p>
              <form @submit.prevent="submitReply" class="mt-3 space-y-3">
                <textarea
                  ref="replyTextarea"
                  v-model="replyForm.body"
                  rows="4"
                  class="w-full rounded-lg border border-neutral-300 bg-white text-sm shadow-sm focus:border-diginova-red focus:ring-diginova-red"
                  placeholder="Rédigez votre réponse..."
                />
                <p v-if="replyForm.errors.body" class="text-[11px] text-red-600">
                  {{ replyForm.errors.body }}
                </p>
                <div class="flex justify-end">
                  <PrimaryButton
                    :disabled="replyForm.processing"
                    class="px-5 py-2 text-xs font-semibold uppercase tracking-wide"
                  >
                    Publier ma réponse
                  </PrimaryButton>
                </div>
              </form>
            </div>
            <div
              v-else
              class="rounded-2xl border border-dashed border-neutral-300 bg-white p-5 text-xs text-neutral-600"
            >
              <p>
                Vous devez être connecté pour répondre.
                <RouterLink :href="route('login')" class="font-semibold text-diginova-red hover:underline">
                  Se connecter
                </RouterLink>
                ou
                <RouterLink :href="route('register')" class="font-semibold text-diginova-red hover:underline">
                  créer un compte
                </RouterLink>
                .
              </p>
            </div>

            <!-- Liste des réponses -->
            <section class="space-y-3">
              <h2 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Réponses de la communauté ({{ replies.length }})
              </h2>

              <div
                v-for="reply in rootReplies"
                :key="reply.id"
                class="rounded-2xl bg-white p-4 text-sm shadow-sm ring-1 ring-neutral-100"
              >
                <div class="mb-2 flex items-center justify-between text-[11px] text-neutral-500">
                  <div class="flex items-center gap-2">
                    <div
                      class="flex h-7 w-7 items-center justify-center rounded-full bg-diginova-blue/10 text-[11px] font-semibold text-diginova-blue"
                    >
                      {{ reply.user.name.charAt(0) }}
                    </div>
                    <div class="leading-tight">
                      <RouterLink
                        :href="route('members.show', reply.user.id)"
                        class="font-medium text-diginova-red hover:underline"
                      >
                        {{ reply.user.name }}
                      </RouterLink>
                      <p class="text-[11px] text-neutral-500">
                        Posté {{ reply.created_at }}
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <button
                      type="button"
                      class="inline-flex items-center gap-1.5 rounded-full bg-neutral-50 px-2.5 py-1 text-[11px] font-medium text-neutral-600 hover:bg-neutral-100"
                      :class="{
                        'cursor-not-allowed opacity-60': !canReply,
                      }"
                      :disabled="!canReply || replyLikeForm.processing"
                      @click="toggleReplyLike(reply)"
                    >
                      <i
                        class="fas fa-heart text-[11px]"
                        :class="reply.liked ? 'text-diginova-red' : 'text-neutral-400'"
                      ></i>
                      <span>{{ reply.likes_count }}</span>
                    </button>
                    <button
                      v-if="canReply"
                      type="button"
                      class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 px-2.5 py-1 text-[11px] font-medium text-neutral-600 hover:border-diginova-red hover:text-diginova-red"
                      @click="startReplyTo(reply)"
                    >
                      <i class="fas fa-reply text-[11px]"></i>
                      <span>Répondre</span>
                    </button>
                  </div>
                </div>
                <p class="whitespace-pre-line text-sm text-neutral-800">
                  {{ reply.body }}
                </p>
                <div
                  v-for="child in childReplies(reply.id)"
                  :key="child.id"
                  class="mt-3 ml-10 border-l-2 border-diginova-blue/10 pl-4"
                >
                  <div class="rounded-2xl bg-neutral-50 p-4 text-sm shadow-sm ring-1 ring-neutral-100">
                    <div class="mb-2 flex items-center justify-between text-[11px] text-neutral-500">
                    <div class="flex items-center gap-2">
                      <div
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-diginova-blue/10 text-[11px] font-semibold text-diginova-blue"
                      >
                        {{ child.user.name.charAt(0) }}
                      </div>
                      <div class="leading-tight">
                        <RouterLink
                          :href="route('members.show', child.user.id)"
                          class="font-medium text-diginova-red hover:underline"
                        >
                          {{ child.user.name }}
                        </RouterLink>
                        <p class="text-[11px] text-neutral-500">
                          Posté {{ child.created_at }}
                        </p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2">
                      <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-full bg-neutral-50 px-2.5 py-1 text-[11px] font-medium text-neutral-600 hover:bg-neutral-100"
                        :class="{
                          'cursor-not-allowed opacity-60': !canReply,
                        }"
                        :disabled="!canReply || replyLikeForm.processing"
                        @click="toggleReplyLike(child)"
                      >
                        <i
                          class="fas fa-heart text-[11px]"
                          :class="child.liked ? 'text-diginova-red' : 'text-neutral-400'"
                        ></i>
                        <span>{{ child.likes_count }}</span>
                      </button>
                      <button
                        v-if="canReply"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-full border border-neutral-200 px-2.5 py-1 text-[11px] font-medium text-neutral-600 hover:border-diginova-red hover:text-diginova-red"
                        @click="startReplyTo(child)"
                      >
                        <i class="fas fa-reply text-[11px]"></i>
                        <span>Répondre</span>
                      </button>
                    </div>
                  </div>
                    <p class="whitespace-pre-line text-sm text-neutral-800">
                      {{ child.body }}
                    </p>
                  </div>
                </div>
              </div>

              <p v-if="!replies.length" class="py-6 text-sm text-neutral-500">
                Aucune réponse pour le moment. Lancez la discussion !
              </p>
            </section>
          </div>

          <!-- Colonne latérale -->
          <aside class="space-y-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
              <h3 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Continuer la conversation
              </h3>
              <p class="mt-2 text-xs text-neutral-600">
                Restez courtois et précis dans vos réponses. Les échanges de qualité aident toute la communauté.
              </p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
              <h3 class="text-sm font-semibold text-neutral-900 diginova-uppercase">
                Revenir au forum
              </h3>
              <RouterLink
                :href="route('blogs.index')"
                class="mt-3 flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs font-medium text-neutral-800 hover:border-diginova-red hover:text-diginova-red"
              >
                <span>Tous les sujets</span>
                <span class="text-neutral-400">&larr;</span>
              </RouterLink>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, nextTick, computed } from 'vue'
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { useForm, Link as RouterLink } from '@inertiajs/vue3'

const props = defineProps({
  thread: {
    type: Object,
    required: true,
  },
  replies: {
    type: Array,
    default: () => [],
  },
  canReply: {
    type: Boolean,
    default: false,
  },
})

const rootReplies = computed(() => props.replies.filter((reply) => !reply.parent_reply_id))
const childReplies = (parentId) => props.replies.filter((reply) => reply.parent_reply_id === parentId)

const replyForm = useForm({
  body: '',
  parent_reply_id: null,
})

const replyTextarea = ref(null)

const likeForm = useForm({})
const replyLikeForm = useForm({})

const submitReply = () => {
  replyForm.post(route('blogs.comment', props.thread.id), {
    onSuccess: () => {
      replyForm.reset()
    },
    preserveScroll: true,
  })
}

const startReplyTo = (reply) => {
  const mention = `@${reply.user.name} `
  replyForm.body = replyForm.body ? `${replyForm.body}\n${mention}` : mention
  replyForm.parent_reply_id = reply.id

  nextTick(() => {
    if (replyTextarea.value) {
      replyTextarea.value.focus()
    }
  })
}

const toggleThreadLike = () => {
  if (!props.canReply) return

  if (props.thread.liked) {
    likeForm.delete(route('blogs.unlike', props.thread.id), {
      preserveScroll: true,
    })
  } else {
    likeForm.post(route('blogs.like', props.thread.id), {
      preserveScroll: true,
    })
  }
}

const toggleReplyLike = (reply) => {
  if (!props.canReply) return

  if (reply.liked) {
    replyLikeForm.delete(route('blogs.replies.unlike', reply.id), {
      preserveScroll: true,
    })
  } else {
    replyLikeForm.post(route('blogs.replies.like', reply.id), {
      preserveScroll: true,
    })
  }
}
</script>
