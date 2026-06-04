<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Object,
        required: true,
    },
});

const destroyCategory = (category) => {
    if (window.confirm(`Delete ${category.name}? Products will remain without a category.`)) {
        router.delete(route('admin.categories.destroy', category.slug));
    }
};
</script>

<template>
    <Head title="Categories" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Catalog</p>
                <h1 class="mt-2 text-3xl font-semibold">Categories</h1>
            </div>
            <Link :href="route('admin.categories.create')" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                Create category
            </Link>
        </div>

        <div class="mt-8 overflow-hidden border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Products</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="category in categories.data" :key="category.id">
                        <td class="px-4 py-4 font-medium">{{ category.name }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ category.slug }}</td>
                        <td class="px-4 py-4">{{ category.products_count }}</td>
                        <td class="px-4 py-4">{{ category.is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.categories.edit', category.slug)" class="text-gray-600 hover:text-black">Edit</Link>
                            <button type="button" class="ml-4 text-red-700 hover:text-red-900" @click="destroyCategory(category)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <Pagination :links="categories.links" />
        </div>
    </AdminLayout>
</template>
