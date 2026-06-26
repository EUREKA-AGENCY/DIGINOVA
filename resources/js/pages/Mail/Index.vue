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
    type: 'mailbox' | 'alias';
    forward_to: string | null;
}

interface MailDomainItem {
    id: number;
    name: string;
    catch_all_local_part: string | null;
    accounts: MailAccountItem[];
}

interface Props {
    domains: MailDomainItem[];
    status?: string | null;
}

defineProps<Props>();

// ── Mot de passe : génération + indicateur de force ──
function generatePassword(length = 16) {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%&*';
    const bytes = new Uint32Array(length);
    crypto.getRandomValues(bytes);
    return Array.from(bytes, (n) => chars[n % chars.length]).join('');
}

const STRENGTH_LEVELS = [
    { percent: 20, label: 'Très faible', color: 'bg-red-400' },
    { percent: 40, label: 'Faible', color: 'bg-orange-400' },
    { percent: 60, label: 'Moyen', color: 'bg-yellow-400' },
    { percent: 80, label: 'Bon', color: 'bg-[#30998A]' },
    { percent: 100, label: 'Excellent', color: 'bg-[#1D5457]' },
];

function passwordStrength(pw: unknown) {
    if (typeof pw !== 'string' || !pw) return { percent: 0, label: '', color: 'bg-slate-200' };
    let score = 0;
    if (pw.length >= 8) score++;
    if (pw.length >= 12) score++;
    if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^a-zA-Z0-9]/.test(pw)) score++;
    return STRENGTH_LEVELS[Math.min(score, STRENGTH_LEVELS.length) - 1] ?? STRENGTH_LEVELS[0];
}

// ── Création d'adresse (boîte mail ou redirection) ──
const createForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function createForm(domainId: number) {
    if (!createForms.value[domainId]) {
        createForms.value[domainId] = useForm({
            type: 'mailbox' as 'mailbox' | 'alias',
            local_part: '',
            password: '',
            forward_to: '',
        });
    }
    return createForms.value[domainId];
}

function setCreateType(domainId: number, type: 'mailbox' | 'alias') {
    createForm(domainId).type = type;
}

function fillGeneratedPassword(domainId: number) {
    createForm(domainId).password = generatePassword();
}

