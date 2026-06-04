<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    inquiries: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Array,
        required: true,
    },
});

const form = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
});

const applyFilters = () => {
    router.get(route('admin.inquiries.index'), form, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Inquiries" />

    <AdminLayout>
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Reservations</p>
            <h1 class="mt-2 text-3xl font-semibold">Inquiries</h1>
        </div>

        <form class="mt-8 grid gap-3 border border-gray-200 bg-white p-4 md:grid-cols-[1fr_220px_auto]" @submit.prevent="applyFilters">
            <input v-model="form.search" type="search" placeholder="Search name, phone, product, SKU" class="border-gray-300 text-sm focus:border-black focus:ring-black" />
            <select v-model="form.status" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">All statuses</option>
                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
            </select>
            <button type="submit" class="border border-black bg-black px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>

        <div class="mt-6 overflow-hidden border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Selection</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="inquiry in inquiries.data" :key="inquiry.id">
                        <td class="px-4 py-4">
                            <p class="font-medium">{{ inquiry.name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ inquiry.phone }}</p>
                        </td>
                        <td class="px-4 py-4">{{ inquiry.product?.name }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ inquiry.size }} · {{ inquiry.color }} · Qty {{ inquiry.quantity }}</td>
                        <td class="px-4 py-4 capitalize">{{ inquiry.status }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ inquiry.created_at }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.inquiries.show', inquiry.id)" class="text-gray-600 hover:text-black">Open</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <Pagination :links="inquiries.links" />
        </div>
    </AdminLayout>
</template>
