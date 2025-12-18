<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayoutPublic from '@/layouts/AppLayoutPublic.vue';
import type { User } from '@/types';

interface Thread {
    id: number;
    title: string;
    replies_count: number;
    created_at: string;
}

interface Reply {
    id: number;
    body: string;
    thread: {
        id: number;
        title: string;
    };
    created_at: string;
}

interface Props {
    threads: Thread[];
    replies: Reply[];
}

defineProps<Props>();

const page = usePage();
const user = page.props.auth.user as User;
</script>

<template>
    <AppLayoutPublic>
        <Head title="Espace membre" />

        <div class="px-4 py-8 sm:px-8">
            <!-- Header -->
            <div
                class="mb-8 flex flex-col justify-between gap-4 rounded-2xl bg-gradient-to-r from-diginova-blue via-diginova-blue/95 to-diginova-red/80 px-6 py-6 text-white shadow-md sm:flex-row sm:items-center sm:px-8 sm:py-7"
            >
                <div>
                    <p class="text-xs font-medium tracking-[0.25em] text-white/60">
                        ESPACE MEMBRE
                    </p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">
                        Bonjour, {{ user.name }}
                    </h1>
                    <p class="mt-1 text-sm text-white/80">
                        Retrouvez ici votre profil et votre activité sur le forum de la communauté DIGINOVA.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="route('members.show', user.id)"
                        class="inline-flex items-center rounded-full border border-white/30 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white/90 shadow-sm transition hover:border-white hover:bg-white/10"
                    >
                        Voir mon profil public
                    </Link>
                    <Link
                        :href="route('blogs.index', { new: 1 })"
                        class="inline-flex items-center rounded-full bg-white px-4 py-2 text-xs font-semibold uppercase tracking-wide text-diginova-red shadow-sm transition hover:bg-neutral-100"
                    >
                        Lancer un nouveau sujet
                    </Link>
                </div>
            </div>

            <!-- Grid de cartes -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Carte Profil -->
                <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100">
                    <HeadingSmall
                        title="Mon profil"
                        description="Informations principales de votre compte"
                    />

                    <div class="mt-4 space-y-3 text-sm text-neutral-700">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-diginova-blue/10 text-sm font-semibold text-diginova-blue"
                            >
                                {{ user.name.charAt(0) }}
                            </div>
                            <div class="leading-tight">
                                <p class="font-medium text-neutral-900">
                                    {{ user.name }}
                                </p>
                                <p class="text-xs text-neutral-500">
                                    Membre de la communauté DIGINOVA
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 space-y-1 text-xs text-neutral-600">
                            <p class="font-semibold text-neutral-800">Adresse e-mail</p>
                            <p>{{ user.email }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <Link
                            href="/settings/profile"
                            class="inline-flex items-center rounded-full border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-700 hover:border-diginova-red hover:text-diginova-red"
                        >
                            Modifier mon profil
                        </Link>
                        <Link
                            href="/settings/password"
                            class="inline-flex items-center rounded-full border border-neutral-200 px-3 py-1.5 text-xs font-medium text-neutral-700 hover:border-diginova-red hover:text-diginova-red"
                        >
                            Mettre à jour mon mot de passe
                        </Link>
                    </div>
                </section>

                <!-- Carte activité forum (sujets) -->
                <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100 lg:col-span-1">
                    <HeadingSmall
                        title="Mes derniers sujets"
                        description="Discussions que vous avez lancées"
                    />
                    <div class="mt-4 space-y-3">
                        <div
                            v-for="thread in threads"
                            :key="thread.id"
                            class="rounded-xl border border-neutral-100 bg-neutral-50/80 p-3 text-xs shadow-sm transition hover:border-diginova-red/40 hover:bg-white"
                        >
                            <Link
                                :href="route('blogs.show', thread.id)"
                                class="font-semibold text-neutral-900 hover:text-diginova-red"
                            >
                                {{ thread.title }}
                            </Link>
                            <div class="mt-1 flex items-center justify-between text-[11px] text-neutral-500">
                                <span>
                                    {{ thread.replies_count }} réponse<span v-if="thread.replies_count !== 1">s</span>
                                </span>
                                <span>Créé {{ thread.created_at }}</span>
                            </div>
                        </div>

                        <p v-if="!threads.length" class="text-xs text-neutral-500">
                            Vous n'avez pas encore créé de sujet. Lancez votre première discussion !
                        </p>
                    </div>
                </section>

                <!-- Carte activité forum (réponses + actions rapides) -->
                <section class="space-y-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-neutral-100 lg:col-span-1">
                    <div>
                        <HeadingSmall
                            title="Mes dernières réponses"
                            description="Contributions aux discussions"
                        />
                        <div class="mt-4 space-y-3">
                            <div
                                v-for="reply in replies"
                                :key="reply.id"
                                class="rounded-xl border border-neutral-100 bg-neutral-50/80 p-3 text-xs shadow-sm transition hover:border-diginova-red/40 hover:bg-white"
                            >
                                <p class="line-clamp-2 text-neutral-800">
                                    {{ reply.body }}
                                </p>
                                <div class="mt-1 text-[11px] text-neutral-500">
                                    Sur
                                    <Link
                                        :href="route('blogs.show', reply.thread.id)"
                                        class="font-medium text-diginova-red hover:underline"
                                    >
                                        {{ reply.thread.title }}
                                    </Link>
                                    • {{ reply.created_at }}
                                </div>
                            </div>

                            <p v-if="!replies.length" class="text-xs text-neutral-500">
                                Vous n'avez pas encore répondu à un sujet. Rejoignez une discussion sur le forum.
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-neutral-200 pt-4">
                        <HeadingSmall
                            title="Actions rapides"
                            description="Accès directs à vos sections clés"
                        />
                        <div class="mt-3 grid gap-2 text-xs">
                            <Link
                                :href="route('blogs.index')"
                                class="flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 font-medium text-neutral-800 hover:border-diginova-red hover:text-diginova-red"
                            >
                                <span>Accéder au forum</span>
                                <span class="text-neutral-400">&rarr;</span>
                            </Link>
                            <Link
                                :href="route('members.show', user.id)"
                                class="flex items-center justify-between rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 font-medium text-neutral-800 hover:border-diginova-red hover:text-diginova-red"
                            >
                                <span>Voir ma page publique</span>
                                <span class="text-neutral-400">&rarr;</span>
                            </Link>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayoutPublic>
</template>
