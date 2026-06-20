<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'

defineOptions({ layout: AppLayoutPublic })
</script>

<template>
    <Head>
        <title>Mentions légales | Diginova</title>
        <meta name="description" content="Mentions légales du site Diginova." />
    </Head>

    <section class="pt-32 pb-24" style="background: #0A2422;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-10" style="font-family:'Poppins',sans-serif;">
                Mentions légales
            </h1>

            <div class="space-y-8 text-white/70 text-sm leading-relaxed">
                <div>
                    <h2 class="text-white font-bold text-lg mb-2" style="font-family:'Poppins',sans-serif;">Éditeur du site</h2>
                    <p>
                        Le site diginova.cm est édité par Diginova, Yaoundé, Cameroun.<br>
                        Forme juridique : <em>à compléter</em><br>
                        Numéro d'immatriculation (RCCM) : <em>à compléter</em><br>
                        Contact : <a href="mailto:contact@diginova.cm" class="text-[#30998A] hover:underline">contact@diginova.cm</a> · +237 655 065 494
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2" style="font-family:'Poppins',sans-serif;">Hébergement</h2>
                    <p>Le site est hébergé sur un serveur dédié. Coordonnées de l'hébergeur disponibles sur demande à l'adresse ci-dessus.</p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2" style="font-family:'Poppins',sans-serif;">Propriété intellectuelle</h2>
                    <p>
                        L'ensemble des contenus présents sur ce site (textes, logos, visuels) est la propriété de Diginova,
                        sauf mention contraire. Toute reproduction sans autorisation préalable est interdite.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2" style="font-family:'Poppins',sans-serif;">Responsabilité</h2>
                    <p>
                        Diginova s'efforce d'assurer l'exactitude des informations publiées sur ce site, sans garantie d'exhaustivité.
                        Diginova ne pourra être tenu responsable des dommages directs ou indirects résultant de l'accès au site.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-lg mb-2" style="font-family:'Poppins',sans-serif;">Contact</h2>
                    <p>Pour toute question relative à ces mentions légales, contactez-nous à <a href="mailto:contact@diginova.cm" class="text-[#30998A] hover:underline">contact@diginova.cm</a>.</p>
                </div>
            </div>
        </div>
    </section>
</template>
