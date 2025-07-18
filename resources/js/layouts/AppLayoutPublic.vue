<template>
  <div class="min-h-screen flex flex-col bg-white">
    <Header 
      :navigation="navigation" 
      @toggleMobileMenu="isMobileMenuOpen = !isMobileMenuOpen"
    />
    
    <MobileMenu 
      :isOpen="isMobileMenuOpen" 
      :navigation="navigation"
      @close="isMobileMenuOpen = false"
    />

    <main class="flex-grow">
      <slot />
    </main>

    <Footer :navigation="navigation" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Header from '@/components/Public/Header.vue'
import MobileMenu from '@/components/Public/MobileMenu.vue'
import Footer from '@/components/Public/Footer.vue'

const isMobileMenuOpen = ref(false)

const navigation = [
  { name: 'ACCUEIL', href: '/' },
  { name: 'SERVICES', href: '/services' },
  { name: 'RÉALISATIONS', href: '/portfolio' },
  { name: 'BLOG', href: '/blog' },
  { name: 'À PROPOS', href: '/about' },
]
</script>
