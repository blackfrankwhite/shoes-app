<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        required: true,
    },
    recentInquiries: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Factory admin</p>
                <h1 class="mt-2 text-3xl font-semibold">Dashboard</h1>
            </div>
            <Link :href="route('admin.products.create')" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                New product
            </Link>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="(value, label) in stats" :key="label" class="border border-gray-200 bg-white p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ label.replaceAll('_', ' ') }}</p>
                <p class="mt-3 text-3xl font-semibold">{{ value }}</p>
            </div>
        </div>

        <section class="mt-10 border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 p-5">
                <h2 class="text-lg font-semibold">Recent inquiries</h2>
                <Link :href="route('admin.inquiries.index')" class="text-sm text-gray-600 hover:text-black">View all</Link>
            </div>
            <div class="divide-y divide-gray-200">
                <Link
                    v-for="inquiry in recentInquiries"
                    :key="inquiry.id"
                    :href="route('admin.inquiries.show', inquiry.id)"
                    class="grid gap-2 p-5 text-sm hover:bg-gray-50 sm:grid-cols-[1fr_160px_120px]"
                >
                    <div>
                        <p class="font-medium">{{ inquiry.name }}</p>
                        <p class="mt-1 text-gray-600">{{ inquiry.product }} · {{ inquiry.phone }}</p>
                    </div>
                    <p class="text-gray-600">{{ inquiry.created_at }}</p>
                    <p class="capitalize text-gray-900">{{ inquiry.status }}</p>
                </Link>
            </div>
        </section>
    </AdminLayout>
</template>
