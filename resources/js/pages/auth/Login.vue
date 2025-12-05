<script setup>
import Checkbox from '@/components/Checkbox.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold text-diginova-blue">
                Connexion à votre espace
            </h1>
            <p class="mt-2 text-sm text-neutral-500">
                Accédez à votre espace membre et au forum de la communauté DIGINOVA.
            </p>
        </div>

        <div v-if="status" class="mb-4 rounded-lg bg-green-50 px-3 py-2 text-sm font-medium text-green-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-1">
                <InputLabel for="email" value="Adresse e-mail" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="vous@entreprise.com"
                />

                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Mot de passe" />

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-medium text-diginova-blue hover:text-diginova-red"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs text-neutral-600">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>Se souvenir de moi</span>
                </label>

                <Link
                    :href="route('register')"
                    class="text-xs font-medium text-diginova-blue hover:text-diginova-red"
                >
                    Créer un compte
                </Link>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center text-sm"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Se connecter
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
