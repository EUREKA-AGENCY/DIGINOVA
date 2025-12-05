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
            <h1 class="text-2xl font-semibold text-diginova-blue">
                Rejoindre la communauté DIGINOVA
            </h1>
            <p class="mt-2 text-sm text-neutral-500">
                Créez votre compte pour accéder à l’espace membre et participer au forum.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-1">
                <InputLabel for="name" value="Nom complet" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nom et prénom"
                />

                <InputError class="mt-1 text-xs" :message="form.errors.name" />
            </div>

            <div class="space-y-1">
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="vous@entreprise.com"
                />

                <InputError class="mt-1 text-xs" :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <InputLabel for="password" value="Mot de passe" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-1 text-xs" :message="form.errors.password" />
            </div>

            <div class="space-y-1">
                <InputLabel
                    for="password_confirmation"
                    value="Confirmer le mot de passe"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError
                    class="mt-1 text-xs"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-2 flex items-center justify-between text-xs text-neutral-600">
                <span>
                    Déjà inscrit ?
                    <Link
                        :href="route('login')"
                        class="font-semibold text-diginova-blue hover:text-diginova-red"
                    >
                        Se connecter
                    </Link>
                </span>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center text-sm"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Créer mon compte
                </PrimaryButton>
            </div>

            <p class="pt-3 text-center text-[11px] text-neutral-400">
                En créant un compte, vous acceptez les règles d’utilisation de la communauté DIGINOVA.
            </p>
        </form>
    </GuestLayout>
</template>
