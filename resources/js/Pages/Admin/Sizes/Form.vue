<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    size: {
        type: Object,
        default: null,
    },
});

const isEdit = computed(() => Boolean(props.size));
const form = useForm({
    label: props.size?.label || '',
    value: props.size?.value || '',
    sort_order: props.size?.sort_order || 0,
    is_active: props.size?.is_active ?? true,
});

const submit = () => {
    if (isEdit.value) {
        form.put(route('admin.sizes.update', props.size.id));
        return;
    }

    form.post(route('admin.sizes.store'));
};
</script>

<template>
    <Head :title="isEdit ? $t('admin.sizes.edit') : $t('admin.sizes.create')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.attributes') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ isEdit ? $t('admin.sizes.edit') : $t('admin.sizes.create') }}</h1>
            </div>
            <Link :href="route('admin.sizes.index')" class="text-sm text-gray-600 hover:text-black">{{ $t('common.back') }}</Link>
        </div>

        <form class="mt-6 max-w-xl space-y-5 border border-gray-200 bg-white p-4 sm:mt-8 sm:p-5" @submit.prevent="submit">
            <div>
                <label class="text-sm font-medium">{{ $t('common.name') }}</label>
                <input v-model="form.label" type="text" :placeholder="$t('admin.sizes.label_placeholder')" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.label" class="mt-1 text-sm text-red-600">{{ form.errors.label }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('common.value') }}</label>
                <input v-model="form.value" type="text" :placeholder="$t('admin.sizes.value_placeholder')" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.value" class="mt-1 text-sm text-red-600">{{ form.errors.value }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('admin.sizes.sort_order') }}</label>
                <input v-model="form.sort_order" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">{{ form.errors.sort_order }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                {{ $t('common.active') }}
            </label>
            <button type="submit" class="w-full border border-black bg-black px-6 py-3 text-sm font-medium text-white sm:w-auto">
                {{ $t('admin.sizes.save') }}
            </button>
        </form>
    </AdminLayout>
</template>
