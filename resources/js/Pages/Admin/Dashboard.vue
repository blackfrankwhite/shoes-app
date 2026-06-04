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
    <Head :title="$t('admin.dashboard.title')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.dashboard.eyebrow') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('admin.dashboard.title') }}</h1>
            </div>
            <Link :href="route('admin.products.create')" class="inline-flex justify-center border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                {{ $t('admin.dashboard.new_product') }}
            </Link>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3 sm:mt-8 sm:gap-4 lg:grid-cols-4">
            <div v-for="(value, label) in stats" :key="label" class="border border-gray-200 bg-white p-4 sm:p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ $t(`admin.dashboard.${label}`) }}</p>
                <p class="mt-3 text-2xl font-semibold sm:text-3xl">{{ value }}</p>
            </div>
        </div>

        <section class="mt-10 border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 sm:p-5">
                <h2 class="text-lg font-semibold">{{ $t('admin.dashboard.recent_inquiries') }}</h2>
                <Link :href="route('admin.inquiries.index')" class="text-sm text-gray-600 hover:text-black">{{ $t('common.view_all') }}</Link>
            </div>
            <div class="divide-y divide-gray-200">
                <Link
                    v-for="inquiry in recentInquiries"
                    :key="inquiry.id"
                    :href="route('admin.inquiries.show', inquiry.id)"
                    class="grid gap-2 p-4 text-sm hover:bg-gray-50 sm:grid-cols-[1fr_160px_120px] sm:p-5"
                >
                    <div>
                        <p class="font-medium">{{ inquiry.name }}</p>
                        <p class="mt-1 text-gray-600">{{ inquiry.product }} · {{ inquiry.phone }}</p>
                    </div>
                    <p class="text-gray-600">{{ inquiry.created_at }}</p>
                    <p class="capitalize text-gray-900">{{ $t(`status.${inquiry.status}`) }}</p>
                </Link>
            </div>
        </section>
    </AdminLayout>
</template>
