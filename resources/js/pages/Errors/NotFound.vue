<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import { ArrowRight, MessageCircle } from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })
</script>

<template>
    <Head>
        <title>Page introuvable | Diginova</title>
        <meta name="robots" content="noindex, follow" />
    </Head>

    <section class="relative min-h-dvh flex flex-col items-center justify-center text-center px-4" style="background: #061514;">
        <p class="text-[#30998A] text-sm font-semibold uppercase tracking-widest mb-4">Erreur 404</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5" style="font-family:'Poppins',sans-serif;">
            Cette page n'existe pas
        </h1>
        <p class="text-white/55 text-base max-w-md mx-auto mb-10">
            L'adresse demandée a peut-être changé ou n'a jamais existé. Revenez à l'accueil ou contactez-nous directement.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <Link
                href="/"
                class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 justify-center"
            >
                Retour à l'accueil
                <ArrowRight class="w-4 h-4" />
            </Link>
            <a
                href="https://wa.me/237655065494?text=Bonjour%2C%20j%27ai%20atterri%20sur%20une%20page%20introuvable%20du%20site%20Diginova."
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2.5 border border-white/30 hover:border-[#30998A]/50 text-white hover:text-[#30998A] font-semibold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer justify-center backdrop-blur-sm bg-white/5"
            >
                <MessageCircle class="w-4 h-4" />
                Nous écrire
            </a>
        </div>
    </section>
</template>
