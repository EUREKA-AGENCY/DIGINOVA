<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Loader2, MessageCircle, CheckCircle2 } from 'lucide-vue-next'

const props = defineProps({
    result: { type: Object, default: null },
})

const form = useForm({
    name: '',
    email: '',
    phone: '',
    company: '',
    mail_situation: '',
    mail_boxes_needed: '',
    sms_need: '',
    sms_volume_monthly: '',
    main_need: '',
    budget_range: '',
})

function submit() {
    form.post(route('diagnostic.store'))
}

function wa(message) {
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
}
</script>

<template>
    <Head title="Diagnostic numérique gratuit — Diginova" />

    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-0">
            <img src="/images/site/hero-dev.webp" alt="" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(6,21,20,0.96) 0%, rgba(10,36,34,0.93) 55%, rgba(13,43,41,0.9) 100%);"></div>
        </div>

        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative w-full max-w-xl">
            <Link href="/" class="flex items-center justify-center gap-3 mb-8 cursor-pointer">
                <img src="/logo-icon.png" alt="Diginova" width="32" height="32" class="h-8 w-auto" />
                <span class="text-white font-bold text-lg tracking-tight font-display">Diginova</span>
            </Link>

            <div class="rounded-2xl bg-white shadow-2xl p-6 sm:p-8">

                <!-- ── Résultat (après soumission) ── -->
                <div v-if="result">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full bg-[#30998A]/10 flex-shrink-0">
                            <CheckCircle2 class="w-5 h-5 text-[#1D5457]" />
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 font-display">Merci, {{ result.name }} !</h1>
                            <p class="text-xs text-slate-500">Voici une première analyse de votre besoin.</p>
                        </div>
                    </div>

                    <div v-if="result.analysis" class="rounded-xl bg-slate-50 border border-slate-100 p-5 mb-5">
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ result.analysis }}</p>
                    </div>
                    <div v-else class="rounded-xl bg-amber-50 border border-amber-200 p-5 mb-5">
                        <p class="text-sm text-amber-800">
                            Notre analyse automatique n'a pas pu se terminer, mais votre demande est bien enregistrée — notre équipe vous recontacte rapidement.
                        </p>
                    </div>

                    <p class="text-xs text-slate-400 mb-5">
                        Cette estimation est indicative et sera confirmée par notre équipe avant toute mise en place.
                    </p>

                    <a
                        :href="wa(`Bonjour, je viens de faire le diagnostic en ligne (${result.name}), je souhaite en discuter.`)"
                        target="_blank"
                        rel="noopener"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] text-[#0A2422] font-semibold py-3.5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm"
                    >
                        <MessageCircle class="w-4 h-4" />
                        Continuer sur WhatsApp
                    </a>
                </div>

                <!-- ── Formulaire ── -->
                <template v-else>
                    <div class="mb-6 text-center">
                        <h1 class="text-2xl font-bold text-slate-900 font-display">Diagnostic numérique gratuit</h1>
                        <p class="mt-2 text-sm text-slate-500">
                            Quelques questions sur votre besoin (messagerie et/ou SMS pro) — vous recevez une première recommandation immédiatement.
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Nom complet</label>
                                <input v-model="form.name" type="text" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Email</label>
                                <input v-model="form.email" type="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Téléphone</label>
                                <input v-model="form.phone" type="text" placeholder="+237..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Entreprise</label>
                                <input v-model="form.company" type="text" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Votre situation email actuelle</label>
                            <select v-model="form.mail_situation" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
                                <option value="" disabled>Choisir...</option>
                                <option value="none">Aucune adresse email professionnelle</option>
                                <option value="personal_email">J'utilise une adresse personnelle (Gmail, Yahoo...)</option>
                                <option value="own_domain_unmanaged">J'ai un nom de domaine, mais pas de messagerie pro</option>
                            </select>
                            <p v-if="form.errors.mail_situation" class="mt-1 text-xs text-red-600">{{ form.errors.mail_situation }}</p>
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Nombre de boîtes mail souhaitées (optionnel)</label>
                            <input v-model="form.mail_boxes_needed" type="number" min="1" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Besoin en SMS</label>
                            <select v-model="form.sms_need" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
                                <option value="" disabled>Choisir...</option>
                                <option value="none">Pas de besoin SMS</option>
                                <option value="transactional">SMS transactionnels (confirmations, OTP, alertes)</option>
                                <option value="marketing">Campagnes SMS marketing</option>
                                <option value="both">Les deux</option>
                            </select>
                            <p v-if="form.errors.sms_need" class="mt-1 text-xs text-red-600">{{ form.errors.sms_need }}</p>
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Volume SMS estimé / mois (optionnel)</label>
                            <input v-model="form.sms_volume_monthly" type="number" min="1" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Votre besoin principal</label>
                            <textarea v-model="form.main_need" required rows="3" placeholder="Décrivez ce qui vous amène à chercher une solution..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40 resize-none"></textarea>
                            <p v-if="form.errors.main_need" class="mt-1 text-xs text-red-600">{{ form.errors.main_need }}</p>
                        </div>

                        <div>
                            <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Budget mensuel approximatif (optionnel)</label>
                            <select v-model="form.budget_range" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
                                <option value="">Préfère ne pas préciser</option>
                                <option value="< 10 000 F">Moins de 10 000 F</option>
                                <option value="10 000 - 50 000 F">10 000 - 50 000 F</option>
                                <option value="50 000 - 200 000 F">50 000 - 200 000 F</option>
                                <option value="> 200 000 F">Plus de 200 000 F</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed text-[#0A2422] font-semibold py-3.5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm"
                        >
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            {{ form.processing ? 'Analyse en cours… (jusqu\'à quelques minutes)' : 'Obtenir mon diagnostic' }}
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>
