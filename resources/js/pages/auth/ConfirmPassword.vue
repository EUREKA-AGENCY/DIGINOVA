<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirmer le mot de passe" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900 font-display">
                Zone sécurisée
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Merci de confirmer votre mot de passe avant de continuer.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="password" value="Mot de passe" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="••••••••"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirmer
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
