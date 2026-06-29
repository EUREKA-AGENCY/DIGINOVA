<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import { Server, ShieldCheck, Star, Check, ArrowRight, MessageCircle } from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

function wa(message) {
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
}

function orderHref(plan) {
    const params = new URLSearchParams({
        service: 'hebergement',
        amount: plan.priceValue,
        reference: plan.name,
    })
    return `/paiement?${params.toString()}`
}

const OS_CHOICES = 'CentOS 8, CentOS 7, CentOS 6, Debian 9, Debian 8, Ubuntu 21.04, Ubuntu 20.04, Ubuntu 18.04, Fedora 28, openSUSE Leap 15.0, FreeBSD 11.1, Arch Linux'

const plans = [
    {
        name: 'VPS Basic Unmanaged',
        price: '62 500',
        priceValue: 62500,
        unit: 'F CFA / an',
        specs: ['4 Go RAM', '100 Go espace disque', '2 noyaux CPU', 'SSD-Boosted'],
        featured: false,
    },
    {
        name: 'VPS Premium Unmanaged',
        price: '140 000',
        priceValue: 140000,
        unit: 'F CFA / an',
        specs: ['12 Go RAM', '300 Go espace disque', '6 noyaux CPU', '100% SSD'],
        featured: true,
        badge: 'Plus populaire',
    },
    {
        name: 'VPS Business Unmanaged',
        price: '205 000',
        priceValue: 205000,
        unit: 'F CFA / an',
        specs: ['20 Go RAM', '400 Go espace disque', '8 noyaux CPU', '100% SSD'],
        featured: false,
    },
    {
        name: 'VPS Ultimate Unmanaged',
        price: '376 000',
        priceValue: 376000,
        unit: 'F CFA / an',
        specs: ['32 Go RAM', '500 Go espace disque', '10 noyaux CPU', '100% SSD'],
        featured: false,
    },
]

const commonFeatures = [
    'Bande passante illimitée',
    'Accès root complet',
    '1 adresse IPv4 + 1 IPv6',
    'NodeJS préinstallé',
    'Port réseau 1 Gbps',
    'Compatible Docker',
    'Système au choix (voir liste)',
]
</script>

