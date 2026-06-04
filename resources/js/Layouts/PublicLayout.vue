<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
</script>

<template>
    <div class="min-h-screen bg-white text-gray-950">
        <header class="border-b border-gray-200">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <Link :href="route('home')" class="text-sm font-semibold uppercase tracking-[0.18em] text-black">
                    Tbilisi Shoe Factory
                </Link>

                <nav class="flex items-center gap-5 text-sm text-gray-700">
                    <Link :href="route('products.index')" class="hover:text-black">Products</Link>
                    <Link :href="route('about')" class="hover:text-black">About</Link>
                    <Link
                        v-if="$page.props.auth?.can_admin"
                        :href="route('admin.dashboard')"
                        class="hover:text-black"
                    >
                        Admin
                    </Link>
                    <Link v-else :href="route('login')" class="hover:text-black">Admin login</Link>
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
            <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 text-sm text-gray-600 sm:grid-cols-3 sm:px-6 lg:px-8">
                <div>
                    <p class="font-medium text-gray-950">Tbilisi Shoe Factory</p>
                    <p class="mt-2">Factory showroom and reservations in Tbilisi, Georgia.</p>
                </div>
                <div>
                    <p class="font-medium text-gray-950">No online checkout</p>
                    <p class="mt-2">Reserve online, inspect and pay physically on-site.</p>
                </div>
                <div>
                    <p class="font-medium text-gray-950">Contact placeholder</p>
                    <p class="mt-2">Tbilisi, Georgia · +995 599 00 00 00</p>
                </div>
            </div>
        </footer>
    </div>
</template>
