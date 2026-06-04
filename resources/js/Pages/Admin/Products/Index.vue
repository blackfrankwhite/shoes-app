<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useTranslations } from '@/composables/useTranslations';
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
    categories: {
        type: Array,
        required: true,
    },
});

const form = reactive({
    search: props.filters.search || '',
    category: props.filters.category || '',
    active: props.filters.active ?? '',
});

const { t } = useTranslations();

const applyFilters = () => {
    router.get(route('admin.products.index'), form, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const destroyProduct = (product) => {
    if (window.confirm(t('admin.products.delete_confirm', { name: product.name }))) {
        router.delete(route('admin.products.destroy', product.slug));
    }
};
</script>

<template>
    <Head :title="$t('common.products')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.catalog') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('common.products') }}</h1>
            </div>
            <Link :href="route('admin.products.create')" class="inline-flex justify-center border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                {{ $t('admin.products.create') }}
            </Link>
        </div>

        <form class="mt-6 grid gap-3 border border-gray-200 bg-white p-4 lg:mt-8 lg:grid-cols-[1fr_220px_160px_auto]" @submit.prevent="applyFilters">
            <input v-model="form.search" type="search" :placeholder="$t('admin.products.search_placeholder')" class="border-gray-300 text-sm focus:border-black focus:ring-black" />
            <select v-model="form.category" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">{{ $t('public.products.all_categories') }}</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">{{ category.name }}</option>
            </select>
            <select v-model="form.active" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">{{ $t('common.any_status') }}</option>
                <option value="1">{{ $t('common.active') }}</option>
                <option value="0">{{ $t('common.inactive') }}</option>
            </select>
            <button type="submit" class="border border-black bg-black px-4 py-2 text-sm font-medium text-white">{{ $t('common.filter') }}</button>
        </form>

        <div class="mt-6 space-y-3 md:hidden">
            <article v-for="product in products.data" :key="product.id" class="border border-gray-200 bg-white p-4">
                <div class="flex gap-3">
                    <img :src="product.image" :alt="product.name" class="h-24 w-20 shrink-0 bg-gray-100 object-cover" />
                    <div class="min-w-0 flex-1">
                        <p class="break-words font-medium">{{ product.name }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ product.sku }}</p>
                        <p class="mt-2 text-sm text-gray-700">{{ product.formatted_price }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ product.category?.name }} · {{ product.stock_quantity }} {{ $t('common.stock') }}</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm">
                    <span :class="product.is_active ? 'text-gray-900' : 'text-gray-500'">
                        {{ product.is_active ? $t('common.active') : $t('common.inactive') }}
                    </span>
                    <div class="flex gap-4">
                        <Link :href="route('admin.products.show', product.slug)" class="text-gray-600 hover:text-black">{{ $t('common.view') }}</Link>
                        <Link :href="route('admin.products.edit', product.slug)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                        <button type="button" class="text-red-700 hover:text-red-900" @click="destroyProduct(product)">{{ $t('common.delete') }}</button>
                    </div>
                </div>
            </article>
        </div>

        <div class="mt-6 hidden overflow-hidden border border-gray-200 bg-white md:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ $t('common.product') }}</th>
                        <th class="px-4 py-3">{{ $t('common.category') }}</th>
                        <th class="px-4 py-3">{{ $t('common.price') }}</th>
                        <th class="px-4 py-3">{{ $t('common.stock') }}</th>
                        <th class="px-4 py-3">{{ $t('common.status') }}</th>
                        <th class="px-4 py-3 text-right">{{ $t('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="product in products.data" :key="product.id">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img :src="product.image" :alt="product.name" class="h-16 w-14 bg-gray-100 object-cover" />
                                <div>
                                    <p class="font-medium">{{ product.name }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ product.sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-gray-600">{{ product.category?.name }}</td>
                        <td class="px-4 py-4">{{ product.formatted_price }}</td>
                        <td class="px-4 py-4">{{ product.stock_quantity }}</td>
                        <td class="px-4 py-4">{{ product.is_active ? $t('common.active') : $t('common.inactive') }}</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end gap-3">
                                <Link :href="route('admin.products.show', product.slug)" class="text-gray-600 hover:text-black">{{ $t('common.view') }}</Link>
                                <Link :href="route('admin.products.edit', product.slug)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                                <button type="button" class="text-red-700 hover:text-red-900" @click="destroyProduct(product)">{{ $t('common.delete') }}</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <Pagination :links="products.links" />
        </div>
    </AdminLayout>
</template>
