<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue';

interface DeveloperClient {
    id: string;
    name: string;
    created_at: string | null;
}

interface DeveloperWebhook {
    id: number;
    url: string;
    events: string[];
    is_active: boolean;
    created_at: string | null;
}

interface NewClientPayload {
    client_id: string;
    client_secret: string;
    name: string;
    token_type?: string;
    access_token?: string;
    expires_at?: string | null;
}

interface Props {
    clients: DeveloperClient[];
    webhooks: DeveloperWebhook[];
    tokenEndpoint: string;
    apiBaseUrl: string;
    docsUrl: string;
    newClient?: NewClientPayload | null;
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    redirect_uri: '',
});

const webhookForm = useForm({
    url: '',
});

const submit = () => {
    form.post(route('developer.apps.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name', 'redirect_uri');
        },
    });
};

const revoke = (client: DeveloperClient) => {
    router.delete(route('developer.apps.destroy', client.id), {
        preserveScroll: true,
    });
};

const submitWebhook = () => {
    webhookForm.post(route('developer.webhooks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            webhookForm.reset('url');
        },
    });
};

const revokeWebhook = (webhook: DeveloperWebhook) => {
    router.delete(route('developer.webhooks.destroy', webhook.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayoutPublic>
        <Head title="Espace développeur" />

        <div class="bg-gray-50 py-10">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div
                class="mb-8 rounded-2xl bg-gradient-to-r from-diginova-blue via-diginova-blue/95 to-diginova-red/80 px-6 py-6 text-white shadow-md sm:px-8 sm:py-7"
            >
                <p class="text-xs font-medium tracking-[0.25em] text-white/70">DEVELOPER HUB</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight">Espace développeur</h1>
                <p class="mt-2 text-sm text-white/80">
                    Créez une application, obtenez vos identifiants d'accès et consultez la documentation de l'API
                    forum DIGINOVA.
                </p>
            </div>

            <div
                v-if="props.newClient"
                class="mb-8 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900 shadow-sm"
            >
                <h2 class="text-sm font-semibold">Nouvelle application créée</h2>
                <p class="mt-1 text-xs">
                    Copiez immédiatement ces identifiants : le secret et le jeton ne seront plus affichés après avoir quitté
                    cette page.
                </p>
                <div class="mt-3 grid gap-2 text-xs font-mono">
                    <div>
                        <span class="font-semibold">client_id :</span>
                        <span class="ml-2 break-all">{{ props.newClient.client_id }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">client_secret :</span>
                        <span class="ml-2 break-all">{{ props.newClient.client_secret }}</span>
                    </div>
                    <div v-if="props.newClient.access_token">
                        <span class="font-semibold">access_token :</span>
                        <span class="ml-2 break-all">{{ props.newClient.access_token }}</span>
                    </div>
                    <div v-if="props.newClient.expires_at" class="text-[11px] text-amber-800">
                        Expire le : {{ new Date(props.newClient.expires_at).toLocaleString() }}
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Formulaire de création d'application -->
                <section class="space-y-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
                    <HeadingSmall
                        title="Créer une application"
                        description="Générez un couple client_id / client_secret pour accéder à l’API forum."
                    />

                    <form @submit.prevent="submit" class="mt-2 space-y-4 text-sm">
                        <div class="grid gap-2">
                            <label for="name" class="text-xs font-medium text-neutral-800">Nom de l’application</label>
                            <Input
                                id="name"
                                v-model="form.name"
                                required
                                autocomplete="off"
                                placeholder="Ex. Mon intégration CRM"
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <label for="redirect_uri" class="text-xs font-medium text-neutral-800">
                                Redirect URI (optionnel)
                            </label>
                            <Input
                                id="redirect_uri"
                                v-model="form.redirect_uri"
                                type="url"
                                autocomplete="off"
                                placeholder="https://votre-application.com/callback"
                            />
                            <p class="mt-1 text-xs text-neutral-500">
                                Utilisé pour certains flux OAuth. Pour le flux par mot de passe actuel, ce champ est
                                facultatif.
                            </p>
                            <InputError class="mt-1 text-xs" :message="form.errors.redirect_uri" />
                        </div>

                        <div class="pt-2">
                            <Button :disabled="form.processing">Créer l’application</Button>
                        </div>
                    </form>
                </section>

                <!-- Liste des applications -->
                <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
                    <HeadingSmall
                        title="Mes applications"
                        description="Clients OAuth créés depuis votre compte."
                    />

                    <div class="mt-4 space-y-3 text-xs">
                        <div
                            v-for="client in props.clients"
                            :key="client.id"
                            class="rounded-xl border border-neutral-100 bg-neutral-50/80 p-3 shadow-sm"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-medium text-neutral-900">
                                        {{ client.name }}
                                    </p>
                                    <p class="mt-1 break-all font-mono text-[11px] text-neutral-700">
                                        {{ client.id }}
                                    </p>
                                    <p v-if="client.created_at" class="mt-1 text-[11px] text-neutral-500">
                                        Créé {{ client.created_at }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="text-[11px] font-medium text-red-600 hover:text-red-700"
                                    @click="revoke(client)"
                                >
                                    Révoquer
                                </button>
                            </div>
                        </div>

                        <p v-if="!props.clients.length" class="text-xs text-neutral-500">
                            Vous n’avez pas encore créé d’application. Créez-en une pour commencer à consommer l’API
                            forum.
                        </p>
                    </div>
                </section>
            </div>

            <!-- Webhooks -->
            <section class="mt-8 grid gap-6 lg:grid-cols-3">
                <section class="space-y-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
                    <HeadingSmall
                        title="Configurer un webhook"
                        description="Recevez une notification HTTP lorsqu'un évènement se produit."
                    />

                    <form @submit.prevent="submitWebhook" class="mt-2 space-y-4 text-sm">
                        <div class="grid gap-2">
                            <label for="webhook_url" class="text-xs font-medium text-neutral-800">
                                URL du webhook
                            </label>
                            <Input
                                id="webhook_url"
                                v-model="webhookForm.url"
                                type="url"
                                required
                                autocomplete="off"
                                placeholder="https://votre-système.com/webhooks/diginova"
                            />
                            <p class="mt-1 text-xs text-neutral-500">
                                Un POST JSON sera envoyé à chaque événement sélectionné. Par défaut, tous les événements sont envoyés.
                            </p>
                            <InputError class="mt-1 text-xs" :message="webhookForm.errors.url" />
                        </div>

                        <div class="pt-2">
                            <Button :disabled="webhookForm.processing">Enregistrer le webhook</Button>
                        </div>
                    </form>
                </section>

                <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100 lg:col-span-2">
                    <HeadingSmall
                        title="Mes webhooks"
                        description="Points d’entrée HTTP appelés lors des évènements."
                    />

                    <div class="mt-4 space-y-3 text-xs">
                        <div
                            v-for="webhook in props.webhooks"
                            :key="webhook.id"
                            class="rounded-xl border border-neutral-100 bg-neutral-50/80 p-3 shadow-sm"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="break-all font-mono text-[11px] text-neutral-900">
                                        {{ webhook.url }}
                                    </p>
                                    <p class="mt-1 text-[11px] text-neutral-500">
                                        Événements :
                                        <span v-if="webhook.events.includes('*')">tous</span>
                                        <span v-else>{{ webhook.events.join(', ') || 'tous' }}</span>
                                    </p>
                                    <p v-if="webhook.created_at" class="mt-1 text-[11px] text-neutral-500">
                                        Créé {{ webhook.created_at }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="text-[11px] font-medium text-red-600 hover:text-red-700"
                                    @click="revokeWebhook(webhook)"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <p v-if="!props.webhooks.length" class="text-xs text-neutral-500">
                            Vous n’avez pas encore configuré de webhook. Ajoutez une URL pour commencer à recevoir les
                            notifications d’évènements DIGINOVA.
                        </p>
                    </div>
                </section>
            </section>

            <!-- Documentation API -->
            <section class="mt-8 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
                <HeadingSmall
                    title="Documentation de l’API forum"
                    description="Consultez le contrat OpenAPI et testez les endpoints."
                />

                <div class="mt-3 grid gap-4 text-xs md:grid-cols-2">
                    <div class="space-y-3">
                        <p class="text-neutral-600">
                            La documentation interactive est disponible ici :
                        </p>
                        <a
                            :href="props.docsUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center rounded-full border border-diginova-blue px-3 py-1.5 text-xs font-medium text-diginova-blue transition hover:bg-diginova-blue hover:text-white"
                        >
                            Ouvrir la documentation API
                        </a>

                        <div class="mt-4 space-y-1">
                            <p class="text-neutral-600">Base URL de l’API :</p>
                            <p class="break-all font-mono text-[11px] text-neutral-900">
                                {{ props.apiBaseUrl }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-neutral-600">
                            Exemple d’appel pour obtenir un jeton d’accès :
                        </p>
                        <pre
                            class="mt-2 max-h-64 overflow-x-auto rounded-lg bg-neutral-900 p-3 text-[11px] leading-relaxed text-neutral-50"
                        >curl -X POST '{{ props.tokenEndpoint }}' \
  -H 'Content-Type: application/json' \
  -d '{
    "grant_type": "password",
    "client_id": "VOTRE_CLIENT_ID",
    "client_secret": "VOTRE_CLIENT_SECRET",
    "username": "email@diginova.cm",
    "password": "votre_mot_de_passe"
  }'</pre>
                    </div>
                </div>
            </section>
            </div>
        </div>
    </AppLayoutPublic>
</template>
