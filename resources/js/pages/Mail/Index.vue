<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface MailAccountItem {
    id: number;
    local_part: string;
    address: string;
}

interface MailDomainItem {
    id: number;
    name: string;
    accounts: MailAccountItem[];
}

interface Props {
    domains: MailDomainItem[];
    status?: string | null;
}

defineProps<Props>();

const createForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function createForm(domainId: number) {
    if (!createForms.value[domainId]) {
        createForms.value[domainId] = useForm({ local_part: '', password: '' });
    }
    return createForms.value[domainId];
}

function submitCreate(domainId: number) {
    const form = createForm(domainId);
    form.post(route('mail.accounts.store', domainId), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function destroyAccount(domainId: number, account: MailAccountItem) {
    if (!confirm(`Supprimer définitivement ${account.address} ? Cette action coupe la réception immédiatement.`)) {
        return;
    }
    useForm({}).delete(route('mail.accounts.destroy', [domainId, account.id]), { preserveScroll: true });
}

const passwordEditId = ref<number | null>(null);
const passwordForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function passwordForm(accountId: number) {
    if (!passwordForms.value[accountId]) {
        passwordForms.value[accountId] = useForm({ password: '', password_confirmation: '' });
    }
    return passwordForms.value[accountId];
}

function togglePasswordEdit(accountId: number) {
    passwordEditId.value = passwordEditId.value === accountId ? null : accountId;
}

function submitPassword(domainId: number, account: MailAccountItem) {
    const form = passwordForm(account.id);
    form.put(route('mail.accounts.password', [domainId, account.id]), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            passwordEditId.value = null;
        },
    });
}
</script>

<template>
    <AppLayoutClient title="Messagerie">
        <Head title="Messagerie — Espace client" />

        <div class="px-4 py-8 sm:px-8">
            <div
                class="mb-8 flex flex-col justify-between gap-4 rounded-2xl px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
                style="background: linear-gradient(135deg, #1D5457 0%, #0D2B29 100%);"
            >
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">ESPACE CLIENT</p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">Messagerie professionnelle</h1>
                    <p class="mt-1 text-sm text-white/70">
                        Créez, supprimez et changez le mot de passe des adresses de votre domaine.
                    </p>
                </div>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <div v-if="!domains.length" class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <p class="text-slate-600 text-sm">
                    Aucun domaine n'est encore associé à votre compte. Contactez Diginova pour activer votre messagerie pro.
                </p>
            </div>

            <div v-for="domain in domains" :key="domain.id" class="mb-10 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                <HeadingSmall :title="domain.name" description="Adresses actives sur ce domaine" />

                <div class="mt-5 space-y-3">
                    <div
                        v-for="account in domain.accounts"
                        :key="account.id"
                        class="rounded-xl border border-slate-100 bg-slate-50/60 p-4"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-medium text-slate-900">{{ account.address }}</span>
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer"
                                    @click="togglePasswordEdit(account.id)"
                                >
                                    Changer le mot de passe
                                </button>
                                <button
                                    type="button"
                                    class="text-xs font-semibold text-red-600 hover:underline cursor-pointer"
                                    @click="destroyAccount(domain.id, account)"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <form
                            v-if="passwordEditId === account.id"
                            class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto]"
                            @submit.prevent="submitPassword(domain.id, account)"
                        >
                            <div>
                                <input
                                    v-model="passwordForm(account.id).password"
                                    type="password"
                                    placeholder="Nouveau mot de passe"
                                    autocomplete="new-password"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                />
                                <InputError :message="passwordForm(account.id).errors.password" />
                            </div>
                            <div>
                                <input
                                    v-model="passwordForm(account.id).password_confirmation"
                                    type="password"
                                    placeholder="Confirmer"
                                    autocomplete="new-password"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                />
                            </div>
                            <button
                                type="submit"
                                :disabled="passwordForm(account.id).processing"
                                class="rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer"
                            >
                                Valider
                            </button>
                        </form>
                    </div>

                    <p v-if="!domain.accounts.length" class="text-xs text-slate-500">Aucune adresse pour ce domaine.</p>
                </div>

                <form
                    class="mt-6 grid grid-cols-1 gap-3 border-t border-slate-100 pt-6 sm:grid-cols-[1fr_1fr_auto]"
                    @submit.prevent="submitCreate(domain.id)"
                >
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">
                            Nouvelle adresse
                        </label>
                        <div class="flex items-center gap-1">
                            <input
                                v-model="createForm(domain.id).local_part"
                                type="text"
                                placeholder="ex. contact"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                            />
                            <span class="text-sm text-slate-500 whitespace-nowrap">@{{ domain.name }}</span>
                        </div>
                        <InputError :message="createForm(domain.id).errors.local_part" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">
                            Mot de passe
                        </label>
                        <input
                            v-model="createForm(domain.id).password"
                            type="password"
                            placeholder="Min. 10 caractères"
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <InputError :message="createForm(domain.id).errors.password" />
                    </div>
                    <button
                        type="submit"
                        :disabled="createForm(domain.id).processing"
                        class="self-start rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer sm:mt-6"
                    >
                        Créer l'adresse
                    </button>
                </form>
            </div>
        </div>
    </AppLayoutClient>
</template>
