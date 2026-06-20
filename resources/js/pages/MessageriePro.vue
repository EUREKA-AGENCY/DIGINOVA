<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import {
    ClipboardCheck, Mail, Send, ShieldCheck, Brain, Settings2, Map, Rocket,
    Check, ArrowRight, Star, MessageCircle,
} from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

function wa(message) {
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
}

const plans = [
    {
        name: 'Messagerie Essentiel',
        price: '2 000',
        unit: 'F / boîte / mois',
        desc: "L'essentiel pour quitter Gmail et paraître professionnel.",
        features: ['Adresse @votredomaine.cm', 'Webmail + IMAP/POP', 'Anti-spam de base', 'Support par email'],
        icon: Mail,
        featured: false,
    },
    {
        name: 'Messagerie Pro',
        price: '3 500',
        unit: 'F / boîte / mois',
        desc: 'Le plus choisi par nos clients PME et indépendants.',
        features: ['Tout Essentiel', 'Synchronisation mobile push', 'Calendrier & contacts partagés', 'Anti-spam avancé', 'Support prioritaire'],
        icon: Send,
        featured: true,
        badge: 'Le plus choisi',
    },
    {
        name: 'Messagerie Business',
        price: '6 000',
        unit: 'F / boîte / mois',
        desc: 'Pour les équipes qui ont besoin de sécurité renforcée.',
        features: ['Tout Pro', 'Archivage légal', 'Authentification 2FA', 'SLA garanti 99,9%', 'Onboarding dédié'],
        icon: ShieldCheck,
        featured: false,
    },
]

const addons = [
    {
        name: 'Diagnostic numérique gratuit',
        price: 'Gratuit',
        unit: '15 min',
        desc: 'Un appel pour évaluer votre messagerie actuelle et vos besoins, sans engagement.',
        icon: ClipboardCheck,
        whatsapp: true,
    },
    {
        name: 'Assistant e-mail IA',
        price: '1 500',
        unit: 'F / boîte / mois',
        desc: 'Tri automatique, réponses suggérées et résumés intelligents de vos emails.',
        icon: Brain,
    },
    {
        name: 'Automatisation sur mesure',
        price: 'Sur devis',
        unit: '',
        desc: 'Workflows automatisés entre vos outils : emails, CRM, facturation, notifications.',
        icon: Settings2,
    },
    {
        name: 'Audit & cartographie',
        price: 'À partir de 100 000',
        unit: 'F',
        desc: 'Cartographie complète de votre écosystème digital et plan de mise à niveau.',
        icon: Map,
    },
    {
        name: 'Pack Démarrage',
        price: 'À partir de 50 000',
        unit: 'F',
        desc: 'Migration de vos boîtes mail, configuration du domaine et formation de vos équipes.',
        icon: Rocket,
    },
]
</script>

