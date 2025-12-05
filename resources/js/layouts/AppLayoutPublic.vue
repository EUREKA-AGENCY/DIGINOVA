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
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Header from '@/components/Public/Header.vue'
import MobileMenu from '@/components/Public/MobileMenu.vue'
import Footer from '@/components/Public/Footer.vue'

const isMobileMenuOpen = ref(false)

const page = usePage()

const navigation = computed(() => {
  const items = [
    { name: 'ACCUEIL', href: '/' },
    { name: 'SERVICES', href: '/services' },
    { name: 'RÉALISATIONS', href: '/achievements' },
    { name: 'FORUM', href: '/blogs' },
    { name: 'À PROPOS', href: '/about' },
  ]

  const user = page.props.auth?.user as { id: number } | null | undefined

  items.push({
    name: user ? 'ESPACE MEMBRE' : 'CONNEXION',
    href: user ? '/dashboard' : '/login',
  })

  return items
})
</script>
