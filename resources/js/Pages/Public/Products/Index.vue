<script setup>
import Pagination from '@/Components/Pagination.vue';
import ProductCard from '@/Components/Public/ProductCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

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

const form = reactive({
    search: props.filters.search || '',
    category: props.filters.category || '',
    sex: props.filters.sex || '',
    size: props.filters.size || '',
    color: props.filters.color || '',
    min_price: props.filters.min_price || '',
    max_price: props.filters.max_price || '',
});

const applyFilters = () => {
    router.get(route('products.index'), form, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Products" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 border-b border-gray-200 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Current factory stock</p>
                    <h1 class="mt-2 text-3xl font-semibold">Products</h1>
                </div>
                <p class="text-sm text-gray-600">{{ products.total }} results</p>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-[260px_1fr]">
                <aside class="self-start lg:sticky lg:top-6">
                    <form class="space-y-5 border border-gray-200 p-4" @submit.prevent="applyFilters">
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Search</label>
                            <input
                                v-model="form.search"
                                type="search"
                                placeholder="Name or SKU"
                                class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black"
                            />
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Category</label>
                            <select v-model="form.category" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">All categories</option>
                                <option v-for="category in options.categories" :key="category.id" :value="category.slug">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Sex</label>
                            <select v-model="form.sex" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">All</option>
                                <option v-for="sex in options.sexes" :key="sex" :value="sex">
                                    {{ sex }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Size</label>
                            <select v-model="form.size" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">All sizes</option>
                                <option v-for="size in options.sizes" :key="size.id" :value="size.value">
                                    {{ size.label }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Color</label>
                            <select v-model="form.color" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option value="">All colors</option>
                                <option v-for="color in options.colors" :key="color.id" :value="color.slug">
                                    {{ color.name }}
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Min</label>
                                <input v-model="form.min_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            </div>
                            <div>
                                <label class="text-xs font-medium uppercase tracking-wide text-gray-500">Max</label>
                                <input v-model="form.max_price" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                                Apply
                            </button>
                            <Link :href="route('products.index')" class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-800">
                                Reset
                            </Link>
                        </div>
                    </form>
                </aside>

                <div>
                    <div v-if="products.data.length" class="grid gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
                        <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
                    </div>
                    <div v-else class="border border-gray-200 p-10 text-center text-sm text-gray-600">
                        No products matched these filters.
                    </div>

                    <div class="mt-10">
                        <Pagination :links="products.links" />
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
