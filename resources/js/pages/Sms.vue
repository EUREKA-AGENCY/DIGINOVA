<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import {
    Eye, Smartphone, Radio, Zap, ShieldCheck, BellRing, Megaphone,
    Tag, Users, Ban, CheckCheck, ListChecks, BarChart3,
    Package, Send, Crown, Check, ArrowRight, Star, MessageCircle, Terminal,
} from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

function wa(message) {
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
}

const reasons = [
    {
        title: '+95 % de taux de lecture',
        desc: "Le SMS est ouvert dans les minutes qui suivent sa réception. Aucun autre canal n'égale cet impact.",
        icon: Eye,
    },
    {
        title: '100 % des téléphones',
        desc: "Pas besoin d'internet, d'application ni de smartphone. Tout mobile reçoit vos messages, partout au Cameroun.",
        icon: Smartphone,
    },
    {
        title: 'Livraison directe opérateurs',
        desc: 'Connexion directe aux réseaux : accusés de réception, gestion des STOP et meilleurs délais de livraison.',
        icon: Radio,
    },
    {
        title: 'Prêt en quelques minutes',
        desc: 'Importez vos contacts, rédigez, envoyez. Une campagne vers des milliers de destinataires part en quelques clics.',
        icon: Zap,
    },
]

const useCases = [
    {
        title: 'SMS Transactionnels & OTP',
        desc: "Codes de vérification, confirmations de commande, accusés de paiement. Sécurisez les comptes de vos clients avec l'authentification à deux facteurs (2FA) et des messages déclenchés automatiquement.",
        icon: ShieldCheck,
    },
    {
        title: 'Alertes & Notifications',
        desc: 'Rappels de rendez-vous, échéances, mises à jour de livraison, alertes critiques. Vos informations urgentes arrivent et sont lues immédiatement.',
        icon: BellRing,
    },
    {
        title: 'Campagnes Marketing',
        desc: 'Promotions, soldes, lancements, fidélisation. Touchez toute votre base client en un envoi, avec personnalisation du nom et suivi des statistiques en temps réel.',
        icon: Megaphone,
    },
]

const plans = [
    {
        name: 'Pack Start',
        price: '17',
        unit: 'F / SMS',
        range: 'De 1 000 à 9 999 SMS',
        desc: 'Pour démarrer',
        features: ['Plateforme web complète', "Personnalisation de l'expéditeur", 'Accusés de réception', 'Support par email'],
        icon: Package,
        featured: false,
    },
    {
        name: 'Pack Pro',
        price: '15',
        unit: 'F / SMS',
        range: 'De 10 000 à 49 999 SMS',
        desc: 'Le plus choisi par les PME',
        features: ['Tout le Pack Start', 'Publipostage (personnalisation avancée)', 'Gestion automatique des STOP', 'Statistiques détaillées', 'Support prioritaire'],
        icon: Send,
        featured: true,
        badge: 'Le plus choisi',
    },
    {
        name: 'Pack Elite',
        price: '14',
        unit: 'F / SMS',
        range: '50 000 SMS et plus',
        desc: 'Pour les gros volumes',
        features: ['Tout le Pack Pro', 'Accès API complet', 'Réponses SMS (two-way)', 'Gestionnaire de compte dédié', 'SLA garanti'],
        icon: Crown,
        featured: false,
    },
]

const features = [
    {
        title: "Personnalisation de l'expéditeur",
        desc: "Affichez le nom de votre marque (jusqu'à 11 caractères) à la place d'un numéro.",
        icon: Tag,
    },
    {
        title: 'Publipostage',
        desc: 'Insérez le nom, le prénom ou d\'autres champs de vos clients dans chaque message.',
        icon: Users,
    },
    {
        title: 'Gestion des STOP',
        desc: 'Désinscription automatique et conforme : les numéros sortants sont ajoutés à votre liste noire.',
        icon: Ban,
    },
    {
        title: 'Accusés de réception',
        desc: 'Sachez précisément quels messages ont été livrés, et quels numéros ne sont plus actifs.',
        icon: CheckCheck,
    },
    {
        title: 'Nettoyage de listes',
        desc: 'Doublons et numéros invalides supprimés automatiquement : vous ne payez que l\'utile.',
        icon: ListChecks,
    },
    {
        title: 'Statistiques en temps réel',
        desc: 'Suivez vos envois, taux de livraison et performances de campagne en direct.',
        icon: BarChart3,
    },
]

