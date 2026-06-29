<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import ShareButtons from '@/components/ShareButtons.vue'
import { Rocket, Check, MessageCircle, ArrowRightLeft, Globe, GraduationCap } from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

const PAGE_URL = 'https://diginova.cm/messagerie-pro/pack-demarrage'

function wa(message) {
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
}

const features = [
    { icon: ArrowRightLeft, title: 'Migration de vos boîtes mail', desc: 'Transfert de vos emails et contacts existants, sans perte de données.' },
    { icon: Globe, title: 'Configuration du domaine', desc: 'Réglages DNS, SPF, DKIM — tout est configuré pour une délivrabilité optimale.' },
    { icon: GraduationCap, title: 'Formation de vos équipes', desc: 'Prise en main du webmail et des bonnes pratiques, pour démarrer sans friction.' },
]
</script>

<template>
    <Head>
        <title>Pack Démarrage Messagerie | Diginova</title>
        <meta name="description" content="Migration de vos boîtes mail, configuration du domaine et formation de vos équipes. À partir de 50 000 F." />
        <meta property="og:title" content="Pack Démarrage Messagerie | Diginova" />
        <meta property="og:description" content="Migration de vos boîtes mail, configuration du domaine et formation de vos équipes." />
        <meta property="og:url" :content="PAGE_URL" />
        <meta property="og:type" content="website" />
    </Head>

    <section class="relative overflow-hidden pt-32 pb-20" style="background: #0A2422;">
        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <Link href="/messagerie-pro" class="text-white/40 hover:text-[#30998A] text-xs uppercase tracking-widest mb-6 inline-block cursor-pointer">
                ← Messagerie Pro
            </Link>

            <div class="w-14 h-14 rounded-2xl bg-[#30998A]/15 flex items-center justify-center mx-auto mb-6">
                <Rocket class="w-7 h-7 text-[#30998A]" />
            </div>

            <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-[1.1] mb-5 font-display">
                Pack <span class="text-[#30998A]">Démarrage</span>
            </h1>
            <p class="text-white/65 text-lg max-w-xl mx-auto mb-8">
                Passez à une messagerie professionnelle sans rien gérer vous-même : on migre, on configure, on forme vos équipes.
            </p>

            <div class="inline-flex items-baseline gap-1.5 mb-8">
                <span class="text-white/45 text-sm">À partir de</span>
                <span class="text-3xl font-extrabold text-white font-display">50 000 F</span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a
                    :href="wa('Bonjour, je souhaite démarrer avec le Pack Démarrage Messagerie.')"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 justify-center focus-ring"
                >
                    <MessageCircle class="w-5 h-5" />
                    Démarrer maintenant
                </a>
            </div>

            <div class="flex justify-center">
                <ShareButtons title="Pack Démarrage Messagerie — Diginova" :url="PAGE_URL" />
            </div>
        </div>
    </section>

    <section class="py-24" style="background: #0D2B29;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="inline-block text-brand-400 text-xs font-semibold uppercase tracking-widest mb-3">Ce qui est inclus</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                    Tout pour démarrer sereinement
                </h2>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div v-for="f in features" :key="f.title" class="p-6 rounded-2xl border border-white/10 hover:border-[#30998A]/35 transition-all duration-300">
                    <div class="w-11 h-11 rounded-xl bg-[#30998A]/10 flex items-center justify-center mb-4">
                        <component :is="f.icon" class="w-5 h-5 text-[#30998A]" />
                    </div>
                    <h3 class="text-white font-semibold mb-2">{{ f.title }}</h3>
                    <p class="text-white/55 text-sm leading-relaxed">{{ f.desc }}</p>
                </div>
            </div>

            <ul class="mt-10 max-w-xl mx-auto space-y-3">
                <li class="flex items-start gap-2.5 text-white/70 text-sm">
                    <Check class="w-4 h-4 text-[#30998A] flex-shrink-0 mt-0.5" />
                    Idéal pour quitter Gmail/Yahoo ou changer de prestataire
                </li>
                <li class="flex items-start gap-2.5 text-white/70 text-sm">
                    <Check class="w-4 h-4 text-[#30998A] flex-shrink-0 mt-0.5" />
                    Tarif final selon le nombre de boîtes à migrer
                </li>
                <li class="flex items-start gap-2.5 text-white/70 text-sm">
                    <Check class="w-4 h-4 text-[#30998A] flex-shrink-0 mt-0.5" />
                    Inclut un abonnement Messagerie Pro (voir tarifs ci-dessus)
                </li>
            </ul>
        </div>
    </section>

    <section class="relative overflow-hidden py-20" style="background: #061514;">
        <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-white mb-4 font-display">Prêt à passer le cap ?</h2>
            <p class="text-white/70 mb-8">Dites-nous combien de boîtes mail vous souhaitez migrer, on s'occupe du reste.</p>
            <a
                :href="wa('Bonjour, je souhaite démarrer avec le Pack Démarrage Messagerie.')"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 focus-ring"
            >
                <MessageCircle class="w-5 h-5" />
                Discuter sur WhatsApp
            </a>
        </div>
    </section>
</template>
