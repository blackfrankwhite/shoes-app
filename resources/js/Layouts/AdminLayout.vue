<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);

const nav = [
    ['Dashboard', 'admin.dashboard'],
    ['Products', 'admin.products.index'],
    ['Categories', 'admin.categories.index'],
    ['Sizes', 'admin.sizes.index'],
    ['Colors', 'admin.colors.index'],
    ['Inquiries', 'admin.inquiries.index'],
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-950">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <Link :href="route('admin.dashboard')" class="text-sm font-semibold uppercase tracking-[0.18em]">
                        Factory Admin
                    </Link>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <Link :href="route('home')" class="hover:text-black">Storefront</Link>
                        <Link :href="route('profile.edit')" class="hover:text-black">
                            {{ $page.props.auth.user?.name }}
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="hover:text-black">
                            Log out
                        </Link>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto text-sm">
                    <Link
                        v-for="[label, routeName] in nav"
                        :key="routeName"
                        :href="route(routeName)"
                        :class="[
                            'whitespace-nowrap border px-3 py-2',
                            route().current(`${routeName.split('.').slice(0, 2).join('.')}*`) || route().current(routeName)
                                ? 'border-black bg-black text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:border-black hover:text-black',
                        ]"
                    >
                        {{ label }}
                    </Link>
                </nav>
            </div>
        </header>

        <div v-if="flashSuccess" class="border-b border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm text-gray-800 sm:px-6 lg:px-8">
                {{ flashSuccess }}
            </div>
        </div>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
