<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import { useReveal, useCountUp, prefersReducedMotion } from '@/composables/useReveal'
import {
    ArrowRight, ChevronDown, Check, Send, Loader2,
    Phone, Mail, MapPin, Smartphone,
    Code2, Cloud, Network, Terminal, Zap, MessageSquare,
} from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

function scrollTo(id) {
    const el = document.getElementById(id)
    if (!el) return
    const top = el.getBoundingClientRect().top + window.scrollY - 72
    window.scrollTo({ top, behavior: 'smooth' })
}

// Hero : entrée déclenchée au montage (au-dessus de la ligne de flottaison, pas de scroll requis)
const heroLoaded = ref(false)
onMounted(() => requestAnimationFrame(() => { heroLoaded.value = true }))

// Hero : parallax léger sur la photo de fond
const heroParallax = ref(0)
let parallaxRaf = null
function onHeroScroll() {
    if (parallaxRaf) return
    parallaxRaf = requestAnimationFrame(() => {
        heroParallax.value = Math.min(window.scrollY * 0.18, 120)
        parallaxRaf = null
    })
}
onMounted(() => {
    if (!prefersReducedMotion()) window.addEventListener('scroll', onHeroScroll, { passive: true })
})
onUnmounted(() => window.removeEventListener('scroll', onHeroScroll))

// Bandeau logos : marquee en continu, sauf préférence "réduire les animations"
const motionOk = ref(true)
onMounted(() => { motionOk.value = !prefersReducedMotion() })

const { target: statsSection, visible: statsVisible } = useReveal(0.3)
const { target: servicesGrid, visible: servicesVisible } = useReveal(0.15)
const { target: portfolioGrid, visible: portfolioVisible } = useReveal(0.1)
const { target: processSection, visible: processVisible } = useReveal(0.25)

const form = ref({ name: '', email: '', phone: '', project_type: '', budget: '', message: '' })
const sending = ref(false)
const sent = ref(false)
const formError = ref('')
const touched = reactive({ name: false, email: false, message: false })

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

const errors = computed(() => ({
    name: form.value.name.trim() ? '' : 'Indiquez votre nom.',
    email: !form.value.email.trim()
        ? 'Indiquez votre email.'
        : !emailPattern.test(form.value.email.trim())
            ? 'Cette adresse email semble invalide.'
            : '',
    message: form.value.message.trim().length >= 10 ? '' : 'Décrivez votre projet en quelques mots (10 caractères minimum).',
}))

const isFormValid = computed(() => !errors.value.name && !errors.value.email && !errors.value.message)

function touch(field) {
    touched[field] = true
}

async function handleSubmit() {
    formError.value = ''
    touched.name = true
    touched.email = true
    touched.message = true
    if (!isFormValid.value) return
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
        area: 'featured',
        featured: true,
    },
    {
        title: 'Applications SaaS',
        desc: 'Conception et déploiement de plateformes SaaS multi-tenant, de la maquette au Go-Live.',
        tags: ['SaaS', 'Multi-tenant', 'API REST'],
        icon: Cloud,
        area: 'saas',
    },
    {
        title: 'Microservices & API',
        desc: 'Architecture microservices, API Gateway, intégration de services tiers et ETL.',
        tags: ['Node.js', 'Docker', 'REST', 'GraphQL'],
        icon: Network,
        area: 'micro',
    },
    {
        title: 'DevOps & CI/CD',
        desc: 'Pipelines CI/CD, containerisation Docker, déploiement continu sur VPS ou cloud.',
        tags: ['Docker', 'GitHub Actions', 'Nginx', 'Linux'],
        icon: Terminal,
        area: 'devops',
    },
    {
        title: 'Transformation Digitale',
        desc: 'Audit, conseil stratégique et accompagnement pour digitaliser vos processus métier.',
        tags: ['Audit', 'Conseil', 'Formation', 'Support'],
        icon: Zap,
        area: 'transfo',
    },
    {
        title: 'Messagerie Pro & Automatisation IA',
        desc: 'Adresse professionnelle @votredomaine.cm, assistant e-mail IA et automatisation de vos workflows. Dès 2 000 F/mois.',
        tags: ['Messagerie', 'IA', 'Automatisation'],
        icon: Mail,
        link: '/messagerie-pro',
        area: 'msg',
    },
    {
        title: 'SMS Pro & Campagnes',
        desc: 'Notifications, alertes, OTP/2FA et campagnes marketing par SMS, plus de 95% de taux de lecture. Plateforme web et API.',
        tags: ['SMS', 'OTP/2FA', 'API'],
        icon: Smartphone,
        link: '/sms',
        area: 'sms',
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
    { value: '19+', label: 'Projets livrés' },
    { value: '100%', label: 'En production' },
].map((s) => ({ ...s, display: useCountUp(s.value, statsVisible) }))

