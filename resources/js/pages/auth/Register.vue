<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Créer un compte" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900 font-display">
                Créer votre espace client
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Créez votre compte pour gérer votre messagerie professionnelle.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Nom complet" />

                <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nom et prénom"
                />

                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="vous@entreprise.com"
                />

                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Mot de passe" />

                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmer le mot de passe"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError
                    class="mt-1.5"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <span>
                    Déjà inscrit ?
                    <Link
                        :href="route('login')"
                        class="font-semibold text-[#1D5457] hover:text-[#30998A]"
                    >
                        Se connecter
                    </Link>
                </span>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Créer mon compte
                </PrimaryButton>
            </div>

            <p class="pt-3 text-center text-[11px] text-slate-400">
                En créant un compte, vous acceptez les conditions d'utilisation de Diginova.
            </p>
        </form>
    </GuestLayout>
</template>
