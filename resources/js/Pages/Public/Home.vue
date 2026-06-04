<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import ProductCard from '@/Components/Public/ProductCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    featuredProducts: {
        type: Array,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
});

const heroImage = computed(() => props.featuredProducts[0]?.image || '/images/placeholders/shoe-1.png');
</script>

<template>
    <Head title="Storefront" />

    <PublicLayout>
        <section
            class="relative min-h-[58vh] border-b border-gray-200 bg-gray-100 bg-cover bg-center"
            :style="{ backgroundImage: `linear-gradient(90deg, rgba(255,255,255,.92), rgba(255,255,255,.42)), url(${heroImage})` }"
        >
            <div class="mx-auto flex min-h-[58vh] max-w-7xl items-center px-4 py-16 sm:px-6 lg:px-8">
                <div class="max-w-xl">
                    <p class="text-xs font-medium uppercase tracking-[0.22em] text-gray-600">Factory showroom · Tbilisi</p>
                    <h1 class="mt-5 text-4xl font-semibold tracking-normal text-black sm:text-6xl">
                        Tbilisi Shoe Factory
                    </h1>
                    <p class="mt-6 text-base leading-7 text-gray-700 sm:text-lg">
                        Browse current factory stock, reserve a pair, and pay in person after inspection at the showroom.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link :href="route('products.index')" class="border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                            Browse products
                        </Link>
                        <Link :href="route('about')" class="border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-900">
                            Factory info
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Categories</p>
                    <h2 class="mt-2 text-2xl font-semibold">Factory lines</h2>
                </div>
                <Link :href="route('products.index')" class="text-sm text-gray-700 hover:text-black">View all</Link>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('products.index', { category: category.slug })"
                    class="border border-gray-200 p-5 transition hover:border-black"
                >
                    <h3 class="font-medium">{{ category.name }}</h3>
                    <p class="mt-2 min-h-10 text-sm leading-5 text-gray-600">{{ category.description }}</p>
                    <p class="mt-4 text-xs uppercase tracking-wide text-gray-500">{{ category.products_count }} products</p>
                </Link>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4 border-t border-gray-200 pt-12">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Featured</p>
                    <h2 class="mt-2 text-2xl font-semibold">Available for reservation</h2>
                </div>
            </div>

            <div class="mt-6 grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
            </div>
        </section>
    </PublicLayout>
</template>
