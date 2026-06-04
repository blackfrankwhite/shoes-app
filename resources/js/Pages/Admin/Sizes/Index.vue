<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    sizes: {
        type: Object,
        required: true,
    },
});

const destroySize = (size) => {
    if (window.confirm(`Delete ${size.label}?`)) {
        router.delete(route('admin.sizes.destroy', size.id));
    }
};
</script>

<template>
    <Head title="Sizes" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Attributes</p>
                <h1 class="mt-2 text-3xl font-semibold">Sizes</h1>
            </div>
            <Link :href="route('admin.sizes.create')" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                Create size
            </Link>
        </div>

        <div class="mt-8 overflow-hidden border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Label</th>
                        <th class="px-4 py-3">Value</th>
                        <th class="px-4 py-3">Order</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="size in sizes.data" :key="size.id">
                        <td class="px-4 py-4 font-medium">{{ size.label }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ size.value }}</td>
                        <td class="px-4 py-4">{{ size.sort_order }}</td>
                        <td class="px-4 py-4">{{ size.is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.sizes.edit', size.id)" class="text-gray-600 hover:text-black">Edit</Link>
                            <button type="button" class="ml-4 text-red-700 hover:text-red-900" @click="destroySize(size)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <Pagination :links="sizes.links" />
        </div>
    </AdminLayout>
</template>
