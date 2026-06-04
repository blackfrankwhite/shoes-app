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
    <Head :title="$t('public.home.title')" />

    <PublicLayout>
        <section
            class="relative min-h-[520px] border-b border-gray-200 bg-gray-100 bg-cover bg-center sm:min-h-[58vh]"
            :style="{ backgroundImage: `linear-gradient(90deg, rgba(255,255,255,.92), rgba(255,255,255,.42)), url(${heroImage})` }"
        >
            <div class="mx-auto flex min-h-[520px] max-w-7xl items-center px-4 py-12 sm:min-h-[58vh] sm:px-6 sm:py-16 lg:px-8">
                <div class="max-w-xl">
                    <p class="text-xs font-medium uppercase tracking-[0.16em] text-gray-600 sm:tracking-[0.22em]">{{ $t('public.home.eyebrow') }}</p>
                    <h1 class="mt-5 text-3xl font-semibold tracking-normal text-black sm:text-6xl">
                        {{ $t('public.home.headline') }}
                    </h1>
                    <p class="mt-6 text-base leading-7 text-gray-700 sm:text-lg">
                        {{ $t('public.home.intro') }}
                    </p>
                    <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap">
                        <Link :href="route('products.index', { locale: $page.props.i18n.locale })" class="inline-flex justify-center border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                            {{ $t('public.home.browse') }}
                        </Link>
                        <Link :href="route('about', { locale: $page.props.i18n.locale })" class="inline-flex justify-center border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-900">
                            {{ $t('public.home.factory_info') }}
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('public.home.categories_eyebrow') }}</p>
                    <h2 class="mt-2 text-2xl font-semibold">{{ $t('public.home.categories_title') }}</h2>
                </div>
                <Link :href="route('products.index', { locale: $page.props.i18n.locale })" class="text-sm text-gray-700 hover:text-black">{{ $t('common.view_all') }}</Link>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('products.index', { locale: $page.props.i18n.locale, category: category.slug })"
                    class="border border-gray-200 p-4 transition hover:border-black sm:p-5"
                >
                    <h3 class="font-medium">{{ category.name }}</h3>
                    <p class="mt-2 min-h-10 text-sm leading-5 text-gray-600">{{ category.description }}</p>
                    <p class="mt-4 text-xs uppercase tracking-wide text-gray-500">{{ $t('public.home.products_count', { count: category.products_count }) }}</p>
                </Link>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4 border-t border-gray-200 pt-12">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('public.home.featured_eyebrow') }}</p>
                    <h2 class="mt-2 text-2xl font-semibold">{{ $t('public.home.featured_title') }}</h2>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-5 sm:gap-y-10 lg:grid-cols-4">
                <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
            </div>
        </section>
    </PublicLayout>
</template>
