<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
                    <p class="mt-1 text-sm text-white/70">Suivi des commandes hébergement et de leur règlement.</p>
                </div>
                <Link
                    href="/admin"
                    class="inline-flex items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 px-5 py-3 text-sm font-semibold text-white transition-colors duration-200 cursor-pointer whitespace-nowrap"
                >
                    ← Console admin
                </Link>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

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
