<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="About" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_.9fr]">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Tbilisi, Georgia</p>
                    <h1 class="mt-4 text-4xl font-semibold">Factory showroom and reservation desk</h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-gray-700">
                        This placeholder page is ready for the factory address, working hours, phone number, production story, and Georgian-language copy. Customers can reserve a pair online and complete payment physically at the factory.
                    </p>
                    <Link :href="route('products.index')" class="mt-8 inline-flex border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                        Browse stock
                    </Link>
                </div>

                <div class="border border-gray-200 p-6">
                    <dl class="space-y-5 text-sm">
                        <div>
                            <dt class="font-medium text-gray-950">Address</dt>
                            <dd class="mt-1 text-gray-600">Tbilisi factory address placeholder</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">Phone</dt>
                            <dd class="mt-1 text-gray-600">+995 599 00 00 00</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">Hours</dt>
                            <dd class="mt-1 text-gray-600">Monday to Saturday, 10:00-19:00</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">Payment</dt>
                            <dd class="mt-1 text-gray-600">No online payments. Customers pay on-site after confirmation.</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
