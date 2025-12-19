<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r hidden md:flex md:flex-col">
      <div class="h-16 border-b flex items-center px-4 font-bold text-diginova-blue">
        <i class="bi bi-speedometer2 mr-2"></i> Admin
      </div>
      <nav class="flex-1 overflow-y-auto p-3 text-sm">
        <a v-for="item in nav" :key="item.href" :href="item.href"
           :class="['flex items-center gap-2 rounded px-3 py-2 mb-1', isActive(item.href) ? 'bg-diginova-red text-white' : 'text-gray-700 hover:bg-gray-50']">
          <i :class="['bi', item.icon]" />
          <span>{{ item.label }}</span>
        </a>
      </nav>
      <div class="border-t p-3 text-xs text-gray-500">
        © {{ new Date().getFullYear() }} {{ appName }}
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar -->
      <header class="h-16 bg-white border-b flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
          <button class="md:hidden px-3 py-2 border rounded" @click="sidebarOpen = !sidebarOpen">
            <i class="bi bi-list"></i>
          </button>
          <a href="/" class="font-semibold text-diginova-blue">{{ appName }}</a>
        </div>
        <div class="text-sm text-gray-600">
          <i class="bi bi-person-circle mr-1"></i>
          {{ user?.name || 'Utilisateur' }}
        </div>
      </header>

      <!-- Mobile sidebar -->
      <transition name="fade">
        <div v-if="sidebarOpen" class="md:hidden fixed inset-0 z-40 bg-black/40" @click="sidebarOpen=false">
          <aside class="absolute left-0 top-0 bottom-0 w-64 bg-white shadow-lg p-3">
            <div class="h-12 flex items-center justify-between border-b mb-3">
              <span class="font-bold">Admin</span>
              <button class="p-2" @click="sidebarOpen=false"><i class="bi bi-x"></i></button>
            </div>
            <nav class="text-sm">
              <a v-for="item in nav" :key="item.href" :href="item.href"
                 :class="['flex items-center gap-2 rounded px-3 py-2 mb-1', isActive(item.href) ? 'bg-diginova-red text-white' : 'text-gray-700 hover:bg-gray-50']">
                <i :class="['bi', item.icon]" />
                <span>{{ item.label }}</span>
              </a>
            </nav>
          </aside>
        </div>
      </transition>

      <!-- Content -->
      <main class="flex-1 p-4">
        <slot />
      </main>

      <!-- Footer -->
      <footer class="border-t bg-white px-4 py-3 text-xs text-gray-500">
        Fait avec ❤️ par {{ appName }}
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
const appName = import.meta.env.VITE_APP_NAME || 'Diginova'
const user = computed(() => (typeof window !== 'undefined' ? window?.Inertia?.page?.props?.auth?.user : null))
const sidebarOpen = ref(false)

const nav = [
  { label: 'Tableau de bord', href: '/admin', icon: 'bi-speedometer2' },
  { label: 'Services', href: '/admin/services', icon: 'bi-kanban' },
  { label: 'Produits', href: '/admin/products', icon: 'bi-box-seam' },
  { label: 'Catégories', href: '/admin/categories', icon: 'bi-tags' },
  { label: 'Articles', href: '/admin/posts', icon: 'bi-journal-text' },
  { label: 'Témoignages', href: '/admin/testimonials', icon: 'bi-chat-quote' },
  { label: 'Contacts', href: '/admin/contact-requests', icon: 'bi-envelope' },
  { label: 'Devis', href: '/admin/quote-requests', icon: 'bi-receipt' },
]

const isActive = (href) => {
  if (typeof window === 'undefined') return false
  const path = window.location.pathname
  return path === href || path.startsWith(href + '/')
}
</script>

<style scoped>
.fade-enter-active,.fade-leave-active{ transition: opacity .15s ease }
.fade-enter-from,.fade-leave-to{ opacity: 0 }
</style>

