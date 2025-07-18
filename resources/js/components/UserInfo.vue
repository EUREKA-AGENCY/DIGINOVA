<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

interface Props {
    user: User | null; // Accepte maintenant null
    showEmail?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    user: null, // Valeur par défaut explicite
    showEmail: false,
});

const { getInitials } = useInitials();

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.user?.avatar && props.user.avatar !== '');

// Initials pour l'avatar fallback
const userInitials = computed(() => 
    props.user ? getInitials(props.user.name) : '?'
);

// Nom affiché
const userName = computed(() => props.user?.name || 'Invité');

// Email affiché
const userEmail = computed(() => props.user?.email || 'Non connecté');
</script>

<template>
    <div class="flex items-center gap-3">
        <!-- Avatar -->
        <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
            <AvatarImage 
                v-if="showAvatar" 
                :src="user!.avatar" 
                :alt="userName" 
            />
            <AvatarFallback class="rounded-lg text-black">
                {{ userInitials }}
            </AvatarFallback>
        </Avatar>

        <!-- Informations utilisateur -->
        <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-medium">{{ userName }}</span>
            <span 
                v-if="showEmail" 
                class="truncate text-xs text-muted-foreground"
            >
                {{ userEmail }}
            </span>
        </div>
    </div>
</template>
