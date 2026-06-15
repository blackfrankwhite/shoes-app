<script setup>
import LanguageSwitcher from '@/Components/Localization/LanguageSwitcher.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const locale = computed(() => page.props.i18n?.locale || 'ka');
</script>

<template>
    <div class="min-h-screen bg-white text-gray-950">
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <Link :href="route('home', { locale })" class="inline-flex w-fit items-center">
                    <img src="/images/unshoes.jpg" :alt="$t('app_name')" class="h-10 w-auto object-contain" />
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

                <div class="flex items-center gap-3">
                    <a
                        href="https://www.facebook.com/unshoes.geo"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group inline-flex h-10 items-center gap-3 rounded-full border border-gray-200 px-3 text-gray-700 transition hover:border-gray-400 hover:text-black"
                        aria-label="Facebook"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
                            <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.23.2 2.23.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.91h-2.34V22C18.34 21.24 22 17.08 22 12.06Z" />
                        </svg>
                        <span class="h-4 w-px bg-gray-200 transition group-hover:bg-gray-300" aria-hidden="true"></span>
                        <span class="text-xs font-medium tracking-normal text-gray-500 transition group-hover:text-gray-700">
                            {{ $t('footer.facebook_followers') }}
                        </span>
                    </a>

                    <a
                        href="https://www.instagram.com/unshoesgeo"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex size-10 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition hover:border-gray-400 hover:text-black"
                        aria-label="Instagram"
                    >
                        <svg class="size-4" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect width="16" height="16" x="4" y="4" rx="4.5" />
                            <circle cx="12" cy="12" r="3.4" />
                            <path d="M16.9 7.2h.01" stroke-linecap="round" />
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>
