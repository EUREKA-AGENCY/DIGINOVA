<script setup>
import { ref, computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Loader2, MessageCircle, CheckCircle2, ArrowLeft, ArrowRight as ArrowRightIcon } from 'lucide-vue-next'

const props = defineProps({
    submitted: { type: Object, default: null },
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

const STEPS = [
    { title: 'Vos coordonnées' },
    { title: 'Votre messagerie' },
    { title: 'Vos SMS' },
    { title: 'Votre besoin' },
]
const currentStep = ref(1)
const totalSteps = STEPS.length

const canGoNext = computed(() => {
    if (currentStep.value === 1) return form.name.trim() !== '' && form.email.trim() !== ''
    if (currentStep.value === 2) return form.mail_situation !== ''
    if (currentStep.value === 3) return form.sms_need !== ''
    return true
})

function next() {
    if (currentStep.value < totalSteps) currentStep.value++
}
function back() {
    if (currentStep.value > 1) currentStep.value--
}

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

                <!-- ── Confirmation (après soumission) ── -->
                <div v-if="submitted">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full bg-[#30998A]/10 flex-shrink-0">
                            <CheckCircle2 class="w-5 h-5 text-[#1D5457]" />
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 font-display">Merci, {{ submitted.name }} !</h1>
                            <p class="text-xs text-slate-500">Votre demande a bien été enregistrée.</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-5 mb-5">
                        <p class="text-sm text-slate-700 leading-relaxed">
                            Notre IA analyse vos réponses et vous envoie votre diagnostic personnalisé par email à
                            <strong class="text-slate-900">{{ submitted.email }}</strong> dans quelques minutes.
                        </p>
                    </div>

                    <a
                        :href="wa(`Bonjour, je viens de faire le diagnostic en ligne (${submitted.name}), je souhaite en discuter.`)"
                        target="_blank"
                        rel="noopener"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] text-[#0A2422] font-semibold py-3.5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm"
                    >
                        <MessageCircle class="w-4 h-4" />
                        Continuer sur WhatsApp en attendant
                    </a>
                </div>

                <!-- ── Formulaire multi-étapes ── -->
                <template v-else>
                    <div class="mb-6 text-center">
                        <h1 class="text-2xl font-bold text-slate-900 font-display">Diagnostic numérique gratuit</h1>
                        <p class="mt-2 text-sm text-slate-500">
                            Quelques questions sur votre besoin — vous recevez une recommandation personnalisée par email.
                        </p>
                    </div>

                    <!-- Indicateur d'étapes -->
                    <div class="flex items-center gap-2 mb-6">
                        <div
                            v-for="(step, i) in STEPS"
                            :key="i"
                            class="flex-1 h-1.5 rounded-full transition-colors duration-300"
                            :class="i + 1 <= currentStep ? 'bg-[#30998A]' : 'bg-slate-100'"
                        ></div>
                    </div>
                    <p class="text-xs font-medium uppercase tracking-wide text-[#1D5457] mb-5">
                        Étape {{ currentStep }}/{{ totalSteps }} — {{ STEPS[currentStep - 1].title }}
                    </p>

                    <form @submit.prevent="submit" class="space-y-4">

                        <!-- Étape 1 : coordonnées -->
                        <div v-show="currentStep === 1" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Nom complet</label>
                                    <input v-model="form.name" type="text" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Email</label>
                                    <input v-model="form.email" type="email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
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
                        </div>

                        <!-- Étape 2 : messagerie -->
                        <div v-show="currentStep === 2" class="space-y-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Votre situation email actuelle</label>
                                <select v-model="form.mail_situation" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
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
                        </div>

                        <!-- Étape 3 : SMS -->
                        <div v-show="currentStep === 3" class="space-y-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Besoin en SMS</label>
                                <select v-model="form.sms_need" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
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
                        </div>

                        <!-- Étape 4 : besoin principal -->
                        <div v-show="currentStep === 4" class="space-y-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Votre besoin principal</label>
                                <textarea v-model="form.main_need" rows="3" placeholder="Décrivez ce qui vous amène à chercher une solution..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40 resize-none"></textarea>
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
                        </div>

                        <!-- Navigation -->
                        <div class="flex items-center gap-3 pt-2">
                            <button
                                v-if="currentStep > 1"
                                type="button"
                                @click="back"
                                class="inline-flex items-center gap-1.5 px-4 py-3.5 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-all duration-200 cursor-pointer"
                            >
                                <ArrowLeft class="w-4 h-4" />
                                Précédent
                            </button>

                            <button
                                v-if="currentStep < totalSteps"
                                type="button"
                                @click="next"
                                :disabled="!canGoNext"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed text-[#0A2422] font-semibold py-3.5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm"
                            >
                                Suivant
                                <ArrowRightIcon class="w-4 h-4" />
                            </button>

                            <button
                                v-else
                                type="submit"
                                :disabled="form.processing"
                                class="flex-1 inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed text-[#0A2422] font-semibold py-3.5 rounded-xl transition-all duration-200 cursor-pointer shadow-lg shadow-[#30998A]/20 text-sm"
                            >
                                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                                {{ form.processing ? 'Envoi...' : 'Obtenir mon diagnostic' }}
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>
