<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import InputError from '@/components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Contact {
    id: number;
    name: string;
    phone: string;
}

interface Props {
    contacts: Contact[];
}

defineProps<Props>();

const form = useForm({
    name: '',
    phone: '',
});

function submit() {
    form.post(route('sms-pro.contacts.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

function destroyContact(contact: Contact) {
    if (!confirm(`Supprimer le contact ${contact.name} ?`)) return;
    useForm({}).delete(route('sms-pro.contacts.destroy', contact.id), { preserveScroll: true });
}
</script>

<template>
    <AppLayoutClient title="SMS Pro — Contacts">
        <Head title="Contacts SMS — Espace client" />

        <div class="px-4 py-8 sm:px-8 max-w-3xl">
            <div class="mb-6">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-2">SMS Pro</span>
                <h1 class="text-2xl font-bold text-slate-900 font-display">Gérer les contacts</h1>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3 mb-6">
                    <div
                        v-for="contact in contacts"
                        :key="contact.id"
                        class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-4"
                    >
                        <div>
                            <p class="text-sm font-medium text-slate-900">{{ contact.name }}</p>
                            <p class="text-xs text-slate-500">{{ contact.phone }}</p>
                        </div>
                        <button
                            type="button"
                            class="text-xs font-semibold text-red-600 hover:underline cursor-pointer"
                            @click="destroyContact(contact)"
                        >
                            Supprimer
                        </button>
                    </div>

                    <p v-if="!contacts.length" class="text-xs text-slate-500">Aucun contact pour le moment.</p>
                </div>

                <form @submit.prevent="submit" class="grid grid-cols-1 gap-3 border-t border-slate-100 pt-6 sm:grid-cols-[1fr_1fr_auto]">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Nom</label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="ex. Jean Dupont"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wide text-slate-600">Téléphone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="237674937152"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 outline-none transition-colors focus:border-[#30998A]/60 focus:ring-2 focus:ring-[#30998A]/40"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="self-start rounded-lg bg-[#30998A] px-4 py-2 text-sm font-semibold text-[#0A2422] transition hover:bg-[#257A6E] disabled:opacity-50 cursor-pointer sm:mt-6"
                    >
                        Ajouter
                    </button>
                </form>
            </div>
        </div>
    </AppLayoutClient>
</template>
