<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayoutClient from '@/layouts/AppLayoutClient.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
</script>

<template>
    <AppLayoutClient title="Apparence">
        <Head title="Appearance settings" />

        <section class="bg-gray-50 py-10">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <SettingsLayout>
                    <div class="space-y-6">
                        <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                        <AppearanceTabs />
                    </div>
                </SettingsLayout>
            </div>
        </section>
    </AppLayoutClient>
</template>
