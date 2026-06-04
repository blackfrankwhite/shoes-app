<script setup>
import Pagination from '@/Components/Pagination.vue';
import ProductCard from '@/Components/Public/ProductCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const locale = computed(() => page.props.i18n?.locale || 'ka');

const form = reactive({
    search: props.filters.search || '',
    category: props.filters.category || '',
    sex: props.filters.sex || '',
    size: props.filters.size || '',
    color: props.filters.color || '',
    min_price: props.filters.min_price || '',
    max_price: props.filters.max_price || '',
});

const activeFilterCount = computed(() =>
    Object.values(form).filter((value) => value !== '' && value !== null && value !== undefined).length,
);

const applyFilters = () => {
    router.get(route('products.index', { locale: locale.value }), form, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head :title="$t('public.products.title')" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <div class="flex flex-col gap-3 border-b border-gray-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('public.products.eyebrow') }}</p>
                    <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('common.products') }}</h1>
                </div>
                <p class="text-sm text-gray-600">{{ $t('public.products.results', { count: products.total }) }}</p>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-[260px_1fr]">
                <aside class="self-start lg:sticky lg:top-24">
                    <details class="border border-gray-200 bg-white lg:hidden">
                        <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 text-sm font-medium">
                            <span>{{ $t('common.filter') }}</span>
                            <span v-if="activeFilterCount" class="rounded-full bg-black px-2 py-0.5 text-xs text-white">{{ activeFilterCount }}</span>
                        </summary>

                        <form class="space-y-5 border-t border-gray-200 p-4" @submit.prevent="applyFilters">
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.search') }}</label>
                                <input
                                    v-model="form.search"
                                    type="search"
                                    :placeholder="$t('public.products.search_placeholder')"
                                    class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black"
                                />
                            </div>

                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.category') }}</label>
                                <select v-model="form.category" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                    <option value="">{{ $t('public.products.all_categories') }}</option>
                                    <option v-for="category in options.categories" :key="category.id" :value="category.slug">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.sex') }}</label>
                                <select v-model="form.sex" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                    <option value="">{{ $t('common.all') }}</option>
                                    <option v-for="sex in options.sexes" :key="sex" :value="sex">
                                        {{ $t(`sexes.${sex}`) }}
                                    </option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.size') }}</label>
                                    <select v-model="form.size" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                        <option value="">{{ $t('public.products.all_sizes') }}</option>
                                        <option v-for="size in options.sizes" :key="size.id" :value="size.value">
                                            {{ size.label }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.color') }}</label>
                                    <select v-model="form.color" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                        <option value="">{{ $t('public.products.all_colors') }}</option>
                                        <option v-for="color in options.colors" :key="color.id" :value="color.slug">
                                            {{ color.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('public.products.min') }}</label>
                                    <input v-model="form.min_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                                </div>
                                <div>
                                    <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('public.products.max') }}</label>
                                    <input v-model="form.max_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <button type="submit" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                                    {{ $t('common.apply') }}
                                </button>
                                <Link :href="route('products.index', { locale })" class="border border-gray-300 px-4 py-3 text-center text-sm font-medium text-gray-800">
                                    {{ $t('common.reset') }}
                                </Link>
                            </div>
                        </form>
                    </details>

                    <form class="hidden space-y-5 border border-gray-200 bg-white p-4 lg:block" @submit.prevent="applyFilters">
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.search') }}</label>
                            <input
                                v-model="form.search"
                                type="search"
                                :placeholder="$t('public.products.search_placeholder')"
                                class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black"
                            />
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.category') }}</label>
                            <select v-model="form.category" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">{{ $t('public.products.all_categories') }}</option>
                                <option v-for="category in options.categories" :key="category.id" :value="category.slug">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.sex') }}</label>
                            <select v-model="form.sex" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">{{ $t('common.all') }}</option>
                                <option v-for="sex in options.sexes" :key="sex" :value="sex">
                                    {{ $t(`sexes.${sex}`) }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.size') }}</label>
                            <select v-model="form.size" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">{{ $t('public.products.all_sizes') }}</option>
                                <option v-for="size in options.sizes" :key="size.id" :value="size.value">
                                    {{ size.label }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('common.color') }}</label>
                            <select v-model="form.color" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">{{ $t('public.products.all_colors') }}</option>
                                <option v-for="color in options.colors" :key="color.id" :value="color.slug">
                                    {{ color.name }}
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('public.products.min') }}</label>
                                <input v-model="form.min_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            </div>
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $t('public.products.max') }}</label>
                                <input v-model="form.max_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                                {{ $t('common.apply') }}
                            </button>
                            <Link :href="route('products.index', { locale })" class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-800">
                                {{ $t('common.reset') }}
                            </Link>
                        </div>
                    </form>
                </aside>

                <div>
                    <div v-if="products.data.length" class="grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-5 sm:gap-y-10 xl:grid-cols-3">
                        <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                    </div>
                    <div v-else class="border border-gray-200 p-10 text-center text-sm text-gray-600">
                        {{ $t('public.products.empty') }}
                    </div>

                    <div class="mt-10">
                        <Pagination :links="products.links" />
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