<template>
    <Head>
        <title>Messagerie Pro & Automatisation IA | Diginova</title>
        <meta name="description" content="Messagerie professionnelle @votredomaine.cm, assistant e-mail IA et automatisation sur mesure. Diagnostic numérique gratuit en 15 minutes." />
    </Head>

    <!-- ════════════════════ HERO ════════════════════ -->
    <section class="relative overflow-hidden pt-32 pb-20">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1596526131083-e8c633c948d2?w=1600&h=900&q=80&auto=format&fit=crop"
                alt=""
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(6,21,20,0.96) 0%, rgba(10,36,34,0.93) 55%, rgba(13,43,41,0.9) 100%);"></div>
        </div>

        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-[#30998A]/25 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-[#30998A] animate-pulse flex-shrink-0"></span>
                <span class="text-[#30998A] text-xs font-medium tracking-wider uppercase">Messagerie Pro & Automatisation IA</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-6 font-display">
                La même entreprise.<br>
                <span class="text-[#30998A]">Pas la même impression.</span>
            </h1>
            <p class="text-white/65 text-lg max-w-2xl mx-auto mb-10">
                Une adresse email professionnelle, un assistant IA qui trie vos messages, et des automatisations qui vous font gagner du temps. À partir de 2 000 F par mois.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    :href="wa('Bonjour, je souhaite un diagnostic numérique gratuit pour mon entreprise.')"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 justify-center focus-ring"
                >
                    <MessageCircle class="w-5 h-5" />
                    Diagnostic gratuit · 15 min
                </a>
                <a
                    href="#tarifs"
                    class="inline-flex items-center gap-2.5 border border-white/30 hover:border-[#30998A]/50 active:scale-95 text-white hover:text-[#30998A] font-semibold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer justify-center backdrop-blur-sm bg-white/5 focus-ring"
                >
                    Voir les tarifs
                    <ArrowRight class="w-4 h-4" />
                </a>
            </div>
        </div>

        <!-- Before / After -->
        <div class="relative max-w-md mx-auto px-4 mt-16">
            <div class="bg-white rounded-2xl shadow-xl p-4 mb-3 flex items-center gap-3">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-red-100 text-red-500 text-xs font-bold flex-shrink-0">✕</span>
                <span class="text-gray-400 text-sm sm:text-base truncate">artisan.jean1990@gmail.com</span>
            </div>
            <div class="flex justify-center mb-3">
                <div class="w-8 h-8 rounded-full bg-[#30998A]/15 flex items-center justify-center">
                    <ArrowRight class="w-4 h-4 text-[#30998A] rotate-90" />
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3 border-l-4 border-[#30998A]">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 text-xs font-bold flex-shrink-0">✓</span>
                <span class="text-[#0A2422] font-semibold text-sm sm:text-base truncate">contact@artisan-jean.cm</span>
            </div>
        </div>
    </section>

    <!-- ════════════════════ PRICING ════════════════════ -->
    <section id="tarifs" aria-label="Tarifs messagerie" class="py-24" style="background: #0D2B29;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">Messagerie professionnelle</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Une boîte mail à votre domaine
                </h2>
                <p class="text-white/55 mt-4 max-w-xl mx-auto">Facturation mensuelle par boîte mail. Sans engagement, sans frais cachés.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="plan in plans"
                    :key="plan.name"
                    :class="[
                        'relative rounded-2xl p-7 flex flex-col border transition-all duration-300',
                        plan.featured
                            ? 'border-[#30998A]/60 bg-gradient-to-b from-[#30998A]/10 to-transparent'
                            : 'border-white/10 hover:border-[#30998A]/30',
                    ]"
                >
                    <span
                        v-if="plan.badge"
                        class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1 bg-[#30998A] text-[#0A2422] text-[11px] font-bold uppercase tracking-wide px-3 py-1 rounded-full"
                    >
                        <Star class="w-3 h-3" fill="currentColor" />
                        {{ plan.badge }}
                    </span>

                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/15 flex items-center justify-center mb-5">
                        <component :is="plan.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>

                    <h3 class="text-white font-bold text-lg mb-1 font-display">{{ plan.name }}</h3>
                    <p class="text-white/55 text-sm mb-5">{{ plan.desc }}</p>

                    <div class="mb-6">
                        <span class="text-3xl font-extrabold text-white font-display">{{ plan.price }}</span>
                        <span class="text-white/45 text-sm ml-1">{{ plan.unit }}</span>
                    </div>

                    <ul class="space-y-2.5 mb-7 flex-1">
                        <li v-for="f in plan.features" :key="f" class="flex items-start gap-2.5 text-white/70 text-sm">
                            <Check class="w-4 h-4 text-[#30998A] flex-shrink-0 mt-0.5" />
                            {{ f }}
                        </li>
                    </ul>

                    <a
                        :href="wa(`Bonjour, je suis intéressé(e) par l'offre ${plan.name}.`)"
                        target="_blank"
                        rel="noopener"
                        :class="[
                            'inline-flex items-center justify-center gap-2 font-semibold text-sm px-5 py-3 rounded-xl transition-all duration-200 cursor-pointer',
                            plan.featured
                                ? 'bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] focus-ring'
                                : 'border border-white/20 hover:border-[#30998A]/50 active:scale-95 text-white hover:text-[#30998A] focus-ring',
                        ]"
                    >
                        Choisir cette offre
                        <ArrowRight class="w-4 h-4" />
                    </a>
                </div>
            </div>

            <!-- Bande visuelle -->
            <div class="mt-16 rounded-2xl overflow-hidden relative">
                <img
                    src="https://images.unsplash.com/photo-1557200134-90327ee9fafa?w=1400&h=300&q=70&auto=format&fit=crop"
                    alt="Boîte de réception professionnelle"
                    loading="lazy"
                    class="w-full h-48 object-cover object-center"
                />
                <div class="absolute inset-0" style="background: rgba(10,36,34,0.74);"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <p class="text-white/80 text-lg font-semibold tracking-wide text-center px-4 font-display">
                        Boîtes professionnelles · Sécurisées · Toujours synchronisées
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ ADD-ONS ════════════════════ -->
    <section aria-label="Services complémentaires" class="py-24" style="background: #061514;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">Aller plus loin</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Diagnostic, IA & automatisation
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="item in addons"
                    :key="item.name"
                    class="group relative p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300 overflow-hidden flex flex-col"
                >
                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/10 flex items-center justify-center mb-4 flex-shrink-0">
                        <component :is="item.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ item.name }}</h3>
                    <p class="text-white/55 text-sm mb-5 flex-1">{{ item.desc }}</p>

                    <div class="flex items-end justify-between">
                        <div>
                            <span class="text-xl font-bold text-white font-display">{{ item.price }}</span>
                            <span v-if="item.unit" class="text-white/45 text-xs ml-1">{{ item.unit }}</span>
                        </div>
                        <a
                            :href="item.whatsapp
                                ? wa('Bonjour, je souhaite un diagnostic numérique gratuit pour mon entreprise.')
                                : wa(`Bonjour, je souhaite en savoir plus sur : ${item.name}.`)"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 text-[#30998A] text-sm font-semibold group-hover:gap-2.5 transition-all duration-200 rounded focus-ring"
                        >
                            En discuter
                            <ArrowRight class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CTA FINAL ════════════════════ -->
    <section aria-label="Démarrer" class="py-20" style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 font-display">
                Prêt à professionnaliser votre messagerie ?
            </h2>
            <p class="text-white/70 mb-8">
                15 minutes suffisent pour évaluer vos besoins et vous proposer la bonne offre.
            </p>
            <a
                :href="wa('Bonjour, je souhaite un diagnostic numérique gratuit pour mon entreprise.')"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 focus-ring"
            >
                <MessageCircle class="w-5 h-5" />
                Démarrer le diagnostic gratuit
            </a>
        </div>
    </section>
</template>
