<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    colors: {
        type: Object,
        required: true,
    },
});

const destroyColor = (color) => {
    if (window.confirm(`Delete ${color.name}?`)) {
        router.delete(route('admin.colors.destroy', color.slug));
    }
};
</script>

<template>
    <Head title="Colors" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Attributes</p>
                <h1 class="mt-2 text-3xl font-semibold">Colors</h1>
            </div>
            <Link :href="route('admin.colors.create')" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                Create color
            </Link>
        </div>

        <div class="mt-8 overflow-hidden border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Hex</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="color in colors.data" :key="color.id">
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-2 font-medium">
                                <span class="h-4 w-4 border border-gray-300" :style="{ backgroundColor: color.hex_code || '#ddd' }" />
                                {{ color.name }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-600">{{ color.slug }}</td>
                        <td class="px-4 py-4">{{ color.hex_code }}</td>
                        <td class="px-4 py-4">{{ color.is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.colors.edit', color.slug)" class="text-gray-600 hover:text-black">Edit</Link>
                            <button type="button" class="ml-4 text-red-700 hover:text-red-900" @click="destroyColor(color)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <Pagination :links="colors.links" />
        </div>
    </AdminLayout>
</template>
