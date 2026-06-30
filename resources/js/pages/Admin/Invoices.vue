<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface InvoiceItem {
    id: number;
    invoice_number: string;
    client_name: string;
    client_email: string;
    item_name: string;
    years: number;
    total_amount: number;
    status: 'pending' | 'paid' | 'cancelled';
    created_at: string;
}

interface Props {
    invoices: InvoiceItem[];
    status?: string | null;
}

defineProps<Props>();

function fmt(n: number) {
    return new Intl.NumberFormat('fr-FR').format(n);
}

function markPaid(invoice: InvoiceItem) {
    if (!confirm(`Marquer la facture ${invoice.invoice_number} comme payée ?`)) return;
    useForm({}).post(route('admin.invoices.mark-paid', invoice.id), { preserveScroll: true });
}

function markCancelled(invoice: InvoiceItem) {
    if (!confirm(`Annuler la facture ${invoice.invoice_number} ?`)) return;
    useForm({}).post(route('admin.invoices.mark-cancelled', invoice.id), { preserveScroll: true });
}

function resend(invoice: InvoiceItem) {
    useForm({}).post(route('admin.invoices.resend', invoice.id), { preserveScroll: true });
}

const STATUS_LABELS: Record<string, string> = { pending: 'En attente', paid: 'Payée', cancelled: 'Annulée' };
const STATUS_STYLES: Record<string, string> = {
    pending: 'bg-amber-50 text-amber-700',
    paid: 'bg-[#30998A]/10 text-[#1D5457]',
    cancelled: 'bg-red-50 text-red-600',
};

const showCreateForm = ref(false);
const createForm = useForm({
    client_name: '',
    client_email: '',
    client_phone: '',
    client_company: '',
    item_name: '',
    total_amount: '',
});

function submitCreate() {
    createForm.post(route('admin.invoices.store-manual'), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateForm.value = false;
            createForm.reset();
        },
    });
}
</script>

<template>
    <AppLayoutClient title="Factures">
        <Head title="Factures — Console admin" />

        <div class="px-4 py-8 sm:px-8">
            <div
                class="mb-8 flex flex-col justify-between gap-4 rounded-2xl px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
            >
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">ADMINISTRATION</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Factures</h1>
                    <p class="mt-1 text-sm text-white/70">Suivi des commandes et facturation manuelle.</p>
                </div>
                <div class="flex gap-3">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl bg-[#30998A] hover:bg-[#257A6E] px-5 py-3 text-sm font-semibold text-[#0A2422] transition-colors duration-200 cursor-pointer whitespace-nowrap"
                        @click="showCreateForm = !showCreateForm"
                    >
                        + Nouvelle facture
                    </button>
                    <Link
                        href="/admin"
                        class="inline-flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer whitespace-nowrap"
                    >
                        ← Console admin
                    </Link>
                </div>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <!-- Formulaire création manuelle -->
            <div v-if="showCreateForm" class="mb-8 rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-900 mb-5">Créer une facture manuellement</h2>
                <form @submit.prevent="submitCreate" class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nom du client *</label>
                        <input
                            v-model="createForm.client_name"
                            type="text"
                            required
                            placeholder="Rodrigue Hervé Bayanak"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                        <p v-if="createForm.errors.client_name" class="mt-1 text-xs text-red-600">{{ createForm.errors.client_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Email *</label>
                        <input
                            v-model="createForm.client_email"
                            type="email"
                            required
                            placeholder="client@entreprise.cm"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                        <p v-if="createForm.errors.client_email" class="mt-1 text-xs text-red-600">{{ createForm.errors.client_email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                        <input
                            v-model="createForm.client_phone"
                            type="text"
                            placeholder="+237 6XX XXX XXX"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Entreprise</label>
                        <input
                            v-model="createForm.client_company"
                            type="text"
                            placeholder="BH CONNECT"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Libellé de la facture *</label>
                        <input
                            v-model="createForm.item_name"
                            type="text"
                            required
                            placeholder="VPS Basic Unmanaged + Renouvellement domaine bhconnect.cm (1 an)"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                        <p v-if="createForm.errors.item_name" class="mt-1 text-xs text-red-600">{{ createForm.errors.item_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Montant total (F CFA) *</label>
                        <input
                            v-model="createForm.total_amount"
                            type="number"
                            required
                            min="1000"
                            step="500"
                            placeholder="72500"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#30998A] focus:outline-none focus:ring-2 focus:ring-[#30998A]/20"
                        />
                        <p v-if="createForm.errors.total_amount" class="mt-1 text-xs text-red-600">{{ createForm.errors.total_amount }}</p>
                    </div>
                    <div class="sm:col-span-2 flex gap-3 pt-2">
                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="inline-flex items-center rounded-xl bg-[#1D5457] hover:bg-[#164042] disabled:opacity-60 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer"
                        >
                            {{ createForm.processing ? 'Création...' : 'Créer & envoyer la facture' }}
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center rounded-xl border border-slate-200 hover:bg-slate-50 px-5 py-2.5 text-sm font-semibold text-slate-600 transition-colors duration-200 cursor-pointer"
                            @click="showCreateForm = false; createForm.reset()"
                        >
                            Annuler
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Factures ({{ invoices.length }})</h2>
                </div>

                <div v-if="!invoices.length" class="px-6 py-8 text-center text-sm text-slate-500">Aucune facture pour le moment.</div>

                <div v-for="invoice in invoices" :key="invoice.id" class="px-6 py-5 border-b border-slate-50 last:border-0">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ invoice.invoice_number }} — {{ invoice.item_name }}
                                <span class="text-slate-400 font-normal">({{ invoice.years }} an{{ invoice.years > 1 ? 's' : '' }})</span>
                            </p>
                            <p class="text-xs text-slate-500">{{ invoice.client_name }} · {{ invoice.client_email }} · {{ invoice.created_at }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-slate-900">{{ fmt(invoice.total_amount) }} F</span>
                            <span :class="['text-[10px] font-semibold uppercase tracking-wide rounded-full px-2 py-0.5', STATUS_STYLES[invoice.status]]">
                                {{ STATUS_LABELS[invoice.status] }}
                            </span>

                            <a
                                :href="route('admin.invoices.download', invoice.id)"
                                target="_blank"
                                class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer"
                            >
                                Télécharger
                            </a>
                            <button type="button" class="text-xs font-semibold text-slate-500 hover:underline cursor-pointer" @click="resend(invoice)">
                                Renvoyer
                            </button>
                            <button
                                v-if="invoice.status === 'pending'"
                                type="button"
                                class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer"
                                @click="markPaid(invoice)"
                            >
                                Marquer payée
                            </button>
                            <button
                                v-if="invoice.status === 'pending'"
                                type="button"
                                class="text-xs font-semibold text-red-600 hover:underline cursor-pointer"
                                @click="markCancelled(invoice)"
                            >
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutClient>
</template>
