<script setup>
import { useTranslations } from '@/composables/useTranslations';
import { computed } from 'vue';

defineProps({
    compact: {
        type: Boolean,
        default: false,
    },
});

const { locale, locales } = useTranslations();

const localizedLinks = computed(() => {
    const path = window.location.pathname || '/';
    const query = window.location.search || '';
    const segments = path.split('/').filter(Boolean);
    const supported = Object.keys(locales.value);

    return Object.entries(locales.value).map(([value, meta]) => {
        const nextSegments = [...segments];

        if (supported.includes(nextSegments[0])) {
            nextSegments[0] = value;
        } else {
            nextSegments.unshift(value);
        }

        return {
            value,
            ...meta,
            href: `/${nextSegments.join('/')}${query}`,
        };
    });
});
</script>

<template>
    <div
        :class="[
            'inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white p-1 shadow-sm',
            compact ? 'text-xs' : 'text-sm',
        ]"
    >
        <a
            v-for="link in localizedLinks"
            :key="link.value"
            :href="link.href"
            :aria-label="link.name"
            :title="link.name"
            :class="[
                'inline-flex items-center gap-1 rounded-full px-2.5 py-1 font-medium transition',
                locale === link.value
                    ? 'bg-black text-white'
                    : 'text-gray-600 hover:bg-gray-100 hover:text-black',
            ]"
        >
            <span aria-hidden="true">{{ link.flag }}</span>
            <span>{{ link.code }}</span>
        </a>
    </div>
</template>
