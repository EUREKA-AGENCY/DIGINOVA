<script setup lang="ts">
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Props {
    account: { api_key: string; sender_name: string | null } | null;
    endpoint: string;
}

const props = defineProps<Props>();

const copied = ref(false);

function copyKey() {
    if (!props.account) return;
    navigator.clipboard.writeText(props.account.api_key);
    copied.value = true;
    setTimeout(() => (copied.value = false), 1500);
}

const curlExample = computed(() => {
    if (!props.account) return '';
    return `curl -X POST "${props.endpoint}" \\
  -H "Authorization: Bearer ${props.account.api_key}" \\
  -H "Content-Type: application/json" \\
  -d '{
    "sender": "${props.account.sender_name || 'DIGINOVA'}",
    "destination": "237674937152",
    "message": "Bonjour depuis l'\\''API Diginova !"
  }'`;
});

const phpExample = computed(() => {
    if (!props.account) return '';
    return `<?php

$response = file_get_contents("${props.endpoint}", false, stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Authorization: Bearer ${props.account.api_key}\\r\\nContent-Type: application/json",
        'content' => json_encode([
            'sender'      => '${props.account.sender_name || 'DIGINOVA'}',
            'destination' => '237674937152',
            'message'     => 'Bonjour depuis l\\'API Diginova !',
        ]),
    ],
]));

$result = json_decode($response, true);`;
});

const regenForm = useForm({});
function regenerate() {
    if (!confirm("Régénérer la clé API ? L'ancienne clé ne fonctionnera plus immédiatement.")) return;
    regenForm.post(route('sms-pro.developers.regenerate-key'), { preserveScroll: true });
}
</script>

<template>
    <AppLayoutClient title="SMS Pro — Développeurs">
        <Head title="API Développeurs SMS — Espace client" />

        <div class="px-4 py-8 sm:px-8 max-w-3xl">
            <div class="mb-6">
                <span class="inline-block text-[#1D5457] text-xs font-semibold uppercase tracking-widest mb-2">SMS Pro</span>
                <h1 class="text-2xl font-bold text-slate-900 font-display">API Développeurs</h1>
                <p class="mt-1 text-sm text-slate-500">Envoyez des SMS directement depuis votre propre application.</p>
            </div>

            <div v-if="!account" class="rounded-2xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                <p class="text-slate-600 text-sm">
                    Votre compte SMS Pro n'est pas encore activé. Contactez Diginova pour démarrer.
                </p>
            </div>

            <template v-else>
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8 mb-6">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Votre clé API</h2>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2.5 text-sm text-slate-700 font-mono overflow-x-auto">
                            {{ account.api_key }}
                        </code>
                        <button
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-600 hover:border-[#30998A]/40 hover:text-[#1D5457] cursor-pointer whitespace-nowrap"
                            @click="copyKey"
                        >
                            {{ copied ? 'Copié !' : 'Copier' }}
                        </button>
                    </div>
                    <button
                        type="button"
                        class="mt-3 text-xs font-semibold text-red-600 hover:underline cursor-pointer"
                        @click="regenerate"
                    >
                        Régénérer la clé
                    </button>
                    <p class="mt-2 text-xs text-slate-400">
                        Gardez cette clé secrète : elle permet d'envoyer des SMS facturés sur votre solde.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8 mb-6">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Endpoint</h2>
                    <code class="block rounded-lg bg-slate-50 border border-slate-200 px-3 py-2.5 text-sm text-slate-700 font-mono">
                        POST {{ endpoint }}
                    </code>
                    <p class="mt-3 text-xs text-slate-500">
                        Paramètres : <code class="text-[#1D5457]">destination</code> (requis, format international sans +),
                        <code class="text-[#1D5457]">message</code> (requis, 480 caractères max),
                        <code class="text-[#1D5457]">sender</code> (optionnel, 11 caractères max).
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8 mb-6">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Exemple — cURL</h2>
                    <pre class="rounded-lg bg-[#0A2422] text-white/90 px-4 py-3.5 text-xs overflow-x-auto font-mono leading-relaxed">{{ curlExample }}</pre>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Exemple — PHP</h2>
                    <pre class="rounded-lg bg-[#0A2422] text-white/90 px-4 py-3.5 text-xs overflow-x-auto font-mono leading-relaxed">{{ phpExample }}</pre>
                </div>
            </template>
        </div>
    </AppLayoutClient>
</template>
