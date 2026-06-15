<script setup>
import LanguageSwitcher from '@/Components/Localization/LanguageSwitcher.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const locale = computed(() => page.props.i18n?.locale || 'ka');

const socialLinks = [
    {
        name: 'Facebook',
        href: 'https://www.facebook.com/unshoes.geo',
    },
    {
        name: 'Instagram',
        href: 'https://www.instagram.com/unshoesgeo',
    },
];
</script>

<template>
    <div class="min-h-screen bg-white text-gray-950">
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <Link :href="route('home', { locale })" class="max-w-full break-words text-sm font-semibold uppercase tracking-[0.14em] text-black sm:tracking-[0.18em]">
                    {{ $t('app_name') }}
                </Link>

                <nav class="flex items-center justify-between gap-3 overflow-x-auto text-sm text-gray-700 lg:justify-end">
                    <div class="flex shrink-0 items-center gap-4">
                        <Link :href="route('products.index', { locale })" class="py-2 hover:text-black">{{ $t('nav.products') }}</Link>
                        <Link :href="route('about', { locale })" class="py-2 hover:text-black">{{ $t('nav.about') }}</Link>
                    </div>
                    <LanguageSwitcher compact />
                </nav>
            </div>
        </header>

        <div v-if="flashSuccess" class="border-b border-gray-200 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm text-gray-800 sm:px-6 lg:px-8">
                {{ flashSuccess }}
            </div>
        </div>

        <main>
            <slot />
        </main>

        <footer class="mt-20 border-t border-gray-200">
            <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-8 text-sm text-gray-600 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <a href="tel:+995598792626" class="w-fit text-gray-700 transition hover:text-black">
                    {{ $t('footer.mobile') }}
                </a>

                <div class="flex flex-wrap gap-x-6 gap-y-3">
                    <a
                        v-for="link in socialLinks"
                        :key="link.name"
                        :href="link.href"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-700 transition hover:text-black"
                    >
                        {{ link.name }}
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>
