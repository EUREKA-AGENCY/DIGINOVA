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

interface Props {
    account: { balance: number; sender_name: string | null } | null;
    contacts: Contact[];
    status?: string | null;
}

const props = defineProps<Props>();

const form = useForm({
    sender: props.account?.sender_name || 'DIGINOVA',
    destination: '',
    message: '',
});

const segments = computed(() => Math.max(1, Math.ceil(form.message.length / 160)));

function submit() {
    form.post(route('sms-pro.send'), {
        preserveScroll: true,
        onSuccess: () => {
            form.message = '';
        },
    });
}

function fillContact(phone: string) {
    form.destination = phone;
}
</script>

<template>
    <AppLayoutClient title="SMS Pro — Envoyer">
        <Head title="Envoyer des SMS — Espace client" />

        <div class="px-4 py-8 sm:px-8 max-w-3xl">
            <div class="mb-6">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-2">SMS Pro</span>
                <h1 class="text-2xl font-bold text-slate-900 font-display">Préparez votre envoi de SMS</h1>
            </div>

            <p v-if="status" class="mb-6 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-4 py-3 text-sm text-[#1D5457]">
                {{ status }}
            </p>

            <div v-if="!account" class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <p class="text-slate-600 text-sm">
                    Votre compte SMS Pro n'est pas encore activé. Contactez Diginova pour démarrer.
                </p>
            </div>

            <div v-else class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 flex items-center justify-between text-sm">
                    <span class="text-slate-500">Solde disponible</span>
                    <span class="font-semibold text-slate-900">{{ account.balance }} SMS</span>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Expéditeur</label>
                        <input
                            v-model="form.sender"
                            type="text"
                            maxlength="11"
                            placeholder="DIGINOVA"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <p class="mt-1 text-xs text-slate-400">Limité à 11 caractères.</p>
                        <InputError :message="form.errors.sender" />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Destinataire</label>
                        <input
                            v-model="form.destination"
                            type="text"
                            placeholder="237674937152"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <p class="mt-1 text-xs text-slate-400">Numéro au format international, sans le signe +. Ex. 237674937152.</p>
                        <InputError :message="form.errors.destination" />

                        <div v-if="contacts.length" class="mt-2 flex flex-wrap gap-1.5">
                            <button
                                v-for="c in contacts"
                                :key="c.id"
                                type="button"
                                class="text-xs rounded-full border border-slate-200 px-2.5 py-1 text-slate-600 hover:border-[#30998A]/40 hover:text-[#1D5457] cursor-pointer"
                                @click="fillContact(c.phone)"
                            >
                                {{ c.name }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Message</label>
                        <textarea
                            v-model="form.message"
                            rows="5"
                            maxlength="480"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        ></textarea>
                        <div class="mt-1 flex items-center justify-between">
                            <p class="text-xs text-slate-400">{{ form.message.length }} / 480 caractères</p>
                            <p class="text-xs text-slate-400">{{ segments }} SMS</p>
                        </div>
                        <InputError :message="form.errors.message" />
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex items-center justify-center gap-2 bg-[#30998A] hover:bg-[#257A6E] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed text-[#0A2422] font-semibold py-3 rounded-xl transition-all duration-200 cursor-pointer text-sm"
                    >
                        {{ form.processing ? 'Envoi en cours...' : 'Envoyer' }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayoutClient>
</template>