const codeSnippets = {
    cURL: `curl -X POST https://api.diginova.cm/v1/sms/send \\
  -H "Authorization: Bearer VOTRE_CLE_API" \\
  -H "Content-Type: application/json" \\
  -d '{
    "from": "DIGINOVA",
    "to": "+237655065494",
    "text": "Votre code de vérification est 4821."
  }'`,
    PHP: `<?php
$response = file_get_contents('https://api.diginova.cm/v1/sms/send', false, stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Authorization: Bearer VOTRE_CLE_API\\r\\nContent-Type: application/json",
        'content' => json_encode([
            'from' => 'DIGINOVA',
            'to'   => '+237655065494',
            'text' => 'Votre code de vérification est 4821.',
        ]),
    ],
]));`,
    'Node.js': `await fetch('https://api.diginova.cm/v1/sms/send', {
  method: 'POST',
  headers: {
    Authorization: 'Bearer VOTRE_CLE_API',
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    from: 'DIGINOVA',
    to: '+237655065494',
    text: 'Votre code de vérification est 4821.',
  }),
})`,
    Python: `import requests

requests.post('https://api.diginova.cm/v1/sms/send',
    headers={'Authorization': 'Bearer VOTRE_CLE_API'},
    json={
        'from': 'DIGINOVA',
        'to': '+237655065494',
        'text': 'Votre code de vérification est 4821.',
    })`,
}
const codeLangs = Object.keys(codeSnippets)
const activeLang = ref('cURL')
</script>

