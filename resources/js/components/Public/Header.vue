<template>
  <header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <div class="flex items-center">
          <Logo class="h-10" />
        </div>

        <!-- Navigation principale (desktop) -->
        <nav class="hidden md:flex items-center space-x-8">
          <Link 
            v-for="item in navigation"
            :key="item.name"
            :href="item.href"
            class="nav-link"
            :class="{ 'active': $page.url.startsWith(item.href) }"
          >
            {{ item.name }}
          </Link>
        </nav>

        <!-- CTA et mobile menu toggle -->
        <div class="flex items-center gap-4">
          <CallToAction 
            text="Contact" 
            variant="outline-red" 
            size="sm"
            to="/contact"
            class="hidden md:inline-flex"
            @click="$inertia.visit('/contact')"
          />
          
          <button 
            @click="$emit('toggleMobileMenu')"
            class="md:hidden p-2 text-diginova-blue hover:text-diginova-red"
            aria-label="Menu mobile"
          >
            <i class="bi bi-list text-2xl"></i>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Logo from '../Logo.vue';
import CallToAction from './CallToAction.vue';

defineProps<{
  navigation: Array<{
    name: string
    href: string
  }>
}>()

defineEmits(['toggleMobileMenu'])
</script>

<style scoped>
.nav-link {
  @apply diginova-uppercase text-sm font-bold text-diginova-blue hover:text-diginova-red transition-colors px-2 py-1;
  
  &.active {
    @apply text-diginova-red border-b-2 border-diginova-red;
  }
}
</style>
