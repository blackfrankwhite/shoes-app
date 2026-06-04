<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useTranslations } from '@/composables/useTranslations';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Object,
        required: true,
    },
});

const { t } = useTranslations();

const destroyCategory = (category) => {
    if (window.confirm(t('admin.categories.delete_confirm', { name: category.name }))) {
        router.delete(route('admin.categories.destroy', category.slug));
    }
};
</script>

<template>
    <Head :title="$t('common.categories')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.catalog') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('common.categories') }}</h1>
            </div>
            <Link :href="route('admin.categories.create')" class="inline-flex justify-center border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                {{ $t('admin.categories.create') }}
            </Link>
        </div>

        <div class="mt-6 space-y-3 md:hidden">
            <article v-for="category in categories.data" :key="category.id" class="border border-gray-200 bg-white p-4 text-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="break-words font-medium">{{ category.name }}</p>
                        <p class="mt-1 break-all text-xs text-gray-500">{{ category.slug }}</p>
                    </div>
                    <span class="shrink-0 text-xs text-gray-600">{{ category.is_active ? $t('common.active') : $t('common.inactive') }}</span>
                </div>
                <p class="mt-3 text-gray-600">{{ category.products_count }} {{ $t('common.products') }}</p>
                <div class="mt-4 flex justify-end gap-4">
                    <Link :href="route('admin.categories.edit', category.slug)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                    <button type="button" class="text-red-700 hover:text-red-900" @click="destroyCategory(category)">{{ $t('common.delete') }}</button>
                </div>
            </article>
        </div>

        <div class="mt-8 hidden overflow-hidden border border-gray-200 bg-white md:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ $t('common.name') }}</th>
                        <th class="px-4 py-3">{{ $t('common.slug') }}</th>
                        <th class="px-4 py-3">{{ $t('common.products') }}</th>
                        <th class="px-4 py-3">{{ $t('common.status') }}</th>
                        <th class="px-4 py-3 text-right">{{ $t('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="category in categories.data" :key="category.id">
                        <td class="px-4 py-4 font-medium">{{ category.name }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ category.slug }}</td>
                        <td class="px-4 py-4">{{ category.products_count }}</td>
                        <td class="px-4 py-4">{{ category.is_active ? $t('common.active') : $t('common.inactive') }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.categories.edit', category.slug)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                            <button type="button" class="ml-4 text-red-700 hover:text-red-900" @click="destroyCategory(category)">{{ $t('common.delete') }}</button>
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
