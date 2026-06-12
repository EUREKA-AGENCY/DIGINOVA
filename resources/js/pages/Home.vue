<script setup>
import { ref, reactive, onMounted } from 'vue'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import {
    ArrowRight, ChevronDown, Check, Send, Loader2,
    Phone, Mail, MapPin,
    Code2, Cloud, Network, Terminal, Zap, MessageSquare,
} from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

function scrollTo(id) {
    const el = document.getElementById(id)
    if (!el) return
    const top = el.getBoundingClientRect().top + window.scrollY - 72
    window.scrollTo({ top, behavior: 'smooth' })
}

const statsVisible = ref(false)
const statsSection = ref(null)

onMounted(() => {
    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                statsVisible.value = true
                observer.disconnect()
            }
        },
        { threshold: 0.3 }
    )
    if (statsSection.value) observer.observe(statsSection.value)
})

const form = ref({ name: '', email: '', phone: '', project_type: '', budget: '', message: '' })
const sending = ref(false)
const sent = ref(false)
const formError = ref('')

async function handleSubmit() {
    formError.value = ''
    if (!form.value.name || !form.value.email || !form.value.message) return
    sending.value = true
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
        const res = await fetch('/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(form.value),
        })
        if (res.ok) {
            sent.value = true
        } else {
            formError.value = 'Une erreur est survenue. Contactez-nous directement sur WhatsApp.'
        }
    } catch {
        formError.value = 'Connexion impossible. Contactez-nous sur WhatsApp.'
    } finally {
        sending.value = false
    }
}

const services = [
    {
        title: 'Développement Web Custom',
        desc: 'Applications web performantes et scalables, conçues sur mesure pour votre métier.',
        tags: ['Laravel', 'Vue.js', 'React', 'Next.js'],
        icon: Code2,
    },
    {
        title: 'Applications SaaS',
        desc: 'Conception et déploiement de plateformes SaaS multi-tenant, de la maquette au Go-Live.',
        tags: ['SaaS', 'Multi-tenant', 'API REST'],
        icon: Cloud,
    },
    {
        title: 'Microservices & API',
        desc: 'Architecture microservices, API Gateway, intégration de services tiers et ETL.',
        tags: ['Node.js', 'Docker', 'REST', 'GraphQL'],
        icon: Network,
    },
    {
        title: 'DevOps & CI/CD',
        desc: 'Pipelines CI/CD, containerisation Docker, déploiement continu sur VPS ou cloud.',
        tags: ['Docker', 'GitHub Actions', 'Nginx', 'Linux'],
        icon: Terminal,
    },
    {
        title: 'Transformation Digitale',
        desc: 'Audit, conseil stratégique et accompagnement pour digitaliser vos processus métier.',
        tags: ['Audit', 'Conseil', 'Formation', 'Support'],
        icon: Zap,
    },
]