<template>
    <Head>
        <title>Hébergement VPS | Diginova</title>
        <meta name="description" content="Serveurs VPS non managés Diginova : accès root complet, bande passante illimitée, SSD, Docker. À partir de 62 500 F CFA par an." />
    </Head>

    <!-- ════════════════════ HERO ════════════════════ -->
    <section class="relative overflow-hidden pt-32 pb-16">
        <div class="absolute inset-0">
            <img src="/images/site/hebergement/hero.webp" alt="" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(6,21,20,0.95) 0%, rgba(10,36,34,0.88) 45%, rgba(10,36,34,0.75) 100%);"></div>
        </div>

        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-[#30998A]/25 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm">
                <Server class="w-3.5 h-3.5 text-[#30998A]" />
                <span class="text-brand-400 text-xs font-medium tracking-wider uppercase">Hébergement VPS</span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-[1.1] mb-6 font-display">
                Un serveur, <span class="text-[#30998A]">tous les droits.</span>
            </h1>
            <p class="text-white/65 text-lg max-w-2xl mx-auto mb-4">
                Un environnement non managé avec accès root complet — vous installez et configurez exactement ce dont votre projet a besoin.
            </p>
            <p class="text-white/45 text-sm max-w-2xl mx-auto">
                Compatible Tomcat, Django, Python, Java, Ruby, PHP, et bien d'autres. Système d'exploitation au choix :
                {{ OS_CHOICES }}.
            </p>
        </div>
    </section>

    <!-- ════════════════════ TARIFS ════════════════════ -->
    <section id="tarifs" aria-label="Tarifs hébergement" class="py-24" style="background: #0D2B29;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Tarifs</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Choisissez la puissance qu'il vous faut
                </h2>
                <p class="text-white/55 mt-4 max-w-xl mx-auto">Facturation annuelle. Maîtrisez votre serveur, sans limite de créativité.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="plan in plans"
                    :key="plan.name"
                    :class="[
                        'relative rounded-2xl p-6 flex flex-col border transition-all duration-300',
                        plan.featured
                            ? 'border-[#30998A]/60 bg-gradient-to-b from-[#30998A]/10 to-transparent'
                            : 'border-white/10 hover:border-[#30998A]/30',
                    ]"
                >
                    <span
                        v-if="plan.badge"
                        class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1 bg-[#30998A] text-[#0A2422] text-[11px] font-bold uppercase tracking-wide px-3 py-1 rounded-full whitespace-nowrap"
                    >
                        <Star class="w-3 h-3" fill="currentColor" />
                        {{ plan.badge }}
                    </span>

                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/15 flex items-center justify-center mb-5">
                        <Server class="w-5 h-5 text-[#30998A]" />
                    </div>

                    <h3 class="text-white font-bold text-base mb-4 font-display">{{ plan.name }}</h3>

                    <div class="mb-6">
                        <span class="text-2xl font-extrabold text-white font-display">{{ plan.price }}</span>
                        <span class="text-white/45 text-xs ml-1 block">{{ plan.unit }}</span>
                    </div>

                    <ul class="space-y-2.5 mb-7 flex-1">
                        <li v-for="s in plan.specs" :key="s" class="flex items-start gap-2.5 text-white/70 text-sm">
                            <Check class="w-4 h-4 text-[#30998A] flex-shrink-0 mt-0.5" />
                            {{ s }}
                        </li>
                    </ul>

                    <Link
                        :href="orderHref(plan)"
                        :class="[
                            'inline-flex items-center justify-center gap-2 font-semibold text-sm px-5 py-3 rounded-xl transition-all duration-200 cursor-pointer',
                            plan.featured
                                ? 'bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] focus-ring'
                                : 'border border-white/20 hover:border-[#30998A]/50 active:scale-95 text-white hover:text-[#30998A] focus-ring',
                        ]"
                    >
                        Commander
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>

            <p class="text-center text-white/40 text-xs mt-8">
                * Offre incluant un nom de domaine valable uniquement pour les extensions .com, .net, .org.
            </p>

            <p class="text-center text-white/50 text-sm mt-6">
                Besoin d'un serveur managé ou d'une configuration sur mesure ?
                <a
                    :href="wa('Bonjour, je souhaite un serveur avec une configuration sur mesure (ou managé).')"
                    target="_blank"
                    rel="noopener"
                    class="text-[#30998A] font-semibold hover:underline rounded focus-ring"
                >Parlons-en sur WhatsApp.</a>
            </p>
        </div>
    </section>

    <!-- ════════════════════ INCLUS ════════════════════ -->
    <section aria-label="Inclus dans chaque offre" class="py-24" style="background: #061514;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-black/40 order-2 lg:order-1">
                    <img
                        src="/images/site/hebergement/inclus.webp"
                        alt="Infrastructure serveur Diginova"
                        loading="lazy"
                        class="w-full h-80 lg:h-[420px] object-cover"
                    />
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(6,21,20,0.6) 0%, transparent 50%);"></div>
                </div>

                <div class="order-1 lg:order-2">
                    <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">Sur chaque serveur</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 font-display">
                        Le même socle, quel que soit le plan
                    </h2>
                    <p class="text-white/55 text-sm leading-relaxed mb-8">
                        Aucune option cachée : chaque VPS Diginova part avec la même base solide, peu importe la taille choisie.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div
                            v-for="f in commonFeatures"
                            :key="f"
                            class="p-4 rounded-xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300 flex items-center gap-3"
                        >
                            <ShieldCheck class="w-4 h-4 text-[#30998A] flex-shrink-0" />
                            <span class="text-white/75 text-sm">{{ f }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CTA FINAL ════════════════════ -->
    <section aria-label="Démarrer" class="relative overflow-hidden py-20">
        <div class="absolute inset-0">
            <img src="/images/site/hebergement/cta.webp" alt="" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(29,84,87,0.94) 0%, rgba(13,43,41,0.92) 100%);"></div>
        </div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 font-display">
                Prêt à déployer votre projet ?
            </h2>
            <p class="text-white/70 mb-8">
                Choisissez votre serveur ci-dessus, ou décrivez-nous votre besoin pour un devis sur mesure.
            </p>
            <a
                :href="wa('Bonjour, je souhaite des informations sur vos offres d\'hébergement VPS.')"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 focus-ring"
            >
                <MessageCircle class="w-5 h-5" />
                Discuter de mon projet
            </a>
        </div>
    </section>
</template>
