<template>
  <AppLayout>
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 max-w-3xl">
        <a href="/blog" class="text-diginova-red">← Retour au blog</a>
        <h1 class="text-3xl font-bold text-diginova-blue mt-2">{{ post.title }}</h1>
        <div class="text-gray-500 text-sm mt-1" v-if="post.published_at">{{ new Date(post.published_at).toLocaleDateString('fr-FR') }}</div>
        <img v-if="post.cover_image" :src="post.cover_image" alt="" class="mt-6 rounded-xl border" />
        <div class="prose prose-lg mt-6 max-w-none" v-html="html"></div>
      </div>
    </section>
  </AppLayout>
  </template>

<script setup>
import AppLayout from '@/layouts/AppLayoutPublic.vue'
import { computed } from 'vue'
const props = defineProps({ post: { type: Object, required: true } })

const md = (src = '') => {
  let out = src
  out = out.replace(/^###\s+(.+)$/gm, '<h3>$1</h3>')
           .replace(/^##\s+(.+)$/gm, '<h2>$1</h2>')
           .replace(/^#\s+(.+)$/gm, '<h1>$1</h1>')
           .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
           .replace(/\*(.+?)\*/g, '<em>$1</em>')
           .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>')
           .replace(/\n{2,}/g, '</p><p>')
  return `<p>${out}</p>`
}

const html = computed(() => {
  if (!props.post) return ''
  const content = props.post.content || ''
  // Simple heuristic: if contains HTML tags, use as-is; else parse markdown
  return /<[^>]+>/.test(content) ? content : md(content)
})
</script>
