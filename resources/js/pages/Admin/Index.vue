<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import InputError from '@/components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface ClientItem {
    id: number;
    name: string;
    email: string;
    verified: boolean;
    mail_domains: string[];
    sms_balance: number | null;
}

interface DomainItem {
    id: number;
    name: string;
    owner_user_id: number | null;
}

interface Props {
    clients: ClientItem[];
    domains: DomainItem[];
    status?: string | null;
    tempPassword?: string | null;
}

const props = defineProps<Props>();

const createForm = useForm({ name: '', email: '' });
function createClient() {
    createForm.post(route('admin.clients.store'), {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

const domainForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function domainForm(clientId: number) {
    if (!domainForms.value[clientId]) {
        domainForms.value[clientId] = useForm({ domain_id: '' });
    }
    return domainForms.value[clientId];
}
function assignDomain(clientId: number) {
    domainForm(clientId).post(route('admin.clients.assign-domain', clientId), {
        preserveScroll: true,
        onSuccess: () => (domainForm(clientId).domain_id = ''),
    });
}
function unassignDomain(domainId: number) {
    if (!confirm('Retirer ce domaine du client ?')) return;
    useForm({}).delete(route('admin.mail-domains.unassign', domainId), { preserveScroll: true });
}

const creditForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function creditForm(clientId: number) {
    if (!creditForms.value[clientId]) {
        creditForms.value[clientId] = useForm({ amount: 100 });
    }
    return creditForms.value[clientId];
}
function adjustCredits(clientId: number) {
    creditForm(clientId).post(route('admin.clients.sms-credits', clientId), { preserveScroll: true });
}

function unassignedDomains() {
    return props.domains.filter((d) => !d.owner_user_id);
}
</script>

<template>
    <AppLayoutClient title="Console admin">
        <Head title="Console admin — Diginova" />

        <div class="px-4 py-8 sm:px-8">
            <div
                class="mb-8 rounded-2xl px-6 py-6 text-white shadow-md sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
            >
                <p class="text-xs font-medium tracking-[0.25em] text-white/60">ADMINISTRATION</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight">Console admin</h1>
                <p class="mt-1 text-sm text-white/70">Créez des comptes clients et gérez leurs domaines mail et crédits SMS.</p>
            </div>

            <p v-if="status" class="mb-4 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>
            <p v-if="tempPassword" class="mb-6 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                Mot de passe temporaire (à transmettre au client, affiché une seule fois) :
                <code class="font-mono font-semibold">{{ tempPassword }}</code>
            </p>

            <!-- Créer un client -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8 mb-8">
                <h2 class="text-sm font-semibold text-slate-900 mb-4">Nouveau client</h2>
                <form @submit.prevent="createClient" class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto]">
                    <div>
                        <input
                            v-model="createForm.name"
                            type="text"
                            placeholder="Nom"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <InputError :message="createForm.errors.name" />
                    </div>
                    <div>
                        <input
                            v-model="createForm.email"
                            type="email"
                            placeholder="email@client.com"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <InputError :message="createForm.errors.email" />
                    </div>
                    <button
                        type="submit"
                        :disabled="createForm.processing"
                        class="rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer"
                    >
                        Créer le client
                    </button>
                </form>
            </div>

            <!-- Liste des clients -->
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Clients ({{ clients.length }})</h2>
                </div>

                <div v-if="!clients.length" class="px-6 py-8 text-center text-sm text-slate-500">Aucun client pour le moment.</div>

                <div v-for="client in clients" :key="client.id" class="px-6 py-5 border-b border-slate-50 last:border-0">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ client.name }}</p>
                            <p class="text-xs text-slate-500">{{ client.email }}</p>
                        </div>
                        <span
                            :class="[
                                'text-[10px] font-semibold uppercase tracking-wide rounded-full px-2 py-0.5',
                                client.verified ? 'bg-[#30998A]/10 text-[#1D5457]' : 'bg-amber-50 text-amber-700',
                            ]"
                        >
                            {{ client.verified ? 'Email vérifié' : 'Non vérifié' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Domaines mail -->
                        <div class="rounded-xl bg-slate-50/60 border border-slate-100 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Domaines mail</p>
                            <div v-if="client.mail_domains.length" class="flex flex-wrap gap-1.5 mb-3">
                                <span
                                    v-for="d in domains.filter((dom) => dom.owner_user_id === client.id)"
                                    :key="d.id"
                                    class="inline-flex items-center gap-1.5 text-xs bg-white border border-slate-200 rounded-full px-2.5 py-1"
                                >
                                    {{ d.name }}
                                    <button type="button" class="text-red-500 hover:text-red-700 cursor-pointer" @click="unassignDomain(d.id)">×</button>
                                </span>
                            </div>
                            <p v-else class="text-xs text-slate-400 mb-3">Aucun domaine assigné.</p>

                            <form @submit.prevent="assignDomain(client.id)" class="flex items-center gap-2">
                                <select
                                    v-model="domainForm(client.id).domain_id"
                                    class="flex-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:border-[#30998A]/60"
                                >
                                    <option value="" disabled>Assigner un domaine…</option>
                                    <option v-for="d in unassignedDomains()" :key="d.id" :value="d.id">{{ d.name }}</option>
                                </select>
                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#30998A] px-3 py-1.5 text-xs font-semibold text-[#0A2422] hover:bg-[#257A6E] cursor-pointer whitespace-nowrap"
                                >
                                    Assigner
                                </button>
                            </form>
                        </div>

                        <!-- Crédits SMS -->
                        <div class="rounded-xl bg-slate-50/60 border border-slate-100 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500 mb-2">Crédits SMS</p>
                            <p class="text-sm font-semibold text-slate-900 mb-3">
                                {{ client.sms_balance ?? 'Pas de compte SMS' }}
                            </p>
                            <form @submit.prevent="adjustCredits(client.id)" class="flex items-center gap-2">
                                <input
                                    v-model.number="creditForm(client.id).amount"
                                    type="number"
                                    class="w-24 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:border-[#30998A]/60"
                                />
                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#30998A] px-3 py-1.5 text-xs font-semibold text-[#0A2422] hover:bg-[#257A6E] cursor-pointer whitespace-nowrap"
                                >
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayoutClient>
</template>
