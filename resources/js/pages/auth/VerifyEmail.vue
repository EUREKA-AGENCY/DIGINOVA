<script setup>
import { computed } from 'vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification de l'email" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900 font-display">
                Vérifiez votre email
            </h1>
            <p class="mt-2 text-sm text-slate-500">
                Merci de votre inscription ! Avant de commencer, cliquez sur le lien que nous venons
                de vous envoyer par email. Vous ne l'avez pas reçu ? Nous pouvons vous en renvoyer un.
            </p>
        </div>

        <div
            v-if="verificationLinkSent"
            class="mb-4 rounded-xl border border-[#30998A]/30 bg-[#30998A]/10 px-3 py-2 text-sm font-medium text-[#1D5457]"
        >
            Un nouveau lien de vérification a été envoyé à l'adresse fournie lors de l'inscription.
        </div>

        <form @submit.prevent="submit" class="flex items-center justify-between gap-4">
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Renvoyer l'email de vérification
            </PrimaryButton>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="text-sm text-slate-500 hover:text-slate-700 cursor-pointer focus-ring rounded"
            >
                Se déconnecter
            </Link>
        </form>
    </GuestLayout>
</template>
