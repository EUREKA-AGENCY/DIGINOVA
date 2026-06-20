<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'

defineOptions({ layout: AppLayoutPublic })
</script>

<template>
    <Head>
        <title>Politique de confidentialité | Diginova</title>
        <meta name="description" content="Politique de confidentialité et traitement des données personnelles sur diginova.cm." />
    </Head>

    <section class="pt-32 pb-24" style="background: #0A2422;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-10 font-display">
                Politique de confidentialité
            </h1>

            <div class="space-y-8 text-white/70 text-sm leading-relaxed">
                <div>
                    <h2 class="text-white font-bold text-lg mb-2 font-display">Données collectées</h2>
                    <p>
                        Lorsque vous remplissez le formulaire de contact sur ce site, nous collectons votre nom, votre adresse email,
                        votre numéro de téléphone (si renseigné) et les informations concernant votre projet. Ces données sont
                        utilisées exclusivement pour répondre à votre demande.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2 font-display">Utilisation des données</h2>
                    <p>
                        Les informations transmises via le formulaire de contact ne sont ni vendues ni cédées à des tiers.
                        Elles sont conservées le temps nécessaire au traitement de votre demande commerciale.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2 font-display">Cookies</h2>
                    <p>
                        Ce site n'utilise pas de cookies de suivi publicitaire ni d'outils d'analyse tiers nécessitant un consentement.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2 font-display">Vos droits</h2>
                    <p>
                        Vous pouvez demander l'accès, la rectification ou la suppression de vos données personnelles à tout moment
                        en écrivant à <a href="mailto:contact@diginova.cm" class="text-[#30998A] hover:underline">contact@diginova.cm</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
