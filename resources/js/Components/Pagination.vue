<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav v-if="links.length > 3" class="flex items-center gap-2 overflow-x-auto pb-1">
        <component
            :is="link.url ? Link : 'span'"
            v-for="link in links"
            :key="`${link.label}-${link.url}`"
            :href="link.url || undefined"
            preserve-scroll
            :class="[
                'min-w-11 shrink-0 border px-3 py-2.5 text-center text-sm',
                link.active
                    ? 'border-black bg-black text-white'
                    : link.url
                      ? 'border-gray-200 text-gray-700 hover:border-black hover:text-black'
                      : 'border-gray-100 text-gray-300',
            ]"
            v-html="link.label"
        />
    </nav>
</template>