<template>
    <Head>
        <title>SMS Pro & Campagnes par SMS | Diginova</title>
        <meta name="description" content="Envoyez des SMS professionnels au Cameroun : notifications, alertes, OTP/2FA et campagnes marketing. Plateforme web et API, en connexion directe opérateurs. À partir de packs accessibles en FCFA." />
    </Head>

    <!-- ════════════════════ HERO ════════════════════ -->
    <section class="relative overflow-hidden pt-32 pb-20">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=1600&h=900&q=80&auto=format&fit=crop"
                alt=""
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(6,21,20,0.94) 0%, rgba(10,36,34,0.92) 55%, rgba(13,43,41,0.9) 100%);"></div>
        </div>

        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-white/5 border border-[#30998A]/25 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-[#30998A] animate-pulse flex-shrink-0"></span>
                <span class="text-brand-400 text-xs font-medium tracking-wider uppercase">SMS Pro & Campagnes</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-6 font-display">
                Un SMS lu en 3 minutes.<br>
                <span class="text-[#30998A]">Pas un email ignoré.</span>
            </h1>
            <p class="text-white/65 text-lg max-w-2xl mx-auto mb-10">
                Atteignez vos clients là où ils regardent vraiment : leur téléphone. Notifications, alertes, codes de vérification et campagnes marketing — depuis une plateforme simple ou via notre API. Plus de 95 % de vos messages lus.
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

        <!-- Visuel : bulle SMS sur téléphone -->
        <div class="relative max-w-xs mx-auto mt-16">
            <div class="bg-white rounded-2xl shadow-xl p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-full bg-[#30998A] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">D</div>
                    <span class="text-[#0A2422] font-semibold text-sm">DIGINOVA</span>
                </div>
                <div class="bg-gray-100 rounded-xl rounded-tl-sm p-3 text-sm text-gray-700">
                    Votre code de vérification est 4821. Valide 5 minutes.
                </div>
                <div class="flex items-center justify-end gap-1 mt-2">
                    <span class="text-[11px] text-gray-400">09:41</span>
                    <CheckCheck class="w-3.5 h-3.5 text-[#30998A]" />
                    <span class="text-[11px] text-[#30998A] font-medium">Lu</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ POURQUOI LE SMS ════════════════════ -->
    <section aria-label="Pourquoi le SMS" class="py-24" style="background: #0D2B29;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Le canal le plus direct</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Le SMS, c'est l'attention garantie
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="r in reasons"
                    :key="r.title"
                    class="p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300"
                    style="background: rgba(255,255,255,0.03);"
                >
                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/10 flex items-center justify-center mb-4">
                        <component :is="r.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>
                    <h3 class="text-white font-semibold text-[16px] mb-2 leading-snug font-display">{{ r.title }}</h3>
                    <p class="text-white/50 text-sm leading-relaxed">{{ r.desc }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CAS D'USAGE ════════════════════ -->
    <section aria-label="Cas d'usage" class="py-24" style="background: #061514;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">À chaque besoin, son SMS</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Ce que vous pouvez envoyer
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="u in useCases"
                    :key="u.title"
                    class="p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300"
                >
                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/15 flex items-center justify-center mb-4">
                        <component :is="u.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2 font-display">{{ u.title }}</h3>
                    <p class="text-white/55 text-sm leading-relaxed">{{ u.desc }}</p>
                </div>
            </div>

            <!-- Bande visuelle -->
            <div class="mt-16 rounded-2xl overflow-hidden relative">
                <img
                    src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=1400&h=300&q=70&auto=format&fit=crop"
                    alt="Application Messages sur smartphone"
                    loading="lazy"
                    class="w-full h-48 object-cover object-center"
                />
                <div class="absolute inset-0" style="background: rgba(10,36,34,0.74);"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <p class="text-white/80 text-lg font-semibold tracking-wide text-center px-4 font-display">
                        Notifications instantanées · Sans application · Sans connexion data
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ TARIFICATION ════════════════════ -->
    <section id="tarifs" aria-label="Tarifs SMS" class="py-24" style="background: #0D2B29;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Tarifs SMS</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Des packs clairs, sans surprise
                </h2>
                <p class="text-white/55 mt-4 max-w-xl mx-auto">Plus vous envoyez, moins le SMS coûte. Crédits sans date limite d'utilisation. Sans engagement.</p>
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
                    <p class="text-white/45 text-xs mb-1">{{ plan.range }}</p>
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

            <p class="text-center text-white/50 text-sm mt-10">
                Besoin de très gros volumes ?
                <a
                    :href="wa('Bonjour, nous avons un besoin SMS en très gros volume. Pouvez-vous nous proposer un tarif sur mesure ?')"
                    target="_blank"
                    rel="noopener"
                    class="text-[#30998A] font-semibold hover:underline rounded focus-ring"
                >Tarif sur mesure — parlons-en.</a>
            </p>
        </div>
    </section>

    <!-- ════════════════════ FONCTIONNALITÉS ════════════════════ -->
    <section aria-label="Fonctionnalités incluses" class="py-24" style="background: #061514;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">Tout est inclus</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Les outils indispensables, sans option cachée
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="f in features"
                    :key="f.title"
                    class="p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300"
                >
                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/10 flex items-center justify-center mb-4">
                        <component :is="f.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ f.title }}</h3>
                    <p class="text-white/55 text-sm leading-relaxed">{{ f.desc }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ API ════════════════════ -->
    <section aria-label="API SMS pour développeurs" class="py-24" style="background: #0D2B29;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Pour les développeurs</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-5 font-display">
                        Intégrez le SMS en quelques lignes
                    </h2>
                    <p class="text-white/60 leading-relaxed mb-8">
                        Ajoutez l'envoi de SMS à votre application, votre site ou votre plateforme SaaS grâce à notre API REST. Documentation claire, exemples de code et support technique inclus — par l'équipe qui développe au quotidien.
                    </p>
                    <a
                        :href="wa('Bonjour, je souhaite la documentation de votre API SMS.')"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-2 text-[#30998A] font-semibold text-sm hover:gap-3 transition-all duration-200 rounded focus-ring"
                    >
                        Consulter la documentation
                        <ArrowRight class="w-4 h-4" />
                    </a>
                </div>

                <div class="rounded-2xl border border-white/10 overflow-hidden" style="background: #061514;">
                    <div class="flex items-center gap-1 px-4 pt-3">
                        <Terminal class="w-3.5 h-3.5 text-white/45 mr-2" />
                        <button
                            v-for="lang in codeLangs"
                            :key="lang"
                            type="button"
                            @click="activeLang = lang"
                            :class="[
                                'text-xs font-medium px-3 py-1.5 rounded-t-lg transition-colors duration-150 cursor-pointer focus-ring',
                                activeLang === lang ? 'bg-[#30998A]/15 text-[#30998A]' : 'text-white/50 hover:text-white/80',
                            ]"
                        >
                            {{ lang }}
                        </button>
                    </div>
                    <pre class="px-5 py-5 text-[12.5px] leading-relaxed text-white/75 overflow-x-auto font-mono">{{ codeSnippets[activeLang] }}</pre>
                    <div class="border-t border-white/10 px-5 py-4">
                        <p class="text-white/50 text-[11px] uppercase tracking-widest mb-2">Réponse</p>
                        <pre class="text-[12.5px] leading-relaxed text-[#30998A] font-mono">{ "status": "envoyé", "id": "msg_8f2a", "credits_restants": 9876 }</pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CTA FINAL ════════════════════ -->
    <section aria-label="Démarrer" class="relative overflow-hidden py-20">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1600&h=700&q=80&auto=format&fit=crop"
                alt=""
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(29,84,87,0.94) 0%, rgba(13,43,41,0.92) 100%);"></div>
        </div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 font-display">
                Prêt à passer au SMS ?
            </h2>
            <p class="text-white/70 mb-8">
                15 minutes suffisent pour évaluer vos besoins et choisir le bon pack. Diagnostic gratuit, sans engagement.
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
