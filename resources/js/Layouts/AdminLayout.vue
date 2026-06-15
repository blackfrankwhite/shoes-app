<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);

const nav = [
    ['admin.layout.dashboard', 'admin.dashboard'],
    ['admin.layout.storefront', 'admin.storefront.edit'],
    ['admin.layout.products', 'admin.products.index'],
    ['admin.layout.categories', 'admin.categories.index'],
    ['admin.layout.sizes', 'admin.sizes.index'],
    ['admin.layout.colors', 'admin.colors.index'],
    ['admin.layout.inquiries', 'admin.inquiries.index'],
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-950">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <Link :href="route('admin.dashboard')" class="break-words text-sm font-semibold uppercase tracking-[0.14em] sm:tracking-[0.18em]">
                        {{ $t('admin.layout.title') }}
                    </Link>
                    <div class="flex items-center gap-4 overflow-x-auto whitespace-nowrap text-sm text-gray-600">
                        <Link :href="route('home', { locale: 'ka' })" class="py-1 hover:text-black">{{ $t('nav.storefront') }}</Link>
                        <Link :href="route('profile.edit')" class="py-1 hover:text-black">
                            {{ $page.props.auth.user?.name }}
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="py-1 hover:text-black">
                            {{ $t('nav.logout') }}
                        </Link>
                    </div>
                </div>

                <nav class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 text-sm sm:mx-0 sm:px-0">
                    <Link
                        v-for="[labelKey, routeName] in nav"
                        :key="routeName"
                        :href="route(routeName)"
                        :class="[
                            'whitespace-nowrap border px-3 py-2.5',
                            route().current(`${routeName.split('.').slice(0, 2).join('.')}*`) || route().current(routeName)
                                ? 'border-black bg-black text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:border-black hover:text-black',
                        ]"
                    >
                        {{ $t(labelKey) }}
                    </Link>
                </nav>
            </div>
        </header>

        <div v-if="flashSuccess" class="border-b border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm text-gray-800 sm:px-6 lg:px-8">
                {{ flashSuccess }}
            </div>
        </div>

        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">
            <slot />
        </main>
    </div>
</template>
