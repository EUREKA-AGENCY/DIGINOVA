<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0 -translate-y-4"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 -translate-y-4"
  >
    <div 
      v-if="isOpen"
      class="md:hidden bg-white border-b border-gray-100 shadow-md"
    >
      <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col space-y-4">
          <Link 
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="mobile-nav-link"
            :class="{ 'active': $page.url.startsWith(item.href) }"
            @click="$emit('close')"
          >
            {{ item.name }}
          </Link>
          <CallToAction 
            text="Contact" 
            variant="red" 
            size="sm"
            to="/contact"
            class="mt-4"
            @click="$emit('close')"
          />
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import CallToAction from './CallToAction.vue'

defineProps<{
  isOpen: boolean
  navigation: Array<{
    name: string
    href: string
  }>
}>()

defineEmits(['close'])
</script>

<style scoped>
.mobile-nav-link {
  @apply diginova-uppercase text-diginova-blue hover:text-diginova-red py-2 px-4 font-medium;
  
  &.active {
    @apply text-diginova-red bg-diginova-red/10 rounded-lg;
  }
}
</style>