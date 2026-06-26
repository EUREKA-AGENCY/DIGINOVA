<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import InputError from '@/components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Contact {
    id: number;
    name: string;
    phone: string;
}

interface Campaign {
    id: number;
    name: string;
    sender: string;
    message: string;
    recipients_count: number;
    sent_count: number;
    failed_count: number;
    sent_at: string | null;
}

interface Props {
    account: { balance: number; sender_name: string | null } | null;
    contacts: Contact[];
    campaigns: Campaign[];
    status?: string | null;
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    sender: props.account?.sender_name || 'DIGINOVA',
    message: '',
    contact_ids: [] as number[],
});

const segments = computed(() => Math.max(1, Math.ceil(form.message.length / 160)));
const estimatedCost = computed(() => segments.value * form.contact_ids.length);
const allSelected = computed(() => props.contacts.length > 0 && form.contact_ids.length === props.contacts.length);

function toggleAll() {
    form.contact_ids = allSelected.value ? [] : props.contacts.map((c) => c.id);
}

function submit() {
    form.post(route('sms-pro.campaigns.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.name = '';
            form.message = '';
            form.contact_ids = [];
        },
    });
}
</script>

<template>
    <AppLayoutClient title="SMS Pro — Campagnes">
        <Head title="Campagnes SMS — Espace client" />

        <div class="px-4 py-8 sm:px-8 max-w-4xl">
            <div class="mb-6">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-2">SMS Pro</span>
                <h1 class="text-2xl font-bold text-slate-900 font-display">Campagnes</h1>
                <p class="mt-1 text-sm text-slate-500">Envoyez un même message à plusieurs contacts en une fois (50 destinataires max).</p>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <div v-if="!account" class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <p class="text-slate-600 text-sm">
                    Votre compte SMS Pro n'est pas encore activé. Contactez Diginova pour démarrer.
                </p>
            </div>

            <template v-else>
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8 mb-8">
                    <div class="mb-6 flex items-center justify-between text-sm">
                        <span class="text-slate-500">Solde disponible</span>
                        <span class="font-semibold text-slate-900">{{ account.balance }} SMS</span>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Nom de la campagne</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="ex. Promo de juin"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Expéditeur</label>
                                <input
                                    v-model="form.sender"
                                    type="text"
                                    maxlength="11"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                />
                                <InputError :message="form.errors.sender" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Message</label>
                            <textarea
                                v-model="form.message"
                                rows="4"
                                maxlength="480"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                            ></textarea>
                            <p class="mt-1 text-xs text-slate-400">{{ form.message.length }} / 480 caractères · {{ segments }} SMS / destinataire</p>
                            <InputError :message="form.errors.message" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-medium uppercase tracking-wide text-slate-600">Destinataires</label>
                                <button type="button" class="text-xs font-semibold text-[#1D5457] hover:text-[#30998A] cursor-pointer" @click="toggleAll">
                                    {{ allSelected ? 'Tout désélectionner' : 'Tout sélectionner' }}
                                </button>
                            </div>
                            <div v-if="!contacts.length" class="text-xs text-slate-500">
                                Aucun contact enregistré — ajoutez-en depuis la page Contacts.
                            </div>
                            <div v-else class="max-h-56 overflow-y-auto rounded-lg border border-slate-200 divide-y divide-slate-100">
                                <label
                                    v-for="c in contacts"
                                    :key="c.id"
                                    class="flex items-center gap-3 px-3 py-2.5 text-sm cursor-pointer hover:bg-slate-50"
                                >
                                    <input
                                        type="checkbox"
                                        :value="c.id"
                                        v-model="form.contact_ids"
                                        class="rounded border-slate-300 text-[#30998A] focus:ring-[#30998A]/40"
                                    />
                                    <span class="text-slate-900 font-medium">{{ c.name }}</span>
                                    <span class="text-slate-400">{{ c.phone }}</span>
                                </label>
                            </div>
                            <InputError :message="form.errors.contact_ids" />
                        </div>

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-sm">
                            <span class="text-slate-600">{{ form.contact_ids.length }} destinataire(s) sélectionné(s)</span>
                            <span class="font-semibold text-slate-900">Coût estimé : {{ estimatedCost }} SMS</span>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing || !form.contact_ids.length"
                            class="w-full flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed text-[#0A2422] font-semibold py-3 rounded-xl transition-all duration-200 cursor-pointer text-sm"
                        >
                            {{ form.processing ? 'Envoi en cours...' : 'Lancer la campagne' }}
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-sm font-semibold text-slate-900">Campagnes envoyées</h2>
                    </div>
                    <div v-if="!campaigns.length" class="px-6 py-8 text-center text-sm text-slate-500">
                        Aucune campagne pour le moment.
                    </div>
                    <div v-else class="divide-y divide-slate-50">
                        <div v-for="c in campaigns" :key="c.id" class="px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ c.name }}</p>
                                <p class="text-xs text-slate-500">{{ c.sender }} · {{ c.sent_at }}</p>
                            </div>
                            <div class="text-xs text-slate-600">
                                {{ c.recipients_count }} destinataires ·
                                <span class="text-[#1D5457] font-medium">{{ c.sent_count }} envoyés</span>
                                <span v-if="c.failed_count" class="text-red-600 font-medium"> · {{ c.failed_count }} échecs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </AppLayoutClient>
</template>
