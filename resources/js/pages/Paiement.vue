<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import { Building2, Smartphone, Copy, Check, MessageCircle } from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

const PAYMENT_METHODS = [
    { label: 'Virement bancaire AFB', value: '10005 000040 06924451051 04', icon: Building2 },
    { label: 'Orange Money', value: '655065494', icon: Smartphone },
    { label: 'MTN MoMo', value: '682267026', icon: Smartphone },
]

const SERVICES = [
    { value: 'sms', label: 'Recharge crédits SMS' },
    { value: 'messagerie', label: 'Abonnement Messagerie Pro' },
    { value: 'hebergement', label: 'Hébergement / Serveur' },
    { value: 'devis', label: 'Devis ou projet en cours' },
]

const params = new URLSearchParams(window.location.search)

const service = ref(params.get('service') ?? '')
const amount = ref(params.get('amount') ?? '')
const reference = ref(params.get('reference') ?? '')
const copiedIndex = ref(null)

function copy(value, index) {
    navigator.clipboard.writeText(value.replace(/\s/g, ''))
    copiedIndex.value = index
    setTimeout(() => {
        if (copiedIndex.value === index) copiedIndex.value = null
    }, 1500)
}

const serviceLabel = computed(() => SERVICES.find((s) => s.value === service.value)?.label ?? '')

const whatsappHref = computed(() => {
    const parts = ["Bonjour, je viens d'effectuer un paiement"]
    if (serviceLabel.value) parts.push(`pour : ${serviceLabel.value}`)
    if (amount.value) parts.push(`d'un montant de ${amount.value} F CFA`)
    if (reference.value) parts.push(`(référence : ${reference.value})`)
    parts.push('. Je vous envoie la preuve de paiement en pièce jointe.')

    const message = parts.join(' ')
    return `https://wa.me/237655065494?text=${encodeURIComponent(message)}`
})
</script>

<template>
    <Head>
        <title>Paiement de vos services | Diginova</title>
        <meta name="description" content="Modalités de paiement Diginova : virement bancaire, Orange Money, MTN MoMo — pour vos crédits SMS, abonnement messagerie, hébergement ou projet." />
    </Head>

    <section class="pt-32 pb-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-3">Paiement</span>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 font-display">
                    Réglez vos services Diginova
                </h1>
                <p class="mt-4 text-slate-500 text-sm leading-relaxed max-w-xl mx-auto">
                    Crédits SMS, abonnement messagerie, hébergement ou projet en cours — choisissez ce que vous payez,
                    réglez par virement ou Mobile Money, puis confirmez-nous le paiement.
                </p>
            </div>

            <!-- Étape 1 : ce que vous payez -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm mb-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">1. Que souhaitez-vous payer ?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Service</label>
                        <select v-model="service" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40">
                            <option value="" disabled>Choisir...</option>
                            <option v-for="s in SERVICES" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Montant (F CFA)</label>
                        <input v-model="amount" type="number" min="0" placeholder="Ex. 50 000" class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Référence (optionnel)</label>
                        <input v-model="reference" type="text" placeholder="Ex. numéro de devis, nom de domaine, compte client..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                    </div>
                </div>
            </div>

            <!-- Étape 2 : moyens de paiement -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 sm:p-8 shadow-sm mb-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">2. Modalités de paiement</h2>

                <div class="rounded-xl overflow-hidden border border-slate-100 mb-4">
                    <div
                        v-for="(method, i) in PAYMENT_METHODS"
                        :key="method.label"
                        class="flex items-center justify-between gap-3 px-4 py-3.5"
                        :class="i % 2 === 0 ? 'bg-[#0D2B29]' : 'bg-[#0A2422]'"
                    >
                        <div class="flex items-center gap-2.5 text-white text-sm font-semibold">
                            <component :is="method.icon" class="w-4 h-4 text-[#30998A] flex-shrink-0" />
                            {{ method.label }}
                        </div>
                        <button
                            type="button"
                            @click="copy(method.value, i)"
                            class="flex items-center gap-2 text-white font-bold text-sm tracking-wide cursor-pointer hover:text-[#30998A] transition-colors duration-200 focus-ring rounded"
                        >
                            {{ method.value }}
                            <Check v-if="copiedIndex === i" class="w-3.5 h-3.5 text-[#30998A]" />
                            <Copy v-else class="w-3.5 h-3.5 text-white/40" />
                        </button>
                    </div>
                </div>

                <p class="text-xs text-slate-500">
                    Paiements à effectuer au nom de <strong class="text-slate-700">PENLAP KAMDEM SATURIN</strong>.
                    Merci de vérifier ce nom à l'écran de confirmation avant de valider votre transaction.
                </p>
            </div>

            <!-- Étape 3 : confirmation -->
            <div class="rounded-2xl bg-gradient-to-br from-[#30998A]/5 to-[#1D5457]/5 border border-[#30998A]/20 p-6 sm:p-8 text-center">
                <h2 class="text-sm font-semibold text-slate-900 mb-2">3. Confirmez votre paiement</h2>
                <p class="text-sm text-slate-500 mb-5">
                    Une fois le paiement effectué, envoyez-nous la preuve (capture d'écran) par WhatsApp — nous activons votre service dans les plus brefs délais.
                </p>
                <a
                    :href="whatsappHref"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-2.5 bg-[#30998A] hover:bg-[#257A6E] active:scale-95 text-[#0A2422] font-bold px-8 py-4 rounded-xl transition-all duration-200 cursor-pointer shadow-xl shadow-[#30998A]/30 justify-center focus-ring"
                >
                    <MessageCircle class="w-5 h-5" />
                    J'ai payé, envoyer la preuve sur WhatsApp
                </a>
            </div>
        </div>
    </section>
</template>
