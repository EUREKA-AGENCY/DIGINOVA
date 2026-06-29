<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { ArrowRight, ChevronDown, Menu, X, Phone, Mail, MapPin, Github } from 'lucide-vue-next'

const scrolled = ref(false)
const mobileOpen = ref(false)
const page = usePage()
const isHome = computed(() => page.component === 'Home')

function handleScroll() {
    scrolled.value = window.scrollY > 60
}

onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))

const navLinks = [
    { label: 'Services', href: 'services', type: 'anchor' },
    { label: 'Réalisations', href: 'realisations', type: 'anchor' },
    { label: 'Messagerie Pro', href: '/messagerie-pro', type: 'page' },
    { label: 'SMS Pro', href: '/sms', type: 'page' },
    { label: 'Hébergement', href: '/hebergement', type: 'page' },
    { label: 'Processus', href: 'processus', type: 'anchor' },
    { label: 'Contact', href: 'contact', type: 'anchor' },
]

function anchorHref(id) {
    return isHome.value ? `#${id}` : `/#${id}`
}

function isActive(link) {
    return link.type === 'page' && page.url === link.href
}

function smoothScroll(id, event) {
    mobileOpen.value = false
    if (!isHome.value) return
    event?.preventDefault()
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
                    ? 'bg-[#0A2422]/96 backdrop-blur-md shadow-2xl border-b border-white/5'
                    : 'bg-transparent'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">

                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-3 flex-shrink-0 cursor-pointer">
                        <img src="/logo-icon.png" alt="Diginova" width="32" height="32" class="h-8 w-auto" />
                        <span class="text-white font-bold text-lg tracking-tight hidden sm:block font-display">
                            Diginova
                        </span>
                    </a>

                    <!-- Desktop nav -->
                    <nav class="hidden md:flex items-center gap-8">
                        <template v-for="link in navLinks" :key="link.href">
                            <Link
                                v-if="link.type === 'page'"
                                :href="link.href"
                                :aria-current="isActive(link) ? 'page' : undefined"
                                :class="[
                                    'text-sm font-medium transition-colors duration-200 rounded focus-ring',
                                    isActive(link) ? 'text-[#30998A]' : 'text-white/70 hover:text-[#30998A]',
                                ]"
                            >
                                {{ link.label }}
                            </Link>
                            <a
                                v-else
                                :href="anchorHref(link.href)"
                                @click="smoothScroll(link.href, $event)"
                                class="text-white/70 hover:text-[#30998A] text-sm font-medium transition-colors duration-200 cursor-pointer rounded focus-ring"
                            >
                                {{ link.label }}
                            </a>
                        </template>
                    </nav>

                    <!-- CTA -->
                    <a
                        :href="anchorHref('contact')"
                        @click="smoothScroll('contact', $event)"
                        class="hidden md:inline-flex items-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-semibold text-sm px-5 py-2.5 rounded-lg transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/25 focus-ring"
                    >
                        Démarrer un projet
                        <ArrowRight class="w-4 h-4" />
                    </a>

                    <!-- Mobile burger -->
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="md:hidden text-white p-2 cursor-pointer rounded-lg hover:bg-white/10 active:scale-95 transition-all duration-200 border-0 bg-transparent focus-ring"
                        aria-label="Menu"
                    >
                        <X v-if="mobileOpen" class="w-6 h-6" />
                        <Menu v-else class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div
                v-show="mobileOpen"
                class="md:hidden bg-[#0A2422]/98 backdrop-blur-md border-t border-white/10 px-4 py-4 space-y-1"
            >
                <template v-for="link in navLinks" :key="link.href">
                    <Link
                        v-if="link.type === 'page'"
                        :href="link.href"
                        :aria-current="isActive(link) ? 'page' : undefined"
                        @click="mobileOpen = false"
                        :class="[
                            'flex w-full items-center py-3 px-3 rounded-lg hover:bg-white/5 transition-colors duration-200 text-sm font-medium focus-ring',
                            isActive(link) ? 'text-[#30998A]' : 'text-white/80 hover:text-[#30998A]',
                        ]"
                    >
                        {{ link.label }}
                    </Link>
                    <a
                        v-else
                        :href="anchorHref(link.href)"
                        @click="smoothScroll(link.href, $event)"
                        class="flex w-full items-center text-white/80 hover:text-[#30998A] py-3 px-3 rounded-lg hover:bg-white/5 transition-colors duration-200 cursor-pointer text-sm font-medium focus-ring"
                    >
                        {{ link.label }}
                    </a>
                </template>
                <a
                    :href="anchorHref('contact')"
                    @click="smoothScroll('contact', $event)"
                    class="flex w-full items-center justify-center mt-2 bg-[#30998A] text-[#0A2422] font-semibold text-sm px-4 py-3 rounded-lg transition-all duration-200 active:scale-95 cursor-pointer focus-ring"
                >
                    Démarrer un projet
                </a>
            </div>
        </header>

        <!-- ── Page content ── -->
        <slot />

        <!-- ── Footer ── -->
        <footer class="bg-[#0A2422] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">

                    <!-- Brand -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <img src="/logo-icon.png" alt="Diginova" width="32" height="32" class="h-8 w-auto" />
                            <span class="font-bold text-xl font-display">Diginova</span>
                        </div>
                        <p class="text-white/55 text-sm leading-relaxed mb-4">
                            Solutions digitales sur mesure pour les entreprises ambitieuses.
                            Web, SaaS, DevOps — de la conception au déploiement.
                        </p>
                        <p class="text-xs text-white/50">
                            Yaoundé, Cameroun
                        </p>
                    </div>

                    <!-- Links -->
                    <div>
                        <h3 class="font-semibold text-xs text-white/50 mb-5 uppercase tracking-widest">Navigation</h3>
                        <ul class="space-y-3">
                            <li v-for="link in navLinks" :key="link.href">
                                <Link
                                    v-if="link.type === 'page'"
                                    :href="link.href"
                                    class="text-white/60 hover:text-[#30998A] text-sm transition-colors duration-200 rounded focus-ring"
                                >
                                    {{ link.label }}
                                </Link>
                                <a
                                    v-else
                                    :href="anchorHref(link.href)"
                                    @click="smoothScroll(link.href, $event)"
                                    class="text-white/60 hover:text-[#30998A] text-sm transition-colors duration-200 cursor-pointer rounded focus-ring"
                                >
                                    {{ link.label }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="font-semibold text-xs text-white/50 mb-5 uppercase tracking-widest">Contact</h3>
                        <ul class="space-y-3 mb-6">
                            <li>
                                <a href="tel:+237655065494" class="flex items-center gap-2.5 text-white/60 hover:text-[#30998A] text-sm transition-colors cursor-pointer rounded focus-ring">
                                    <Phone class="w-4 h-4 text-[#30998A] flex-shrink-0" />
                                    +237 655 065 494
                                </a>
                            </li>
                            <li>
                                <a href="mailto:contact@diginova.cm" class="flex items-center gap-2.5 text-white/60 hover:text-[#30998A] text-sm transition-colors cursor-pointer rounded focus-ring">
                                    <Mail class="w-4 h-4 text-[#30998A] flex-shrink-0" />
                                    contact@diginova.cm
                                </a>
                            </li>
                            <li class="flex items-center gap-2.5 text-white/60 text-sm">
                                <MapPin class="w-4 h-4 text-[#30998A] flex-shrink-0" />
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
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/5 hover:bg-[#30998A]/15 text-white/50 hover:text-[#30998A] transition-all duration-200 cursor-pointer focus-ring"
                            >
                                <!-- WhatsApp brand icon (pas dans Lucide) -->
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>
                            <a
                                href="https://github.com/EUREKA-AGENCY"
                                target="_blank"
                                rel="noopener"
                                aria-label="GitHub"
                                class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/5 hover:bg-[#30998A]/15 text-white/50 hover:text-[#30998A] transition-all duration-200 cursor-pointer focus-ring"
                            >
                                <Github class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-white/50 text-xs">
                        © {{ new Date().getFullYear() }} Diginova. Tous droits réservés.
                    </p>
                    <div class="flex items-center gap-5">
                        <Link href="/mentions-legales" class="text-white/50 hover:text-[#30998A] text-xs transition-colors duration-200 rounded focus-ring">Mentions légales</Link>
                        <Link href="/confidentialite" class="text-white/50 hover:text-[#30998A] text-xs transition-colors duration-200 rounded focus-ring">Confidentialité</Link>
                        <p class="text-white/50 text-xs">Conçu &amp; développé par Diginova, Yaoundé</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
