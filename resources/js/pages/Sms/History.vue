<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface HistoryRow {
    id: number;
    date: string;
    sender: string;
    destination: string;
    message: string;
    segments: number;
    status: 'sent' | 'failed';
}

interface PaginatedMessages {
    data: HistoryRow[];
    links: { url: string | null; label: string; active: boolean }[];
}

interface Props {
    messages: PaginatedMessages;
    filters: { from?: string; to?: string; phone?: string };
    total: number;
}

const props = defineProps<Props>();

const form = useForm({
    from: props.filters.from || '',
    to: props.filters.to || '',
    phone: props.filters.phone || '',
});

function search() {
    form.get(route('sms-pro.history'), { preserveState: true });
}
</script>

<template>
    <AppLayoutClient title="SMS Pro — Historique">
        <Head title="Historique SMS — Espace client" />

        <div class="px-4 py-8 sm:px-8">
            <div class="mb-6">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-2">SMS Pro</span>
                <h1 class="text-2xl font-bold text-slate-900 font-display">Historique et rapports</h1>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <form @submit.prevent="search" class="flex flex-wrap items-end gap-3 px-6 py-5 border-b border-slate-100">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Du</label>
                        <input
                            v-model="form.from"
                            type="date"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Au</label>
                        <input
                            v-model="form.to"
                            type="date"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                    </div>
                    <div class="flex-1 min-w-[180px]">
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Numéro de téléphone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="237674937152"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                    </div>
                    <button
                        type="submit"
                        class="rounded-lg bg-[#30998A] hover:bg-[#257A6E] px-4 py-2 text-sm font-semibold text-[#0A2422] transition-colors cursor-pointer"
                    >
                        Recherche
                    </button>
                </form>

                <div v-if="!messages.data.length" class="px-6 py-10 text-center text-sm text-slate-500">
                    Aucun envoi ne correspond à ces critères.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Expéditeur</th>
                                <th class="px-6 py-3">Destinataire</th>
                                <th class="px-6 py-3">Message</th>
                                <th class="px-6 py-3">Nbr SMS</th>
                                <th class="px-6 py-3">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="m in messages.data" :key="m.id" class="border-b border-slate-50 last:border-0">
                                <td class="px-6 py-3 text-slate-500 whitespace-nowrap">{{ m.date }}</td>
                                <td class="px-6 py-3 text-slate-900 font-medium">{{ m.sender }}</td>
                                <td class="px-6 py-3 text-slate-600 whitespace-nowrap">{{ m.destination }}</td>
                                <td class="px-6 py-3 text-slate-500 max-w-xs truncate">{{ m.message }}</td>
                                <td class="px-6 py-3 text-slate-500">{{ m.segments }}</td>
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

                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">Total : {{ total }}</p>
                    <div class="flex items-center gap-1">
                        <Link
                            v-for="(link, i) in messages.links"
                            :key="i"
                            :href="link.url || ''"
                            :class="[
                                'min-w-[32px] text-center rounded-lg px-2.5 py-1.5 text-xs font-medium',
                                link.active ? 'bg-[#30998A] text-[#0A2422]' : 'text-slate-500 hover:bg-slate-100',
                                !link.url && 'opacity-40 cursor-not-allowed pointer-events-none',
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutClient>
</template>
