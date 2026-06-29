<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue'
import { Loader2, CheckCircle2, ArrowLeft } from 'lucide-vue-next'

defineOptions({ layout: AppLayoutPublic })

const props = defineProps({
    plan: { type: Object, default: null },
    invoiceSent: { type: Object, default: null },
})

function discountPercentFor(years) {
    if (years >= 3) return 10
    if (years === 2) return 5
    return 0
}

const subtotal = computed(() => (props.plan ? props.plan.unit_price * props.plan.years : 0))
const discountPercent = computed(() => (props.plan ? discountPercentFor(props.plan.years) : 0))
const total = computed(() => Math.round(subtotal.value * (100 - discountPercent.value) / 100))

const form = useForm({
    client_name: '',
    client_email: '',
    client_phone: '',
    client_company: '',
    item_name: props.plan?.item_name ?? '',
    years: props.plan?.years ?? 1,
})

function submit() {
    form.post(route('hebergement.commander.store'))
}

function fmt(n) {
    return new Intl.NumberFormat('fr-FR').format(n)
}
</script>

<template>
    <Head title="Commander un serveur — Diginova" />

    <section class="pt-32 pb-24" style="background: #0A2422;">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <Link href="/hebergement" class="inline-flex items-center gap-1.5 text-white/50 hover:text-[#30998A] text-sm mb-8 cursor-pointer focus-ring rounded">
                <ArrowLeft class="w-3.5 h-3.5" />
                Retour aux offres
            </Link>

            <div v-if="!plan" class="rounded-2xl border border-white/10 bg-white/5 p-8 text-center">
                <p class="text-white/70 text-sm">Offre introuvable. Merci de repartir de la page des offres.</p>
            </div>

            <template v-else>
                <!-- ── Confirmation ── -->
                <div v-if="invoiceSent" class="rounded-2xl bg-white p-6 sm:p-8 shadow-2xl">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full bg-[#30998A]/10 flex-shrink-0">
                            <CheckCircle2 class="w-5 h-5 text-[#1D5457]" />
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 font-display">Facture envoyée !</h1>
                            <p class="text-xs text-slate-500">Référence {{ invoiceSent.number }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        Votre facture de <strong class="text-slate-900">{{ fmt(invoiceSent.total) }} F CFA</strong> a été envoyée à
                        <strong class="text-slate-900">{{ invoiceSent.email }}</strong>, avec les modalités de paiement.
                        Une fois réglée, envoyez-nous la preuve sur WhatsApp pour activer votre serveur.
                    </p>
                    <a
                        :href="`https://wa.me/237655065494?text=${encodeURIComponent(`Bonjour, je viens de commander (facture ${invoiceSent.number}), je souhaite confirmer.`)}`"
                        target="_blank"
                        rel="noopener"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] text-[#0A2422] font-semibold py-3.5 rounded-xl text-sm cursor-pointer"
                    >
                        Continuer sur WhatsApp
                    </a>
                </div>

                <!-- ── Formulaire de commande ── -->
                <div v-else class="rounded-2xl bg-white p-6 sm:p-8 shadow-2xl">
                    <h1 class="text-xl font-bold text-slate-900 font-display mb-1">Commander : {{ plan.item_name }}</h1>
                    <p class="text-sm text-slate-500 mb-6">{{ plan.years }} an{{ plan.years > 1 ? 's' : '' }} d'engagement</p>

                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-4 mb-6 text-sm">
                        <div class="flex justify-between text-slate-600 mb-1">
                            <span>{{ fmt(plan.unit_price) }} F × {{ plan.years }} an{{ plan.years > 1 ? 's' : '' }}</span>
                            <span>{{ fmt(subtotal) }} F</span>
                        </div>
                        <div v-if="discountPercent" class="flex justify-between text-[#1D5457] mb-1">
                            <span>Remise engagement ({{ discountPercent }}%)</span>
                            <span>-{{ fmt(subtotal - total) }} F</span>
                        </div>
                        <div class="flex justify-between text-slate-900 font-bold text-base pt-2 border-t border-slate-200 mt-2">
                            <span>Total</span>
                            <span>{{ fmt(total) }} F CFA</span>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Nom complet</label>
                                <input v-model="form.client_name" type="text" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                                <p v-if="form.errors.client_name" class="mt-1 text-xs text-red-600">{{ form.errors.client_name }}</p>
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Email</label>
                                <input v-model="form.client_email" type="email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                                <p v-if="form.errors.client_email" class="mt-1 text-xs text-red-600">{{ form.errors.client_email }}</p>
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Téléphone</label>
                                <input v-model="form.client_phone" type="text" placeholder="+237..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-medium mb-1.5 uppercase tracking-wide">Entreprise</label>
                                <input v-model="form.client_company" type="text" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40" />
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] disabled:opacity-70 text-[#0A2422] font-semibold py-3.5 rounded-xl text-sm cursor-pointer"
                        >
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            {{ form.processing ? 'Envoi...' : 'Recevoir ma facture par email' }}
                        </button>
                    </form>
                </div>
            </template>
        </div>
    </section>
</template>