const portfolio = [
    {
        name: 'BGFI LeClient',
        type: 'Banque · Fintech',
        desc: 'Plateforme digitale BGFIBank Cameroun : ouverture de compte en ligne, gestion bancaire et services financiers pour particuliers et entreprises.',
        tags: ['Laravel', 'Vue.js', 'API Banking'],
        accent: '#1D4ED8',
        img: 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://leclientcm.bgfi.com/',
    },
    {
        name: 'MinDef',
        type: 'Défense Nationale · Institutionnel',
        desc: 'Site institutionnel et système de messagerie sécurisé du Ministère de la Défense du Cameroun.',
        tags: ['Laravel', 'Bootstrap', 'MySQL'],
        accent: '#15803D',
        img: 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://mindef.gov.cm/',
    },
    {
        name: 'USRA-CARE',
        type: 'Santé · Banque Mondiale',
        desc: 'Plateforme de suivi santé développée dans le cadre du projet SISEPCAM, financé par la Banque Mondiale.',
        tags: ['React', 'Node.js', 'PostgreSQL'],
        accent: '#DC2626',
        img: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://usra-care.com/',
    },
    {
        name: 'ADS360',
        type: 'DOOH · Publicité Digitale',
        desc: 'Solutions d\'écrans digitaux publicitaires (DOOH) : gestion de contenus, diffusion en temps réel et analytics pour réseaux d\'affichage urbain.',
        tags: ['Vue.js', 'Laravel', 'API REST'],
        accent: '#EA580C',
        img: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://ads360.digital/',
    },
    {
        name: 'CaregFA',
        type: 'Expérience Client · SaaS',
        desc: 'Plateforme SaaS de gestion de file d\'attente et d\'optimisation de l\'accueil client pour entreprises et institutions.',
        tags: ['Laravel', 'Vue.js', 'MySQL'],
        accent: '#7C3AED',
        img: 'https://images.unsplash.com/photo-1556742205-e10c9486e506?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://caregfa.com/',
    },
    {
        name: 'FreeSurf',
        type: 'SaaS · Hotspot WiFi',
        desc: 'Plateforme de gestion de hotspots WiFi avec portail captif, facturation automatique et dashboards temps réel.',
        tags: ['Laravel', 'Vue.js', 'MySQL'],
        accent: '#0891B2',
        img: 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=500&h=220&q=75&auto=format&fit=crop',
        url: null,
    },
    {
        name: 'DECH School',
        type: 'EdTech · Gestion Scolaire',
        desc: 'Application complète : inscriptions, notes, emplois du temps, paiements en ligne et portail parents/élèves.',
        tags: ['Laravel', 'Vue.js', 'MySQL'],
        accent: '#D97706',
        img: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=500&h=220&q=75&auto=format&fit=crop',
        url: null,
    },
    {
        name: 'MBAC',
        type: 'Administration Publique',
        desc: 'Système de gestion administrative pour le Ministère des Biens et Affaires Culturelles du Cameroun.',
        tags: ['Laravel', 'Bootstrap', 'Oracle'],
        accent: '#0D6B52',
        img: 'https://images.unsplash.com/photo-1568992687947-868a62a9f521?w=500&h=220&q=75&auto=format&fit=crop',
        url: null,
    },
    {
        name: 'Census',
        type: 'Administration · Recensement',
        desc: 'Application nationale de recensement avec interface mobile offline-first et synchronisation différée.',
        tags: ['Laravel', 'Flutter', 'MySQL'],
        accent: '#6B7280',
        img: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=220&q=75&auto=format&fit=crop',
        url: 'https://census.diginova.cm/',
    },
]

const process = [
    {
        n: '01',
        title: 'Découverte & Audit',
        desc: "Analyse de vos besoins, audit de l'existant, définition des objectifs. Un interlocuteur dédié de bout en bout.",
    },
    {
        n: '02',
        title: 'Conception & Design',
        desc: 'Maquettes interactives, architecture technique et sélection des technologies adaptées à votre contexte.',
    },
    {
        n: '03',
        title: 'Développement & Tests',
        desc: 'Développement itératif avec livraisons régulières, tests fonctionnels et revue qualité à chaque étape.',
    },
    {
        n: '04',
        title: 'Déploiement & Support',
        desc: 'Mise en production, formation des équipes, documentation et support technique réactif post-lancement.',
    },
]

const stats = [
    { value: '8+', label: "Années d'expérience" },
    { value: '50+', label: 'Clients satisfaits' },
    { value: '19', label: 'Projets livrés' },
    { value: '100%', label: 'En production' },
]

const logoFailed = reactive({})

const clients = [
    { name: 'BGFIBank',    logo: 'https://leclientcm.bgfi.com/_nuxt/img/bgfi.f7108a4.png' },
    { name: 'MinDef',      logo: 'https://mindef.gov.cm/wp-content/uploads/2023/10/LOGO-MINDEF-PNG-FRANCAIS.png' },
    { name: 'USRA-CARE',   logo: 'https://usra-care.com/images/logo.jpg' },
    { name: 'ADS360',      logo: 'https://ads360.digital/images/logo.png' },
    { name: 'CaregFA',     logo: 'https://www.google.com/s2/favicons?domain=caregfa.com&sz=128' },
    { name: 'Census',      logo: 'https://www.google.com/s2/favicons?domain=census.diginova.cm&sz=128' },
    { name: 'DECH School', logo: null },
    { name: 'FreeSurf',    logo: null },
    { name: 'MBAC',        logo: null },
]

const budgets = [
    'Moins de 500 000 FCFA',
    '500 000 – 1 500 000 FCFA',
    '1 500 000 – 5 000 000 FCFA',
    'Plus de 5 000 000 FCFA',
    'À définir ensemble',
]