function submitCreate(domainId: number) {
    const form = createForm(domainId);
    form.post(route('mail.accounts.store', domainId), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function destroyAccount(domainId: number, account: MailAccountItem) {
    const verb = account.type === 'alias' ? 'la redirection' : "l'adresse";
    if (!confirm(`Supprimer définitivement ${verb} ${account.address} ? Cette action coupe la réception immédiatement.`)) {
        return;
    }
    useForm({}).delete(route('mail.accounts.destroy', [domainId, account.id]), { preserveScroll: true });
}

// ── Changement de mot de passe ──
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

function fillGeneratedPasswordFor(accountId: number) {
    const form = passwordForm(accountId);
    const pw = generatePassword();
    form.password = pw;
    form.password_confirmation = pw;
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

// ── Catch-all ──
const catchAllEditingDomainId = ref<number | null>(null);
const catchAllForms = ref<Record<number, ReturnType<typeof useForm>>>({});
function catchAllForm(domainId: number) {
    if (!catchAllForms.value[domainId]) {
        catchAllForms.value[domainId] = useForm({ local_part: '' });
    }
    return catchAllForms.value[domainId];
}

function toggleCatchAllEdit(domainId: number) {
    catchAllEditingDomainId.value = catchAllEditingDomainId.value === domainId ? null : domainId;
}

function submitCatchAll(domain: MailDomainItem) {
    const form = catchAllForm(domain.id);
    form.post(route('mail.catch-all.enable', domain.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            catchAllEditingDomainId.value = null;
        },
    });
}

function disableCatchAll(domain: MailDomainItem) {
    if (!confirm(`Désactiver le catch-all de ${domain.name} ?`)) return;
    useForm({}).delete(route('mail.catch-all.disable', domain.id), { preserveScroll: true });
}

function mailboxAccounts(domain: MailDomainItem) {
    return domain.accounts.filter((a) => a.type === 'mailbox');
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
                        Gérez les boîtes mail, redirections et le catch-all de votre domaine depuis une seule vue.
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

            <div v-for="domain in domains" :key="domain.id" class="mb-10 rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">

                <!-- En-tête domaine + catch-all -->
                <div class="flex flex-col gap-4 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                    <HeadingSmall :title="domain.name" description="Adresses actives sur ce domaine" />

                    <div class="flex flex-col items-start gap-2 sm:items-end">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Catch-all</span>
                            <span
                                v-if="domain.catch_all_local_part"
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#30998A]/10 px-2.5 py-1 text-xs font-semibold text-[#1D5457]"
                            >
                                <span class="w-1.5 h-1.5 rounded-full bg-[#30998A]"></span>
                                Vers {{ domain.catch_all_local_part }}@{{ domain.name }}
                            </span>
                            <span v-else class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Désactivé
                            </span>
                        </div>

                        <button
                            v-if="domain.catch_all_local_part"
                            type="button"
                            class="text-xs font-semibold text-red-600 hover:underline cursor-pointer"
                            @click="disableCatchAll(domain)"
                        >
                            Désactiver le catch-all
                        </button>
                        <button
                            v-else
                            type="button"
                            class="text-xs font-semibold text-[#1D5457] hover:underline cursor-pointer"
                            @click="toggleCatchAllEdit(domain.id)"
                        >
                            Configurer le catch-all
                        </button>
                    </div>
                </div>

                <form
                    v-if="catchAllEditingDomainId === domain.id"
                    class="flex flex-wrap items-end gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4 sm:px-8"
                    @submit.prevent="submitCatchAll(domain)"
                >
                    <div class="flex-1 min-w-[220px]">
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">
                            Boîte mail qui recevra tout le reste
                        </label>
                        <select
                            v-model="catchAllForm(domain.id).local_part"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        >
                            <option value="" disabled>Choisir une boîte mail…</option>
                            <option v-for="acc in mailboxAccounts(domain)" :key="acc.id" :value="acc.local_part">
                                {{ acc.address }}
                            </option>
                        </select>
                        <InputError :message="catchAllForm(domain.id).errors.local_part" />
                    </div>
                    <button
                        type="submit"
                        :disabled="catchAllForm(domain.id).processing"
                        class="rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer"
                    >
                        Activer
                    </button>
                    <button
                        type="button"
                        class="text-sm text-slate-500 hover:text-slate-700 cursor-pointer px-2 py-2"
                        @click="toggleCatchAllEdit(domain.id)"
                    >
                        Annuler
                    </button>
                </form>

                <div class="p-6 sm:p-8">
                    <div class="space-y-3">
                        <div
                            v-for="account in domain.accounts"
                            :key="account.id"
                            class="rounded-xl border border-slate-100 bg-slate-50/60 p-4"
                        >
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span class="text-sm font-medium text-slate-900">{{ account.address }}</span>
                                    <span
                                        v-if="account.type === 'mailbox'"
                                        class="text-[10px] font-semibold uppercase tracking-wide text-[#1D5457] bg-[#30998A]/10 rounded-full px-2 py-0.5"
                                    >
                                        Boîte mail
                                    </span>
                                    <span v-else class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 bg-slate-200 rounded-full px-2 py-0.5">
                                        Redirection → {{ account.forward_to }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button
                                        v-if="account.type === 'mailbox'"
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
                                class="mt-4 space-y-3"
                                @submit.prevent="submitPassword(domain.id, account)"
                            >
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto_auto]">
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
                                        type="button"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-[#30998A]/40 hover:text-[#1D5457] cursor-pointer whitespace-nowrap"
                                        @click="fillGeneratedPasswordFor(account.id)"
                                    >
                                        Générer
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="passwordForm(account.id).processing"
                                        class="rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer"
                                    >
                                        Valider
                                    </button>
                                </div>
                                <div v-if="passwordForm(account.id).password" class="flex items-center gap-2">
                                    <div class="h-1.5 flex-1 max-w-[200px] rounded-full bg-slate-200 overflow-hidden">
                                        <div
                                            class="h-full rounded-full transition-all duration-200"
                                            :class="passwordStrength(passwordForm(account.id).password).color"
                                            :style="`width: ${passwordStrength(passwordForm(account.id).password).percent}%`"
                                        ></div>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ passwordStrength(passwordForm(account.id).password).label }}</span>
                                </div>
                            </form>
                        </div>

                        <p v-if="!domain.accounts.length" class="text-xs text-slate-500">Aucune adresse pour ce domaine.</p>
                    </div>

                    <!-- Création d'une nouvelle adresse -->
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <div class="mb-4 inline-flex rounded-lg border border-slate-200 p-1">
                            <button
                                type="button"
                                :class="[
                                    'rounded-md px-3 py-1.5 text-xs font-semibold transition-colors cursor-pointer',
                                    createForm(domain.id).type === 'mailbox' ? 'bg-[#30998A] text-[#0A2422]' : 'text-slate-500 hover:text-slate-700',
                                ]"
                                @click="setCreateType(domain.id, 'mailbox')"
                            >
                                Boîte mail complète
                            </button>
                            <button
                                type="button"
                                :class="[
                                    'rounded-md px-3 py-1.5 text-xs font-semibold transition-colors cursor-pointer',
                                    createForm(domain.id).type === 'alias' ? 'bg-[#30998A] text-[#0A2422]' : 'text-slate-500 hover:text-slate-700',
                                ]"
                                @click="setCreateType(domain.id, 'alias')"
                            >
                                Réacheminer le courriel seulement
                            </button>
                        </div>

                        <form class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1fr_auto]" @submit.prevent="submitCreate(domain.id)">
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

                            <div v-if="createForm(domain.id).type === 'mailbox'">
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">
                                    Mot de passe
                                </label>
                                <div class="flex items-center gap-1.5">
                                    <input
                                        v-model="createForm(domain.id).password"
                                        type="password"
                                        placeholder="Min. 10 caractères"
                                        autocomplete="new-password"
                                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                    />
                                    <button
                                        type="button"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:border-[#30998A]/40 hover:text-[#1D5457] cursor-pointer whitespace-nowrap"
                                        @click="fillGeneratedPassword(domain.id)"
                                    >
                                        Générer
                                    </button>
                                </div>
                                <InputError :message="createForm(domain.id).errors.password" />
                                <div v-if="createForm(domain.id).password" class="mt-1.5 flex items-center gap-2">
                                    <div class="h-1.5 flex-1 max-w-[160px] rounded-full bg-slate-200 overflow-hidden">
                                        <div
                                            class="h-full rounded-full transition-all duration-200"
                                            :class="passwordStrength(createForm(domain.id).password).color"
                                            :style="`width: ${passwordStrength(createForm(domain.id).password).percent}%`"
                                        ></div>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ passwordStrength(createForm(domain.id).password).label }}</span>
                                </div>
                            </div>

                            <div v-else>
                                <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">
                                    Adresse de destination
                                </label>
                                <input
                                    v-model="createForm(domain.id).forward_to"
                                    type="email"
                                    placeholder="vous@gmail.com"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                                />
                                <InputError :message="createForm(domain.id).errors.forward_to" />
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
            </div>
        </div>
    </AppLayoutClient>
</template>
