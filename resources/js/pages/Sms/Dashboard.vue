<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link } from '@inertiajs/vue3';

interface RecentMessage {
    id: number;
    date: string;
    sender: string;
    destination: string;
    message: string;
    status: 'sent' | 'failed';
}

interface Props {
    account: { balance: number; sender_name: string | null } | null;
    stats: { total_sent: number; delivery_rate: number };
    recent: RecentMessage[];
}

defineProps<Props>();
</script>

<template>
    <AppLayoutClient title="SMS Pro — Tableau de bord">
        <Head title="SMS Pro — Espace client" />

        <div class="px-4 py-8 sm:px-8">
            <div
                class="mb-8 flex flex-col justify-between gap-4 rounded-2xl px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
            >
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">ESPACE CLIENT</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">SMS Pro</h1>
                    <p class="mt-1 text-sm text-white/70">
                        Envoyez des SMS, suivez vos crédits et l'historique de vos campagnes.
                    </p>
                </div>
                <Link
                    href="/sms-pro/send"
                    class="inline-flex items-center justify-center rounded-xl bg-[#30998A] hover:bg-[#257A6E] px-5 py-3 text-sm font-semibold text-[#0A2422] transition-colors duration-200 cursor-pointer whitespace-nowrap"
                >
                    Envoyer un SMS
                </Link>
            </div>

            <div v-if="!account" class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <p class="text-slate-600 text-sm">
                    Votre compte SMS Pro n'est pas encore activé. Contactez Diginova pour démarrer.
                </p>
            </div>

            <template v-else>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Votre solde</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 font-display">
                            {{ account.balance }}
                            <span class="text-sm font-medium text-slate-400">SMS</span>
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Total envoyés</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 font-display">
                            {{ stats.total_sent }}
                            <span class="text-sm font-medium text-slate-400">SMS</span>
                        </p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Taux de délivrabilité</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900 font-display">
                            {{ stats.delivery_rate }}<span class="text-sm font-medium text-slate-400">%</span>
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-slate-900">Vos derniers envois</h2>
                        <Link href="/sms-pro/history" class="text-xs font-semibold text-[#1D5457] hover:text-[#30998A]">
                            Voir tout l'historique
                        </Link>
                    </div>
                    <div v-if="!recent.length" class="px-6 py-8 text-center text-sm text-slate-500">
                        Aucun envoi pour le moment.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400 border-b border-slate-100">
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Expéditeur</th>
                                    <th class="px-6 py-3">Destinataire</th>
                                    <th class="px-6 py-3">Message</th>
                                    <th class="px-6 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="m in recent" :key="m.id" class="border-b border-slate-50 last:border-0">
                                    <td class="px-6 py-3 text-slate-500 whitespace-nowrap">{{ m.date }}</td>
                                    <td class="px-6 py-3 text-slate-900 font-medium">{{ m.sender }}</td>
                                    <td class="px-6 py-3 text-slate-600 whitespace-nowrap">{{ m.destination }}</td>
                                    <td class="px-6 py-3 text-slate-500 max-w-xs truncate">{{ m.message }}</td>
                                    <td class="px-6 py-3">
                                        <span
                                            :class="[
                                                'text-[10px] font-semibold uppercase tracking-wide rounded-full px-2 py-0.5',
                                                m.status === 'sent' ? 'bg-[#30998A]/10 text-[#1D5457]' : 'bg-red-50 text-red-600',
                                            ]"
                                        >
                                            {{ m.status === 'sent' ? 'Envoyé' : 'Échec' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>
    </AppLayoutClient>
</template>
