<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Mot de passe oublié" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900 font-display">
                Mot de passe oublié
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Indiquez votre adresse e-mail, nous vous envoyons un lien pour en choisir un nouveau.
            </p>
        </div>

        <div v-if="status" class="mb-4 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-3 py-2 text-sm font-medium text-[#1D5457]">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="vous@entreprise.com"
                />

                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Envoyer le lien de réinitialisation
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
