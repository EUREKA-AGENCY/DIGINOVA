<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden px-4 py-10">
        <!-- Background photo + dark teal overlay (meme recette que le Hero) -->
        <div class="absolute inset-0">
            <img
                src="/images/site/hero-dev.webp"
                alt=""
                class="w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0" style="background: linear-gradient(160deg, rgba(6,21,20,0.96) 0%, rgba(10,36,34,0.93) 55%, rgba(13,43,41,0.9) 100%);"></div>
        </div>

        <div class="pointer-events-none absolute -top-10 right-10 w-72 h-72 rounded-full border border-white/8"></div>
        <div class="pointer-events-none absolute bottom-0 left-10 w-56 h-56 rounded-full border border-[#30998A]/20"></div>

        <div class="relative w-full max-w-md">
            <Link href="/" class="flex items-center justify-center gap-3 mb-8 cursor-pointer">
                <img src="/logo-icon.png" alt="Diginova" width="32" height="32" class="h-8 w-auto" />
                <span class="text-white font-bold text-lg tracking-tight font-display">Diginova</span>
            </Link>

            <div class="rounded-2xl bg-white shadow-2xl p-6 sm:p-8">
                <slot />
            </div>
        </div>
    </div>
</template>