const logoFailed = reactive({})

const clients = [
    { name: 'BGFIBank',    logo: '/images/clients/bgfibank.webp' },
    { name: 'MinDef',      logo: '/images/clients/mindef.webp' },
    { name: 'USRA-CARE',   logo: '/images/clients/usra-care.webp' },
    { name: 'ADS360',      logo: '/images/clients/ads360.webp' },
    { name: 'CaregFA',     logo: '/images/clients/caregfa.webp' },
    { name: 'Census',      logo: '/images/clients/census.webp' },
    { name: 'DECH School', logo: 'https://dech-school.diginova.cm/images/Header/logo.svg' },
    { name: 'FreeSurf',    logo: '/images/clients/freesurf.webp' },
    { name: 'MBAC',        logo: '/images/clients/mbac.webp' },
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
    <Head>
        <title>Diginova — Développement Web & SaaS | Yaoundé, Cameroun</title>
        <meta name="description" content="Agence de développement web et SaaS à Yaoundé. Applications sur mesure, microservices, DevOps. 8 ans d'expérience · 19+ projets en production." />
    </Head>

    <!-- ════════════════════ HERO ════════════════════ -->
    <section class="relative min-h-dvh flex flex-col justify-center overflow-hidden">

        <!-- Background photo + dark overlay (parallax léger) -->
        <div class="absolute inset-0 -top-16 -bottom-16" :style="`transform: translateY(${heroParallax}px);`">
            <img
                src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1600&h=900&q=80&auto=format&fit=crop"
                alt=""
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0" style="background: linear-gradient(105deg, rgba(6,21,20,0.95) 0%, rgba(10,36,34,0.85) 45%, rgba(10,36,34,0.65) 100%);"></div>
        </div>

        <!-- Cercle décoratif bas-gauche -->
        <div class="pointer-events-none absolute bottom-16 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>
        <div class="pointer-events-none absolute bottom-24 left-20 w-32 h-32 rounded-full border border-[#30998A]/10"></div>

        <!-- Cercle décoratif haut-droite -->
        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>

        <!-- Accent dot -->
        <div class="pointer-events-none absolute top-1/3 left-1/2 w-2 h-2 rounded-full bg-[#30998A]/60"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 w-full">

            <!-- 2-column -->
            <div class="grid lg:grid-cols-[1fr_420px] gap-12 lg:gap-16 items-center">

                <!-- Left: text -->
                <div>
                    <!-- Headline -->
                    <h1
                        class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white leading-[1.05] mb-6 font-display transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    >
                        Votre partenaire<br>
                        <span class="text-[#30998A]">digital</span><br>
                        de confiance
                    </h1>

                    <!-- Subtitle -->
                    <p
                        class="text-white/55 text-lg leading-relaxed mb-10 max-w-xl transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="transition-delay: 120ms;"
                    >
                        Développement web sur mesure, applications SaaS, microservices et DevOps —
                        tout sous un même toit, une seule équipe.
                    </p>

                    <!-- CTAs -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="transition-delay: 220ms;"
                    >
                        <button
                            @click="scrollTo('contact')"
                            class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 text-base justify-center border-0 focus-ring"
                        >
                            Démarrer un projet
                            <ArrowRight class="w-5 h-5" />
                        </button>
                        <button
                            @click="scrollTo('realisations')"
                            class="inline-flex items-center gap-2.5 border border-white/30 hover:border-[#30998A]/50 active:scale-95 text-white hover:text-[#30998A] font-semibold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer text-base justify-center backdrop-blur-sm bg-white/5 focus-ring"
                        >
                            Voir nos réalisations →
                        </button>
                    </div>
                </div>

                <!-- Right: stat cards 2×2 -->
                <div class="hidden lg:grid grid-cols-2 gap-4">
                    <!-- Card 1 — accent -->
                    <div
                        class="rounded-2xl p-6 backdrop-blur-md border border-[#30998A]/30 transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="background: rgba(48,153,138,0.12); transition-delay: 280ms;"
                    >
                        <p class="text-4xl font-extrabold text-white mb-2 font-display">8+</p>
                        <p class="text-white/60 text-sm leading-tight">Années<br>d'expérience</p>
                    </div>
                    <!-- Card 2 — glass -->
                    <div
                        class="rounded-2xl p-6 backdrop-blur-md border border-white/10 transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="background: rgba(255,255,255,0.05); transition-delay: 340ms;"
                    >
                        <p class="text-4xl font-extrabold text-white mb-2 font-display">24/7</p>
                        <p class="text-white/60 text-sm leading-tight">Support<br>dédié</p>
                    </div>
                    <!-- Card 3 — glass -->
                    <div
                        class="rounded-2xl p-6 backdrop-blur-md border border-white/10 transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="background: rgba(255,255,255,0.05); transition-delay: 400ms;"
                    >
                        <p class="text-4xl font-extrabold text-white mb-2 font-display">19+</p>
                        <p class="text-white/60 text-sm leading-tight">Projets<br>livrés</p>
                    </div>
                    <!-- Card 4 — accent -->
                    <div
                        class="rounded-2xl p-6 backdrop-blur-md border border-[#30998A]/30 transition-all duration-700 ease-out"
                        :class="heroLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                        style="background: rgba(48,153,138,0.12); transition-delay: 460ms;"
                    >
                        <p class="text-4xl font-extrabold text-white mb-2 font-display">100%</p>
                        <p class="text-white/60 text-sm leading-tight">En<br>production</p>
                    </div>
                </div>
            </div>

            <!-- Trust / clients -->
            <div class="mt-20">
                <!-- Label -->
                <div class="flex items-center gap-4 mb-10">
                    <div class="flex-1 h-px bg-white/10"></div>
                    <span class="text-white/50 text-[11px] uppercase tracking-[0.18em] whitespace-nowrap">Ils nous font confiance</span>
                    <div class="flex-1 h-px bg-white/10"></div>
                </div>

                <!-- Logos en défilement continu (pause au survol) -->
                <div v-if="motionOk" class="relative overflow-hidden" style="mask-image: linear-gradient(90deg, transparent, black 8%, black 92%, transparent);">
                    <div class="flex items-center gap-16 w-max animate-marquee">
                        <div
                            v-for="(c, idx) in [...clients, ...clients]"
                            :key="`${c.name}-${idx}`"
                            class="group flex flex-col items-center gap-3 flex-shrink-0"
                        >
                            <div class="h-16 flex items-center justify-center px-2">
                                <img
                                    v-if="c.logo && !logoFailed[c.name]"
                                    :src="c.logo"
                                    :alt="c.name"
                                    width="110"
                                    height="56"
                                    loading="lazy"
                                    class="max-h-14 max-w-[110px] w-auto object-contain opacity-75 group-hover:opacity-100 transition-opacity duration-300"
                                    @error="logoFailed[c.name] = true"
                                />
                                <span v-else class="text-white/40 text-base font-bold group-hover:text-white/70 transition-colors duration-300">
                                    {{ c.name.slice(0, 2).toUpperCase() }}
                                </span>
                            </div>
                            <span class="text-white/50 group-hover:text-white/70 text-[10px] font-medium tracking-wide transition-colors duration-300 whitespace-nowrap">
                                {{ c.name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Grille statique (prefers-reduced-motion) -->
                <div v-else class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-y-8">
                    <div
                        v-for="c in clients"
                        :key="c.name"
                        class="group flex flex-col items-center gap-3"
                    >
                        <div class="h-16 flex items-center justify-center px-2">
                            <img
                                v-if="c.logo && !logoFailed[c.name]"
                                :src="c.logo"
                                :alt="c.name"
                                width="110"
                                height="56"
                                loading="lazy"
                                class="max-h-14 max-w-[110px] w-auto object-contain opacity-75 group-hover:opacity-100 transition-opacity duration-300"
                                @error="logoFailed[c.name] = true"
                            />
                            <span v-else class="text-white/40 text-base font-bold group-hover:text-white/70 transition-colors duration-300">
                                {{ c.name.slice(0, 2).toUpperCase() }}
                            </span>
                        </div>
                        <span class="text-white/50 group-hover:text-white/70 text-[10px] font-medium tracking-wide transition-colors duration-300">
                            {{ c.name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1.5 opacity-40">
            <span class="text-white text-[11px] uppercase tracking-widest">Découvrir</span>
            <ChevronDown class="w-4 h-4 text-white animate-bounce" />
        </div>
    </section>

    <!-- ════════════════════ SERVICES ════════════════════ -->
    <section id="services" aria-label="Nos services" class="py-24" style="background: #0D2B29;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Nos expertises</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white font-display">
                    Ce que nous construisons
                </h2>
                <p class="mt-4 text-white/50 max-w-xl mx-auto text-sm leading-relaxed">
                    De la conception à la production, nous couvrons toute la chaîne de valeur digitale.
                </p>
            </div>

            <!-- Services en bento asymétrique (casse la grille 3 colonnes uniforme) -->
            <div ref="servicesGrid" class="services-bento">
                <component
                    :is="svc.link ? Link : 'div'"
                    v-for="(svc, i) in services"
                    :key="svc.title"
                    :href="svc.link"
                    :data-area="svc.area"
                    class="group relative p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300 overflow-hidden flex flex-col"
                    :class="[
                        svc.link ? 'cursor-pointer focus-ring' : 'cursor-default',
                        svc.featured ? 'lg:p-8' : '',
                        servicesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6',
                    ]"
                    :style="`background: rgba(255,255,255,0.03); transition: opacity 700ms ease-out ${i * 70}ms, transform 700ms ease-out ${i * 70}ms, border-color 300ms;`"
                >
                    <div
                        class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                        style="background: radial-gradient(ellipse at 0% 0%, rgba(48,153,138,0.06) 0%, transparent 60%);"
                    ></div>

                    <div
                        class="relative rounded-xl bg-[#30998A]/10 flex items-center justify-center mb-4 flex-shrink-0"
                        :class="svc.featured ? 'w-14 h-14' : 'w-11 h-11'"
                    >
                        <component :is="svc.icon" :class="svc.featured ? 'w-7 h-7' : 'w-5 h-5'" class="text-[#30998A]" />
                    </div>

                    <h3
                        class="relative text-white font-semibold leading-snug font-display"
                        :class="svc.featured ? 'text-2xl mb-3' : 'text-[17px] mb-2'"
                    >
                        {{ svc.title }}
                    </h3>
                    <p class="relative text-white/50 text-sm leading-relaxed mb-4" :class="svc.featured ? 'max-w-sm' : ''">{{ svc.desc }}</p>

                    <div class="relative flex flex-wrap gap-1.5 mt-auto">
                        <span
                            v-for="tag in svc.tags"
                            :key="tag"
                            class="text-[10px] font-medium text-[#30998A]/70 bg-[#30998A]/8 border border-[#30998A]/15 rounded-full px-2.5 py-0.5"
                        >
                            {{ tag }}
                        </span>
                    </div>
                </component>

                <!-- CTA card -->
                <button
                    @click="scrollTo('contact')"
                    data-area="cta"
                    class="group p-6 rounded-2xl border border-[#30998A]/25 hover:border-[#30998A]/50 flex flex-col justify-between transition-all duration-300 cursor-pointer text-left focus-ring"
                    :class="servicesVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    :style="`background: linear-gradient(135deg, rgba(48,153,138,0.06), rgba(29,84,87,0.12)); transition: opacity 700ms ease-out 560ms, transform 700ms ease-out 560ms, border-color 300ms;`"
                >
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-[#30998A]/15 flex items-center justify-center mb-4">
                            <MessageSquare class="w-5 h-5 text-[#30998A]" />
                        </div>
                        <h3 class="text-white font-semibold text-[17px] mb-2 font-display">
                            Votre projet ici
                        </h3>
                        <p class="text-white/50 text-sm leading-relaxed">
                            Besoin d'une solution sur mesure qui ne rentre dans aucune case ? Parlons-en.
                        </p>
                    </div>
                    <div class="mt-6 inline-flex items-center gap-2 text-[#30998A] text-sm font-semibold group-hover:gap-3 transition-all duration-200">
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
                    loading="lazy"
                    class="w-full h-48 object-cover object-center"
                />
                <div class="absolute inset-0" style="background: linear-gradient(90deg, #0D2B29 0%, transparent 30%, transparent 70%, #0D2B29 100%);"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <p class="text-white/70 text-lg font-semibold tracking-wide text-center px-4 font-display">
                        Code propre · Livraisons rapides · Infrastructure robuste
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ RÉALISATIONS ════════════════════ -->
    <section id="realisations" aria-label="Nos réalisations" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-3">Portfolio</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 font-display">
                    Nos réalisations
                </h2>
                <p class="mt-4 text-slate-500 max-w-xl mx-auto text-sm leading-relaxed">
                    Des projets concrets, en production, pour des organisations publiques et privées au Cameroun et en Afrique.
                </p>
            </div>

            <div ref="portfolioGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div
                    v-for="(proj, i) in portfolio"
                    :key="proj.name"
                    class="portfolio-card group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:-translate-y-1.5 transition-all duration-300 cursor-default flex flex-col"
                    :class="portfolioVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    :style="`--accent: ${proj.accent}; --accent-shadow: ${proj.accent}40; transition: opacity 600ms ease-out ${i * 60}ms, transform 300ms ease-out, box-shadow 300ms, border-color 300ms;`"
                >
                    <!-- Cover image -->
                    <div class="relative h-44 overflow-hidden">
                        <img
                            :src="proj.img"
                            :alt="proj.name"
                            loading="lazy"
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
                        <h3 class="text-slate-900 font-bold text-xl mb-2 font-display">
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
                            class="inline-flex items-center gap-1.5 text-xs font-semibold transition-all duration-200 group/link rounded focus-ring-light"
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
                <p class="text-slate-600 mb-5 text-sm">Vous voulez rejoindre cette liste ?</p>
                <button
                    @click="scrollTo('contact')"
                    class="inline-flex items-center gap-2 bg-[#1D5457] hover:bg-[#30998A] active:scale-95 text-white font-semibold px-7 py-3.5 rounded-xl transition-all duration-200 cursor-pointer border-0 shadow-lg shadow-[#1D5457]/20 focus-ring-light"
                >
                    Démarrer un projet
                    <ArrowRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </section>

    <!-- ════════════════════ PROCESSUS ════════════════════ -->
    <section id="processus" aria-label="Notre processus" class="py-24 bg-white">
        <div ref="processSection" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left: image -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-slate-200">
                    <img
                        src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=700&h=520&q=80&auto=format&fit=crop"
                        alt="Équipe Diginova en réunion"
                        loading="lazy"
                        class="w-full h-96 lg:h-[520px] object-cover"
                    />
                    <!-- Overlay with floating stat -->
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(29,84,87,0.7) 0%, transparent 50%);"></div>
                    <div class="absolute bottom-6 left-6 right-6 flex gap-4">
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-white font-display">19+</div>
                            <div class="text-white/70 text-xs">Projets livrés</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-[#30998A] font-display">8+</div>
                            <div class="text-white/70 text-xs">Ans d'exp.</div>
                        </div>
                        <div class="bg-white/15 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex-1 text-center">
                            <div class="text-2xl font-bold text-white font-display">100%</div>
                            <div class="text-white/70 text-xs">En prod.</div>
                        </div>
                    </div>
                </div>

                <!-- Right: steps -->
                <div>
                    <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-3">Comment ça marche</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4 font-display">
                        Notre processus en 4 étapes
                    </h2>
                    <p class="text-slate-500 text-sm leading-relaxed mb-10">
                        Un cadre clair et éprouvé pour chaque projet, de la première conversation à la mise en ligne.
                    </p>

                    <div class="space-y-6">
                        <div
                            v-for="(step, i) in process"
                            :key="step.n"
                            class="flex gap-5"
                            :class="processVisible ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'"
                            :style="`transition: opacity 600ms ease-out ${i * 110}ms, transform 600ms ease-out ${i * 110}ms;`"
                        >
                            <div class="w-14 h-14 rounded-2xl bg-[#1D5457] flex items-center justify-center flex-shrink-0 shadow-lg shadow-[#1D5457]/20">
                                <span class="text-xl font-bold text-white font-display">
                                    {{ step.n }}
                                </span>
                            </div>
                            <div class="pt-1">
                                <h3 class="text-slate-900 font-bold text-base mb-1 font-display">
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
        style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
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
                        class="text-4xl lg:text-5xl font-extrabold mb-1 font-display"
                        style="background: linear-gradient(90deg, #ffffff, #30998A); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"
                    >
                        {{ s.display }}
                    </div>
                    <p class="text-white/55 text-sm">{{ s.label }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════════ CONTACT ════════════════════ -->
    <section id="contact" aria-label="Nous contacter" class="py-24" style="background: #061514;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

                <!-- Left: info -->
                <div>
                    <span class="inline-block text-[#30998A] text-xs font-semibold uppercase tracking-widest mb-3">Parlons de votre projet</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-5 font-display">
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
                            loading="lazy"
                            class="w-full h-44 object-cover"
                        />
                    </div>

                    <!-- WhatsApp CTA -->
                    <a
                        href="https://wa.me/237655065494?text=Bonjour%2C%20je%20souhaite%20d%C3%A9marrer%20un%20projet%20avec%20Diginova."
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center gap-3 bg-[#25D366] hover:bg-[#20bb5a] active:scale-95 text-[#0A2422] font-semibold px-6 py-3.5 rounded-xl transition-all duration-200 cursor-pointer mb-8 shadow-lg shadow-[#25D366]/20 focus-ring"
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
                                <Phone class="w-4 h-4 text-[#30998A]" />
                            </div>
                            <a href="tel:+237655065494" class="text-white/65 hover:text-[#30998A] text-sm transition-colors cursor-pointer rounded focus-ring">
                                +237 655 065 494
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <Mail class="w-4 h-4 text-[#30998A]" />
                            </div>
                            <a href="mailto:contact@diginova.cm" class="text-white/65 hover:text-[#30998A] text-sm transition-colors cursor-pointer rounded focus-ring">
                                contact@diginova.cm
                            </a>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <MapPin class="w-4 h-4 text-[#30998A]" />
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
                        <div class="w-16 h-16 rounded-full bg-[#30998A]/15 flex items-center justify-center mx-auto mb-4">
                            <Check class="w-8 h-8 text-[#30998A]" stroke-width="2.5" />
                        </div>
                        <h3 class="text-white text-xl font-bold mb-2 font-display">
                            Message envoyé
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
                                    Nom complet <span class="text-[#30998A]">*</span>
                                </label>
                                <input
                                    id="f-name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Votre nom"
                                    :aria-invalid="touched.name && !!errors.name"
                                    aria-describedby="f-name-error"
                                    @blur="touch('name')"
                                    class="w-full bg-white/5 border text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 focus:ring-2"
                                    :class="touched.name && errors.name
                                        ? 'border-red-400/60 focus:border-red-400/70 focus:ring-red-400/30'
                                        : 'border-white/10 focus:border-[#30998A]/60 focus:ring-[#30998A]/40'"
                                />
                                <p v-if="touched.name && errors.name" id="f-name-error" class="text-red-300 text-xs mt-1.5">{{ errors.name }}</p>
                            </div>
                            <div>
                                <label for="f-email" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Email <span class="text-[#30998A]">*</span>
                                </label>
                                <input
                                    id="f-email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="email@exemple.cm"
                                    :aria-invalid="touched.email && !!errors.email"
                                    aria-describedby="f-email-error"
                                    @blur="touch('email')"
                                    class="w-full bg-white/5 border text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 focus:ring-2"
                                    :class="touched.email && errors.email
                                        ? 'border-red-400/60 focus:border-red-400/70 focus:ring-red-400/30'
                                        : 'border-white/10 focus:border-[#30998A]/60 focus:ring-[#30998A]/40'"
                                />
                                <p v-if="touched.email && errors.email" id="f-email-error" class="text-red-300 text-xs mt-1.5">{{ errors.email }}</p>
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
                                    class="w-full bg-white/5 border border-white/10 focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40 text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200"
                                />
                            </div>
                            <div>
                                <label for="f-type" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                    Type de projet
                                </label>
                                <select
                                    id="f-type"
                                    v-model="form.project_type"
                                    class="w-full bg-[#0D2B29] border border-white/10 focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40 text-white/70 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 cursor-pointer"
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
                                class="w-full bg-[#0D2B29] border border-white/10 focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40 text-white/70 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 cursor-pointer"
                            >
                                <option value="">Sélectionner une fourchette...</option>
                                <option v-for="b in budgets" :key="b" :value="b">{{ b }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="f-message" class="block text-white/60 text-xs font-medium mb-1.5 uppercase tracking-wide">
                                Description du projet <span class="text-[#30998A]">*</span>
                            </label>
                            <textarea
                                id="f-message"
                                v-model="form.message"
                                rows="5"
                                placeholder="Décrivez votre projet, vos objectifs, vos contraintes techniques ou métier..."
                                :aria-invalid="touched.message && !!errors.message"
                                aria-describedby="f-message-error"
                                @blur="touch('message')"
                                class="w-full bg-white/5 border text-white placeholder-white/25 rounded-xl px-4 py-3 text-sm outline-none transition-colors duration-200 resize-none focus:ring-2"
                                :class="touched.message && errors.message
                                    ? 'border-red-400/60 focus:border-red-400/70 focus:ring-red-400/30'
                                    : 'border-white/10 focus:border-[#30998A]/60 focus:ring-[#30998A]/40'"
                            ></textarea>
                            <p v-if="touched.message && errors.message" id="f-message-error" class="text-red-300 text-xs mt-1.5">{{ errors.message }}</p>
                        </div>

                        <p v-if="formError" class="text-red-300 text-xs" role="alert">{{ formError }}</p>

                        <button
                            type="submit"
                            :disabled="sending"
                            class="w-full flex items-center justify-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed disabled:active:scale-100 text-[#0A2422] font-bold py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm border-0 focus-ring"
                        >
                            <Loader2 v-if="sending" class="w-4 h-4 animate-spin" />
                            <Send v-else class="w-4 h-4" />
                            {{ sending ? 'Envoi en cours...' : 'Envoyer ma demande' }}
                        </button>

                        <p class="text-center text-white/50 text-xs">
                            Réponse garantie sous 24h · Estimation gratuite et sans engagement
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