const projectTypes = [
    'Site vitrine / Landing page',
    'Application web sur mesure',
    'Plateforme SaaS',
    'Refonte / Migration',
    'API / Microservices',
    'DevOps & Infrastructure',
    'Conseil / Audit Digital',
]
</script>

<template>
    <!-- ════════════════════ HERO ════════════════════ -->
    <section
        class="relative min-h-screen flex flex-col justify-center overflow-hidden"
        style="background: linear-gradient(150deg, #050D2A 0%, #0B1437 50%, #0d2060 100%);"
    >
        <!-- Dot grid overlay -->
        <div
            class="absolute inset-0 opacity-[0.035]"
            style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 32px 32px;"
        ></div>

        <!-- Cyan glow top-center -->
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 w-[900px] h-[600px] rounded-full opacity-[0.08] pointer-events-none"
            style="background: radial-gradient(ellipse at center, #00D8E8 0%, transparent 65%);"
        ></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-20 w-full">

            <!-- 2-column on desktop -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Left: text -->
                <div class="text-center lg:text-left">

                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 bg-white/5 border border-[#00D8E8]/20 rounded-full px-4 py-1.5 mb-8">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#00D8E8] animate-pulse flex-shrink-0"></span>
                        <span class="text-[#00D8E8] text-xs font-medium tracking-wider uppercase">Diginova · Yaoundé, Cameroun</span>
                    </div>

                    <!-- Headline -->
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-[1.08] mb-6"
                        style="font-family:'Poppins',sans-serif;"
                    >
                        Transformez votre vision
                        <span
                            class="block mt-1"
                            style="background: linear-gradient(90deg, #00D8E8 0%, #4EB8FF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
                        >
                            en réalité digitale
                        </span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-white/60 text-lg sm:text-xl leading-relaxed mb-10">
                        Développement web sur mesure, applications SaaS, microservices et DevOps.
                        Des solutions robustes livrées par une équipe avec
                        <strong class="text-white/85 font-semibold">8 ans d'expérience</strong> et
                        <strong class="text-white/85 font-semibold">19 projets en production</strong>.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center lg:items-start justify-center lg:justify-start gap-4 mb-12">
                        <button
                            @click="scrollTo('contact')"
                            class="inline-flex items-center gap-2.5 bg-[#00D8E8] hover:bg-[#00C2D0] active:scale-95 text-[#070E24] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#00D8E8]/30 text-base w-full sm:w-auto justify-center border-0"
                        >
                            Démarrer un projet
                            <ArrowRight class="w-5 h-5" />
                        </button>
                        <button
                            @click="scrollTo('realisations')"
                            class="inline-flex items-center gap-2.5 bg-white/8 hover:bg-white/12 border border-white/15 hover:border-[#00D8E8]/40 text-white font-semibold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer text-base w-full sm:w-auto justify-center backdrop-blur-sm"
                        >
                            Voir nos réalisations
                            <ChevronDown class="w-5 h-5 text-[#00D8E8]" />
                        </button>
                    </div>

                    <!-- Stats bar -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 bg-white/5 border border-white/10 rounded-2xl overflow-hidden backdrop-blur-sm">
                        <div
                            v-for="(s, i) in stats"
                            :key="s.label"
                            :class="['flex flex-col items-center py-5 px-4', i < stats.length - 1 ? 'border-r border-white/10' : '']"
                        >
                            <span class="text-2xl sm:text-3xl font-bold text-[#00D8E8]" style="font-family:'Poppins',sans-serif">
                                {{ s.value }}
                            </span>
                            <span class="text-white/50 text-xs mt-0.5 text-center">{{ s.label }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: hero image (desktop only) -->
                <div class="hidden lg:flex items-center justify-center relative">
                    <!-- Glow behind image -->
                    <div
                        class="absolute inset-0 rounded-3xl blur-3xl opacity-20 pointer-events-none"
                        style="background: radial-gradient(ellipse at center, #00D8E8 0%, #1B2D8C 60%, transparent 100%);"
                    ></div>

                    <!-- Browser mockup frame -->
                    <div class="relative w-full max-w-lg rounded-2xl overflow-hidden border border-white/15 shadow-2xl shadow-black/60">
                        <!-- Faux browser bar -->
                        <div class="bg-[#0d1a3a] px-4 py-3 flex items-center gap-2 border-b border-white/10">
                            <span class="w-3 h-3 rounded-full bg-red-400/70"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400/70"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400/70"></span>
                            <div class="flex-1 mx-3 bg-white/5 rounded-md px-3 py-1 text-white/30 text-xs">
                                diginova.cm/dashboard
                            </div>
                        </div>
                        <img
                            src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=700&h=450&q=80&auto=format&fit=crop"
                            alt="Dashboard Diginova"
                            class="w-full block"
                        />
                    </div>
                </div>
            </div>

            <!-- Trust / clients (full width below) -->
            <div class="mt-16">
                <p class="text-white/30 text-xs uppercase tracking-widest mb-8 text-center">Ils nous font confiance</p>

                <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-3">
                    <div
                        v-for="c in clients"
                        :key="c.name"
                        class="group flex flex-col items-center gap-2 p-3 rounded-xl border border-white/8 bg-white/[0.03] hover:bg-white/8 hover:border-white/20 transition-all duration-300 cursor-default"
                    >
                        <!-- Logo -->
                        <div class="w-10 h-10 flex items-center justify-center">
                            <img
                                v-if="c.logo && !logoFailed[c.name]"
                                :src="c.logo"
                                :alt="c.name"
                                class="w-9 h-9 object-contain brightness-0 invert opacity-50 group-hover:opacity-90 transition-opacity duration-300 rounded-sm"
                                @error="logoFailed[c.name] = true"
                            />
                            <div v-else class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                                <span class="text-white/50 text-[11px] font-bold group-hover:text-white/80 transition-colors">
                                    {{ c.name.slice(0, 2).toUpperCase() }}
                                </span>
                            </div>
                        </div>
                        <!-- Nom -->
                        <span class="text-white/35 group-hover:text-white/65 text-[10px] font-medium text-center leading-tight transition-colors duration-300 line-clamp-2">
                            {{ c.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1.5 opacity-35">
            <span class="text-white text-[11px] uppercase tracking-widest">Découvrir</span>
            <ChevronDown class="w-4 h-4 text-white animate-bounce" />
        </div>
    </section>

    <!-- ════════════════════ SERVICES ════════════════════ -->
    <section id="services" class="py-24" style="background: #0B1437;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block text-[#00D8E8] text-xs font-semibold uppercase tracking-widest mb-3">Nos expertises</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white" style="font-family:'Poppins',sans-serif;">
                    Ce que nous construisons
                </h2>
                <p class="mt-4 text-white/50 max-w-xl mx-auto text-sm leading-relaxed">
                    De la conception à la production, nous couvrons toute la chaîne de valeur digitale.
                </p>
            </div>

            <!-- Services with background image strip -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="svc in services"
                    :key="svc.title"
                    class="group relative p-6 rounded-2xl border border-white/10 hover:border-[#00D8E8]/35 transition-all duration-300 cursor-default overflow-hidden"
                    style="background: rgba(255,255,255,0.03);"
                >
                    <div
                        class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                        style="background: radial-gradient(ellipse at 0% 0%, rgba(0,216,232,0.06) 0%, transparent 60%);"
                    ></div>

                    <div class="relative w-11 h-11 rounded-xl bg-[#00D8E8]/10 flex items-center justify-center mb-4 flex-shrink-0">
                        <component :is="svc.icon" class="w-5 h-5 text-[#00D8E8]" />
                    </div>

                    <h3 class="relative text-white font-semibold text-[17px] mb-2 leading-snug" style="font-family:'Poppins',sans-serif;">
                        {{ svc.title }}
                    </h3>
                    <p class="relative text-white/50 text-sm leading-relaxed mb-4">{{ svc.desc }}</p>

                    <div class="relative flex flex-wrap gap-1.5">
                        <span
                            v-for="tag in svc.tags"
                            :key="tag"
                            class="text-[10px] font-medium text-[#00D8E8]/70 bg-[#00D8E8]/8 border border-[#00D8E8]/15 rounded-full px-2.5 py-0.5"
                        >
                            {{ tag }}
                        </span>
                    </div>
                </div>

                <!-- CTA card (6th slot) -->
                <button
                    @click="scrollTo('contact')"
                    class="group p-6 rounded-2xl border border-[#00D8E8]/25 hover:border-[#00D8E8]/50 flex flex-col justify-between transition-all duration-300 cursor-pointer text-left"
                    style="background: linear-gradient(135deg, rgba(0,216,232,0.06), rgba(27,45,140,0.12));"
                >
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-[#00D8E8]/15 flex items-center justify-center mb-4">
                            <MessageSquare class="w-5 h-5 text-[#00D8E8]" />
                        </div>
                        <h3 class="text-white font-semibold text-[17px] mb-2" style="font-family:'Poppins',sans-serif;">
                            Votre projet ici
                        </h3>
                        <p class="text-white/50 text-sm leading-relaxed">
                            Besoin d'une solution sur mesure qui ne rentre dans aucune case ? Parlons-en.
                        </p>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 text-[#00D8E8] text-sm font-semibold group-hover:gap-3 transition-all duration-200">
                        Discutons de votre projet
                        <ArrowRight class="w-4 h-4" />
                    </div>
                </button>
            </div>

            <!-- Tech visual strip -->
            <div class="mt-16 rounded-2xl overflow-hidden relative">
                <img
                    src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1400&h=300&q=70&auto=format&fit=crop"
                    alt="Développement web et code"
                    class="w-full h-48 object-cover object-center"
                />
                <div class="absolute inset-0" style="background: linear-gradient(90deg, #0B1437 0%, transparent 30%, transparent 70%, #0B1437 100%);"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <p class="text-white/70 text-lg font-semibold tracking-wide text-center px-4" style="font-family:'Poppins',sans-serif;">
                        Code propre · Livraisons rapides · Infrastructure robuste
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ RÉALISATIONS ════════════════════ -->
    <section id="realisations" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block text-[#1B2D8C] text-xs font-semibold uppercase tracking-widest mb-3">Portfolio</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900" style="font-family:'Poppins',sans-serif;">
                    Nos réalisations
                </h2>
                <p class="mt-4 text-slate-500 max-w-xl mx-auto text-sm leading-relaxed">
                    Des projets concrets, en production, pour des organisations publiques et privées au Cameroun et en Afrique.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="proj in portfolio"
                    :key="proj.name"
                    class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:border-slate-200 hover:shadow-xl hover:shadow-slate-200/70 transition-all duration-300 cursor-default flex flex-col"
                >
                    <!-- Cover image -->
                    <div class="relative h-44 overflow-hidden">
                        <img
                            :src="proj.img"
                            :alt="proj.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        <div
                            class="absolute inset-0"
                            :style="`background: linear-gradient(to bottom, transparent 40%, ${proj.accent}30 100%);`"
                        ></div>
                        <!-- Type badge over image -->
                        <span
                            class="absolute top-3 left-3 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-sm"
                            :style="`background: ${proj.accent}cc;`"
                        >
                            {{ proj.type }}
                        </span>
                    </div>

                    <!-- Accent bar -->
                    <div class="h-1 w-full" :style="`background: ${proj.accent};`"></div>

                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-slate-900 font-bold text-xl mb-2" style="font-family:'Poppins',sans-serif;">
                            {{ proj.name }}
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4 flex-1">{{ proj.desc }}</p>

                        <div class="flex flex-wrap gap-1.5 mb-4">
                            <span
                                v-for="tag in proj.tags"
                                :key="tag"
                                class="text-[10px] font-medium text-slate-500 bg-slate-100 rounded-full px-2.5 py-0.5"
                            >
                                {{ tag }}
                            </span>
                        </div>

                        <a
                            v-if="proj.url"
                            :href="proj.url"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold transition-all duration-200 group/link"
                            :style="`color: ${proj.accent};`"
                            @click.stop
                        >
                            Voir le site
                            <ArrowRight class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 transition-transform duration-200" />
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-slate-400 mb-5 text-sm">Vous voulez rejoindre cette liste ?</p>
                <button
                    @click="scrollTo('contact')"
                    class="inline-flex items-center gap-2 bg-[#1B2D8C] hover:bg-[#1527A0] text-white font-semibold px-7 py-3.5 rounded-xl transition-colors duration-200 cursor-pointer border-0 shadow-lg shadow-[#1B2D8C]/20"
                >
                    Démarrer un projet
                    <ArrowRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </section>

    <!-- ════════════════════ PROCESSUS ════════════════════ -->
    <section id="processus" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left: image -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-slate-200">
                    <img
                        src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=700&h=520&q=80&auto=format&fit=crop"
                        alt="Équipe Diginova en réunion"
                        class="w-full h-96 lg:h-[520px] object-cover"
                    />
                    <!-- Overlay with floating stat -->
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(27,45,140,0.7) 0%, transparent 50%);"></div>
                    <div class="absolute bottom-6 left-6 right-6 flex gap-4">
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-white" style="font-family:'Poppins',sans-serif;">19</div>
                            <div class="text-white/70 text-xs">Projets livrés</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-[#00D8E8]" style="font-family:'Poppins',sans-serif;">8+</div>
                            <div class="text-white/70 text-xs">Ans d'exp.</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-white" style="font-family:'Poppins',sans-serif;">100%</div>
                            <div class="text-white/70 text-xs">En prod.</div>
                        </div>
                    </div>
                </div>

                <!-- Right: steps -->
                <div>
                    <span class="inline-block text-[#1B2D8C] text-xs font-semibold uppercase tracking-widest mb-3">Comment ça marche</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4" style="font-family:'Poppins',sans-serif;">
                        Notre processus en 4 étapes
                    </h2>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10">
                        Un cadre clair et éprouvé pour chaque projet, de la première conversation à la mise en ligne.
                    </p>

                    <div class="space-y-6">
                        <div v-for="step in process" :key="step.n" class="flex gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-[#1B2D8C] flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#1B2D8C]/20">
                                <span class="text-xl font-bold text-white" style="font-family:'Poppins',sans-serif;">
                                    {{ step.n }}
                                </span>
                            </div>
                            <div class="pt-1">
                                <h3 class="text-slate-900 font-bold text-base mb-1" style="font-family:'Poppins',sans-serif;">
                                    {{ step.title }}
                                </h3>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ step.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ STATS ════════════════════ -->
    <section
        ref="statsSection"
        class="py-20 relative overflow-hidden"
        style="background: linear-gradient(135deg, #1B2D8C 0%, #0B1437 100%);"
    >
        <!-- Background image overlay -->
        <div class="absolute inset-0 opacity-10">
            <img
                src="https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=1400&h=400&q=60&auto=format&fit=crop"
                alt=""
                class="w-full h-full object-cover"
            />
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div
                    v-for="(s, i) in stats"
                    :key="s.label"
                    class="transition-all duration-700"
                    :class="statsVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    :style="`transition-delay: ${i * 100}ms`"
                >
                    <div
                        class="text-4xl lg:text-5xl font-extrabold mb-1"
                        style="font-family:'Poppins',sans-serif; background: linear-gradient(90deg, #ffffff, #00D8E8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
                    >
                        {{ s.value }}
                    </div>
                    <p class="text-white/55 text-sm">{{ s.label }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CONTACT ════════════════════ -->
    <section id="contact" class="py-24" style="background: #050D2A;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                <!-- Left: info -->
                <div>
                    <span class="inline-block text-[#00D8E8] text-xs font-semibold uppercase tracking-widest mb-3">Parlons de votre projet</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-5" style="font-family:'Poppins',sans-serif;">
                        Prêt à démarrer ?
                    </h2>
                    <p class="text-white/55 leading-relaxed mb-8 text-sm">
                        Décrivez votre projet et nous vous répondons sous <strong class="text-white/80">24h</strong> avec une estimation gratuite.
                        Ou commencez directement sur WhatsApp pour une réponse immédiate.
                    </p>

                    <!-- Office photo -->
                    <div class="rounded-2xl overflow-hidden mb-8 border border-white/10">
                        <img
                            src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=240&q=75&auto=format&fit=crop"
                            alt="Bureau Diginova Yaoundé"
                            class="w-full h-44 object-cover"
                        />
                    </div>

                    <!-- WhatsApp CTA -->
                    <a
                        href="https://wa.me/237655065494?text=Bonjour%2C%20je%20souhaite%20d%C3%A9marrer%20un%20projet%20avec%20Diginova."
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-3 bg-[#25D366] hover:bg-[#20bb5a] text-white font-semibold px-6 py-3.5 rounded-xl transition-all duration-200 cursor-pointer mb-8 shadow-lg shadow-[#25D366]/20"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Écrire sur WhatsApp
                    </a>

                    <!-- Contact details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <Phone class="w-4 h-4 text-[#00D8E8]" />
                            </div>
                            <a href="tel:+237655065494" class="text-white/65 hover:text-[#00D8E8] text-sm transition-colors cursor-pointer">
                                +237 655 065 494
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <Mail class="w-4 h-4 text-[#00D8E8]" />
                            </div>
                            <a href="mailto:contact@diginova.cm" class="text-white/65 hover:text-[#00D8E8] text-sm transition-colors cursor-pointer">
                                contact@diginova.cm
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <MapPin class="w-4 h-4 text-[#00D8E8]" />
                            </div>
                            <span class="text-white/65 text-sm">Yaoundé, Cameroun</span>
                        </div>
                    </div>
                </div>

                <!-- Right: form -->
                <div>
                    <!-- Success state -->
                    <div
                        v-if="sent"
                        class="text-center py-16 px-8 rounded-2xl border border-white/10"
                        style="background: rgba(255,255,255,0.03);"
                    >
                        <div class="w-16 h-16 rounded-full bg-[#00D8E8]/15 flex items-center justify-center mx-auto mb-4">
                            <Check class="w-8 h-8 text-[#00D8E8]" stroke-width="2.5" />
                        </div>
                        <h3 class="text-white text-xl font-bold mb-2" style="font-family:'Poppins',sans-serif;">
                            Message envoyé !
                        </h3>
                        <p class="text-white/55 text-sm">Nous vous répondrons sous 24h. Merci de votre confiance.</p>
                    </div>

                    <!-- Form -->
                    <form
                        v-else
                        @submit.prevent="handleSubmit"
                        class="space-y-4 p-6 sm:p-8 rounded-2xl border border-white/10"
                        style="background: rgba(255,255,255,0.03);"
                    >
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="f-name" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Nom complet <span class="text-[#00D8E8]">*</span>
                                </label>
                                <input
                                    id="f-name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Votre nom"
                                    class="w-full bg-white/5 border border-white/10 focus:border-[#00D8E8]/60 text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label for="f-email" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Email <span class="text-[#00D8E8]">*</span>
                                </label>
                                <input
                                    id="f-email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="email@exemple.cm"
                                    class="w-full bg-white/5 border border-white/10 focus:border-[#00D8E8]/60 text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="f-phone" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Téléphone / WhatsApp
                                </label>
                                <input
                                    id="f-phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+237 6XX XXX XXX"
                                    class="w-full bg-white/5 border border-white/10 focus:border-[#00D8E8]/60 text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label for="f-type" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Type de projet
                                </label>
                                <select
                                    id="f-type"
                                    v-model="form.project_type"
                                    class="w-full bg-[#0B1437] border border-white/10 focus:border-[#00D8E8]/60 text-white/70 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 cursor-pointer"
                                >
                                    <option value="">Choisir...</option>
                                    <option v-for="t in projectTypes" :key="t" :value="t">{{ t }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="f-budget" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                Budget estimé
                            </label>
                            <select
                                id="f-budget"
                                v-model="form.budget"
                                class="w-full bg-[#0B1437] border border-white/10 focus:border-[#00D8E8]/60 text-white/70 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 cursor-pointer"
                            >
                                <option value="">Sélectionner une fourchette...</option>
                                <option v-for="b in budgets" :key="b" :value="b">{{ b }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="f-message" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                Description du projet <span class="text-[#00D8E8]">*</span>
                            </label>
                            <textarea
                                id="f-message"
                                v-model="form.message"
                                required
                                rows="5"
                                placeholder="Décrivez votre projet, vos objectifs, vos contraintes techniques ou métier..."
                                class="w-full bg-white/5 border border-white/10 focus:border-[#00D8E8]/60 text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 resize-none"
                            ></textarea>
                        </div>

                        <p v-if="formError" class="text-red-400 text-xs">{{ formError }}</p>

                        <button
                            type="submit"
                            :disabled="sending"
                            class="w-full flex items-center justify-center gap-2.5 bg-[#00D8E8] hover:bg-[#00C2D0] disabled:opacity-60 disabled:cursor-not-allowed text-[#070E24] font-bold py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#00D8E8]/20 text-sm border-0"
                        >
                            <Loader2 v-if="sending" class="w-4 h-4 animate-spin" />
                            <Send v-else class="w-4 h-4" />
                            {{ sending ? 'Envoi en cours...' : 'Envoyer ma demande' }}
                        </button>

                        <p class="text-center text-white/30 text-xs">
                            Réponse garantie sous 24h · Estimation gratuite et sans engagement
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
