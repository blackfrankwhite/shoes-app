<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
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

const applyFilters = () => {
    router.get(route('admin.products.index'), form, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const destroyProduct = (product) => {
    if (window.confirm(`Delete ${product.name}?`)) {
        router.delete(route('admin.products.destroy', product.slug));
    }
};
</script>

<template>
    <Head title="Products" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Catalog</p>
                <h1 class="mt-2 text-3xl font-semibold">Products</h1>
            </div>
            <Link :href="route('admin.products.create')" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                Create product
            </Link>
        </div>

        <form class="mt-8 grid gap-3 border border-gray-200 bg-white p-4 lg:grid-cols-[1fr_220px_160px_auto]" @submit.prevent="applyFilters">
            <input v-model="form.search" type="search" placeholder="Search name or SKU" class="border-gray-300 text-sm focus:border-black focus:ring-black" />
            <select v-model="form.category" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">All categories</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">{{ category.name }}</option>
            </select>
            <select v-model="form.active" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">Any status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <button type="submit" class="border border-black bg-black px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>

        <div class="mt-6 overflow-hidden border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
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
                        <td class="px-4 py-4">{{ product.is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex justify-end gap-3">
                                <Link :href="route('admin.products.show', product.slug)" class="text-gray-600 hover:text-black">View</Link>
                                <Link :href="route('admin.products.edit', product.slug)" class="text-gray-600 hover:text-black">Edit</Link>
                                <button type="button" class="text-red-700 hover:text-red-900" @click="destroyProduct(product)">Delete</button>
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
