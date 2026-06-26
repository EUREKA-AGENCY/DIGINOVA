<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Mail, Settings, LogOut, Menu, X, ChevronDown, ArrowLeft, LayoutDashboard, Send, History, Users, Megaphone, Code2, ShieldCheck } from 'lucide-vue-next'

defineProps({
    title: { type: String, default: '' },
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
const initials = computed(() => {
    const name = user.value?.name || ''
    return name
        .split(' ')
        .map((p) => p[0])
        .filter(Boolean)
        .slice(0, 2)
        .join('')
        .toUpperCase()
})

const mobileSidebarOpen = ref(false)
const userMenuOpen = ref(false)

const navGroups = computed(() => {
    const groups = [
        {
            label: 'Messagerie',
            items: [
                { label: 'Messagerie', href: '/mail', icon: Mail },
            ],
        },
        {
            label: 'SMS Pro',
            items: [
                { label: 'Tableau de bord', href: '/sms-pro', icon: LayoutDashboard },
                { label: 'Envoyer des SMS', href: '/sms-pro/send', icon: Send },
                { label: 'Historique', href: '/sms-pro/history', icon: History },
                { label: 'Contacts', href: '/sms-pro/contacts', icon: Users },
                { label: 'Campagnes', href: '/sms-pro/campaigns', icon: Megaphone },
                { label: 'Développeurs', href: '/sms-pro/developers', icon: Code2 },
            ],
        },
    ]

    if (user.value?.is_admin) {
        groups.push({
            label: 'Administration',
            items: [
                { label: 'Console admin', href: '/admin', icon: ShieldCheck },
            ],
        })
    }

    return groups
})

function isActive(href) {
    return page.url === href
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">

        <!-- ── Sidebar (desktop) ── -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-[#0A2422]">
            <div class="h-16 flex items-center gap-3 px-6 border-b border-white/10 flex-shrink-0">
                <img src="/logo-icon.png" alt="Diginova" width="28" height="28" class="h-7 w-auto" />
                <span class="text-white font-bold text-base tracking-tight font-display">Diginova</span>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-5 overflow-y-auto">
                <div v-for="group in navGroups" :key="group.label">
                    <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-white/30">{{ group.label }}</p>
                    <div class="space-y-1">
                        <Link
                            v-for="item in group.items"
                            :key="item.href"
                            :href="item.href"
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 focus-ring',
                                isActive(item.href) ? 'bg-[#30998A]/15 text-[#30998A]' : 'text-white/60 hover:text-white hover:bg-white/5',
                            ]"
                        >
                            <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                            {{ item.label }}
                        </Link>
                    </div>
                </div>
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <Link
                    href="/"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/40 hover:text-white hover:bg-white/5 transition-colors duration-200 focus-ring"
                >
                    <ArrowLeft class="w-5 h-5 flex-shrink-0" />
                    Retour au site
                </Link>
            </div>
        </aside>

        <!-- ── Sidebar (mobile, overlay) ── -->
        <div v-show="mobileSidebarOpen" class="lg:hidden fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/40" @click="mobileSidebarOpen = false"></div>
            <aside class="absolute inset-y-0 left-0 w-64 bg-[#0A2422] flex flex-col">
                <div class="h-16 flex items-center justify-between gap-3 px-6 border-b border-white/10 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="/logo-icon.png" alt="Diginova" width="28" height="28" class="h-7 w-auto" />
                        <span class="text-white font-bold text-base tracking-tight font-display">Diginova</span>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="text-white/60 hover:text-white p-1 cursor-pointer bg-transparent border-0" aria-label="Fermer">
                        <X class="w-5 h-5" />
                    </button>
                </div>
                <nav class="flex-1 px-3 py-6 space-y-5 overflow-y-auto">
                    <div v-for="group in navGroups" :key="group.label">
                        <p class="px-3 mb-1.5 text-[10px] font-semibold uppercase tracking-widest text-white/30">{{ group.label }}</p>
                        <div class="space-y-1">
                            <Link
                                v-for="item in group.items"
                                :key="item.href"
                                :href="item.href"
                                @click="mobileSidebarOpen = false"
                                :class="[
                                    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 focus-ring',
                                    isActive(item.href) ? 'bg-[#30998A]/15 text-[#30998A]' : 'text-white/60 hover:text-white hover:bg-white/5',
                                ]"
                            >
                                <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
                                {{ item.label }}
                            </Link>
                        </div>
                    </div>
                </nav>
                <div class="px-3 py-4 border-t border-white/10">
                    <Link href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/40 hover:text-white hover:bg-white/5 transition-colors duration-200 focus-ring">
                        <ArrowLeft class="w-5 h-5 flex-shrink-0" />
                        Retour au site
                    </Link>
                </div>
            </aside>
        </div>

        <!-- ── Right column: header + content + footer ── -->
        <div class="flex-1 flex flex-col min-h-screen lg:pl-64">

            <!-- Header -->
            <header class="h-16 flex-shrink-0 bg-white border-b border-slate-100 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button
                        @click="mobileSidebarOpen = true"
                        class="lg:hidden text-slate-500 hover:text-slate-900 p-1.5 -ml-1.5 cursor-pointer bg-transparent border-0 rounded-lg hover:bg-slate-100 focus-ring"
                        aria-label="Menu"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                    <h1 v-if="title" class="text-sm font-semibold text-slate-900">{{ title }}</h1>
                </div>

                <div class="relative">
                    <button
                        @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center gap-2.5 cursor-pointer bg-transparent border-0 rounded-lg px-2 py-1.5 hover:bg-slate-50 focus-ring"
                    >
                        <span class="w-8 h-8 rounded-full bg-[#30998A]/10 text-[#1D5457] text-xs font-semibold flex items-center justify-center flex-shrink-0">
                            {{ initials }}
                        </span>
                        <span class="hidden sm:block text-sm font-medium text-slate-700">{{ user?.name }}</span>
                        <ChevronDown class="hidden sm:block w-4 h-4 text-slate-400" />
                    </button>

                    <div v-if="userMenuOpen" @click="userMenuOpen = false" class="fixed inset-0 z-10"></div>

                    <div
                        v-show="userMenuOpen"
                        class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-20"
                    >
                        <p class="px-4 py-2 text-xs text-slate-400 truncate border-b border-slate-100 mb-1">{{ user?.email }}</p>
                        <Link
                            href="/settings/profile"
                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors duration-150"
                        >
                            <Settings class="w-4 h-4" />
                            Paramètres du compte
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 cursor-pointer bg-transparent border-0 text-left"
                        >
                            <LogOut class="w-4 h-4" />
                            Se déconnecter
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="flex-shrink-0 border-t border-slate-100 px-4 sm:px-8 py-4">
                <p class="text-xs text-slate-400 text-center">
                    © {{ new Date().getFullYear() }} Diginova · Espace client
                </p>
            </footer>
        </div>
    </div>
</template>
