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
        initial: 'f',
        class: 'bg-[#1877f2] text-white',
    },
    {
        name: 'Instagram',
        href: 'https://www.instagram.com/unshoesgeo',
        initial: 'ig',
        class: 'bg-gradient-to-br from-[#f58529] via-[#dd2a7b] to-[#515bd4] text-white',
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

        <footer class="mt-20 border-t border-gray-200 bg-gray-50">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 text-sm text-gray-600 sm:grid-cols-2 sm:px-6 lg:grid-cols-[1fr_1fr_1.25fr] lg:px-8">
                <div class="min-w-0">
                    <p class="font-medium text-gray-950">{{ $t('app_name') }}</p>
                    <p class="mt-2">{{ $t('footer.location') }}</p>
                </div>
                <div class="min-w-0">
                    <p class="font-medium text-gray-950">{{ $t('footer.no_checkout_title') }}</p>
                    <p class="mt-2">{{ $t('footer.no_checkout_text') }}</p>
                    <p class="mt-5 font-medium text-gray-950">{{ $t('footer.contact_title') }}</p>
                    <p class="mt-2">{{ $t('footer.contact_text') }}</p>
                </div>
                <div class="min-w-0 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:col-span-2 lg:col-span-1">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.16em] text-gray-500">{{ $t('footer.social_title') }}</p>
                            <p class="mt-2 text-3xl font-semibold text-gray-950">{{ $t('footer.facebook_followers_count') }}</p>
                            <p class="text-sm text-gray-600">{{ $t('footer.facebook_followers_label') }}</p>
                        </div>

                        <a
                            href="https://www.facebook.com/unshoes.geo"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex shrink-0 items-center justify-center rounded-md bg-[#1877f2] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#0f66d8]"
                            :aria-label="$t('footer.facebook_followers_label')"
                        >
                            <span class="mr-2 flex size-7 items-center justify-center rounded bg-white/20 text-base font-bold">f</span>
                            {{ $t('footer.follow_facebook') }}
                        </a>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <a
                            v-for="link in socialLinks"
                            :key="link.name"
                            :href="link.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex min-h-12 items-center gap-3 rounded-md border border-gray-200 bg-white px-3 py-2 text-gray-800 transition hover:border-gray-400"
                        >
                            <span class="flex size-8 shrink-0 items-center justify-center rounded text-xs font-bold uppercase" :class="link.class">
                                {{ link.initial }}
                            </span>
                            <span class="min-w-0">
                                <span class="block truncate font-medium text-gray-950">{{ link.name }}</span>
                                <span class="block truncate text-xs text-gray-500">{{ $t('footer.open_social') }}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
