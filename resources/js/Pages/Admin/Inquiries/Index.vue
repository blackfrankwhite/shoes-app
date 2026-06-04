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
    <Head :title="$t('admin.inquiries.title')" />

    <AdminLayout>
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.reservations') }}</p>
            <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('admin.inquiries.title') }}</h1>
        </div>

        <form class="mt-6 grid gap-3 border border-gray-200 bg-white p-4 md:grid-cols-[1fr_220px_auto] lg:mt-8" @submit.prevent="applyFilters">
            <input v-model="form.search" type="search" :placeholder="$t('admin.inquiries.search_placeholder')" class="border-gray-300 text-sm focus:border-black focus:ring-black" />
            <select v-model="form.status" class="border-gray-300 text-sm focus:border-black focus:ring-black">
                <option value="">{{ $t('admin.inquiries.all_statuses') }}</option>
                <option v-for="status in statuses" :key="status" :value="status">{{ $t(`status.${status}`) }}</option>
            </select>
            <button type="submit" class="border border-black bg-black px-4 py-2 text-sm font-medium text-white">{{ $t('common.filter') }}</button>
        </form>

        <div class="mt-6 space-y-3 md:hidden">
            <Link
                v-for="inquiry in inquiries.data"
                :key="inquiry.id"
                :href="route('admin.inquiries.show', inquiry.id)"
                class="block border border-gray-200 bg-white p-4 text-sm"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="break-words font-medium">{{ inquiry.name }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ inquiry.phone }}</p>
                    </div>
                    <span class="shrink-0 text-xs text-gray-600">{{ $t(`status.${inquiry.status}`) }}</span>
                </div>
                <p class="mt-3 break-words text-gray-900">{{ inquiry.product?.name }}</p>
                <p class="mt-1 text-gray-600">{{ inquiry.size }} · {{ inquiry.color }} · {{ $t('common.quantity') }} {{ inquiry.quantity }}</p>
                <p class="mt-3 text-xs text-gray-500">{{ inquiry.created_at }}</p>
            </Link>
        </div>

        <div class="mt-6 hidden overflow-hidden border border-gray-200 bg-white md:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ $t('common.customer') }}</th>
                        <th class="px-4 py-3">{{ $t('common.product') }}</th>
                        <th class="px-4 py-3">{{ $t('common.selection') }}</th>
                        <th class="px-4 py-3">{{ $t('common.status') }}</th>
                        <th class="px-4 py-3">{{ $t('common.created') }}</th>
                        <th class="px-4 py-3 text-right">{{ $t('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="inquiry in inquiries.data" :key="inquiry.id">
                        <td class="px-4 py-4">
                            <p class="font-medium">{{ inquiry.name }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ inquiry.phone }}</p>
                        </td>
                        <td class="px-4 py-4">{{ inquiry.product?.name }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ inquiry.size }} · {{ inquiry.color }} · {{ $t('common.quantity') }} {{ inquiry.quantity }}</td>
                        <td class="px-4 py-4 capitalize">{{ $t(`status.${inquiry.status}`) }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ inquiry.created_at }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.inquiries.show', inquiry.id)" class="text-gray-600 hover:text-black">{{ $t('common.open') }}</Link>
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
