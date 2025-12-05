<script setup>
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-br from-diginova-blue via-diginova-blue/95 to-diginova-red/80 px-4 py-10 sm:px-6 lg:px-8"
    >
        <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-5xl items-center justify-center">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center justify-center">
                    <Link href="/" class="inline-flex items-center gap-3">
                        <ApplicationLogo class="h-12 w-12 fill-current text-white drop-shadow-lg" />
                        <span class="diginova-uppercase text-sm font-semibold tracking-[0.25em] text-white/80">
                            DIGINOVA
                        </span>
                    </Link>
                </div>

                <div
                    class="overflow-hidden rounded-2xl bg-white/95 px-6 py-6 shadow-xl ring-1 ring-white/40 backdrop-blur-sm sm:px-8 sm:py-8"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
