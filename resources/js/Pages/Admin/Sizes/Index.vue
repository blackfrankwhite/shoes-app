<script setup>
import Pagination from '@/Components/Pagination.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useTranslations } from '@/composables/useTranslations';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    sizes: {
        type: Object,
        required: true,
    },
});

const { t } = useTranslations();

const destroySize = (size) => {
    if (window.confirm(t('admin.sizes.delete_confirm', { name: size.label }))) {
        router.delete(route('admin.sizes.destroy', size.id));
    }
};
</script>

<template>
    <Head :title="$t('common.sizes')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.attributes') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('common.sizes') }}</h1>
            </div>
            <Link :href="route('admin.sizes.create')" class="inline-flex justify-center border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                {{ $t('admin.sizes.create') }}
            </Link>
        </div>

        <div class="mt-6 space-y-3 md:hidden">
            <article v-for="size in sizes.data" :key="size.id" class="border border-gray-200 bg-white p-4 text-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ size.label }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $t('common.value') }} {{ size.value }} · {{ $t('common.order') }} {{ size.sort_order }}</p>
                    </div>
                    <span class="shrink-0 text-xs text-gray-600">{{ size.is_active ? $t('common.active') : $t('common.inactive') }}</span>
                </div>
                <div class="mt-4 flex justify-end gap-4">
                    <Link :href="route('admin.sizes.edit', size.id)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                    <button type="button" class="text-red-700 hover:text-red-900" @click="destroySize(size)">{{ $t('common.delete') }}</button>
                </div>
            </article>
        </div>

        <div class="mt-8 hidden overflow-hidden border border-gray-200 bg-white md:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">{{ $t('common.name') }}</th>
                        <th class="px-4 py-3">{{ $t('common.value') }}</th>
                        <th class="px-4 py-3">{{ $t('common.order') }}</th>
                        <th class="px-4 py-3">{{ $t('common.status') }}</th>
                        <th class="px-4 py-3 text-right">{{ $t('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="size in sizes.data" :key="size.id">
                        <td class="px-4 py-4 font-medium">{{ size.label }}</td>
                        <td class="px-4 py-4 text-gray-600">{{ size.value }}</td>
                        <td class="px-4 py-4">{{ size.sort_order }}</td>
                        <td class="px-4 py-4">{{ size.is_active ? $t('common.active') : $t('common.inactive') }}</td>
                        <td class="px-4 py-4 text-right">
                            <Link :href="route('admin.sizes.edit', size.id)" class="text-gray-600 hover:text-black">{{ $t('common.edit') }}</Link>
                            <button type="button" class="ml-4 text-red-700 hover:text-red-900" @click="destroySize(size)">{{ $t('common.delete') }}</button>
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
