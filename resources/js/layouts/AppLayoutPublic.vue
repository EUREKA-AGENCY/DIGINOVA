<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const scrolled = ref(false)
const mobileOpen = ref(false)

function handleScroll() {
    scrolled.value = window.scrollY > 60
}

onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const navLinks = [
    { label: 'Services', href: 'services' },
    { label: 'Réalisations', href: 'realisations' },
    { label: 'Processus', href: 'processus' },
    { label: 'Contact', href: 'contact' },
]

function smoothScroll(id) {
    mobileOpen.value = false
    const el = document.getElementById(id)
    if (!el) return
    const top = el.getBoundingClientRect().top + window.scrollY - 72
    window.scrollTo({ top, behavior: 'smooth' })
}
</script>

<template>
    <div class="min-h-screen bg-white">

        <!-- ── Navbar ── -->
        <header
            :class="[
                'fixed top-0 inset-x-0 z-50 transition-all duration-300',
                scrolled
                    ? 'bg-[#070E24]/96 backdrop-blur-md shadow-2xl border-b border-white/5'
                    : 'bg-transparent'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">

                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-3 flex-shrink-0 cursor-pointer">
                        <img src="/logo-dark.svg" alt="Diginova" class="h-8 w-auto" />
                        <span class="text-white font-bold text-lg tracking-tight hidden sm:block" style="font-family:'Poppins',sans-serif">
                            Diginova
                        </span>
                    </a>

                    <!-- Desktop nav -->
                    <nav class="hidden md:flex items-center gap-8">
                        <button
                            v-for="link in navLinks"
                            :key="link.href"
                            @click="smoothScroll(link.href)"
                            class="text-white/70 hover:text-[#00D8E8] text-sm font-medium transition-colors duration-200 cursor-pointer bg-transparent border-0"
                        >
                            {{ link.label }}
                        </button>
                    </nav>

                    <!-- CTA -->
                    <button
                        @click="smoothScroll('contact')"
                        class="hidden md:inline-flex items-center gap-2 bg-[#00D8E8] hover:bg-[#00C2D0] text-[#070E24] font-semibold text-sm px-5 py-2.5 rounded-lg transition-all duration-200 cursor-pointer shadow-lg shadow-[#00D8E8]/25 border-0"
                    >
                        Démarrer un projet
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7 7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Mobile burger -->
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="md:hidden text-white p-2 cursor-pointer rounded-lg hover:bg-white/10 transition-colors border-0 bg-transparent"
                        aria-label="Menu"
                    >
                        <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div
                v-show="mobileOpen"
                class="md:hidden bg-[#070E24]/98 backdrop-blur-md border-t border-white/10 px-4 py-4 space-y-1"
            >
                <button
                    v-for="link in navLinks"
                    :key="link.href"
                    @click="smoothScroll(link.href)"
                    class="flex w-full items-center text-white/80 hover:text-[#00D8E8] py-3 px-3 rounded-lg hover:bg-white/5 transition-colors duration-200 cursor-pointer text-sm font-medium bg-transparent border-0"
                >
                    {{ link.label }}
                </button>
                <button
                    @click="smoothScroll('contact')"
                    class="flex w-full items-center justify-center mt-2 bg-[#00D8E8] text-[#070E24] font-semibold text-sm px-4 py-3 rounded-lg transition-colors duration-200 cursor-pointer border-0"
                >
                    Démarrer un projet
                </button>
            </div>
        </header>

        <!-- ── Page content ── -->
        <slot />

        <!-- ── Footer ── -->
        <footer class="bg-[#070E24] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">

                    <!-- Brand -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/logo-dark.svg" alt="Diginova" class="h-8 w-auto" />
                            <span class="font-bold text-xl" style="font-family:'Poppins',sans-serif">Diginova</span>
                        </div>
                        <p class="text-white/55 text-sm leading-relaxed mb-4">
                            Solutions digitales sur mesure pour les entreprises ambitieuses.
                            Web, SaaS, DevOps — de la conception au déploiement.
                        </p>
                        <p class="text-xs text-white/35">
                            Une initiative <span class="text-[#00D8E8]">Eureka Agency</span> · Yaoundé, Cameroun
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="font-semibold text-xs text-white/45 mb-5 uppercase tracking-widest">Navigation</h3>
                        <ul class="space-y-3">
                            <li v-for="link in navLinks" :key="link.href">
                                <button
                                    @click="smoothScroll(link.href)"
                                    class="text-white/60 hover:text-[#00D8E8] text-sm transition-colors duration-200 cursor-pointer bg-transparent border-0"
                                >
                                    {{ link.label }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="font-semibold text-xs text-white/45 mb-5 uppercase tracking-widest">Contact</h3>
                        <ul class="space-y-3 mb-6">
                            <li>
                                <a href="tel:+237655065494" class="flex items-center gap-2.5 text-white/60 hover:text-[#00D8E8] text-sm transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-[#00D8E8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    +237 655 065 494
                                </a>
                            </li>
                            <li>
                                <a href="mailto:penlapsaturin@gmail.com" class="flex items-center gap-2.5 text-white/60 hover:text-[#00D8E8] text-sm transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-[#00D8E8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    penlapsaturin@gmail.com
                                </a>
                            </li>
                            <li class="flex items-center gap-2.5 text-white/60 text-sm">
                                <svg class="w-4 h-4 text-[#00D8E8] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Yaoundé, Cameroun
                            </li>
                        </ul>

                        <!-- Social links -->
                        <div class="flex items-center gap-3">
                            <a
                                href="https://wa.me/237655065494"
                                target="_blank"
                                rel="noopener"
                                aria-label="WhatsApp"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/5 hover:bg-[#00D8E8]/15 text-white/50 hover:text-[#00D8E8] transition-all duration-200 cursor-pointer"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>
                            <a
                                href="https://github.com/EUREKA-AGENCY"
                                target="_blank"
                                rel="noopener"
                                aria-label="GitHub"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/5 hover:bg-[#00D8E8]/15 text-white/50 hover:text-[#00D8E8] transition-all duration-200 cursor-pointer"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-white/35 text-xs">
                        © {{ new Date().getFullYear() }} Diginova · Eureka Agency. Tous droits réservés.
                    </p>
                    <p class="text-white/25 text-xs">Conçu &amp; développé par Eureka Agency, Yaoundé 🇨🇲</p>
                </div>
            </div>
        </footer>
    </div>
</template>
