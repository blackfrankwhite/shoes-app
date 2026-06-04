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
    <Head :title="isEdit ? 'Edit Size' : 'Create Size'" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Attributes</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ isEdit ? 'Edit size' : 'Create size' }}</h1>
            </div>
            <Link :href="route('admin.sizes.index')" class="text-sm text-gray-600 hover:text-black">Back</Link>
        </div>

        <form class="mt-8 max-w-xl space-y-5 border border-gray-200 bg-white p-5" @submit.prevent="submit">
            <div>
                <label class="text-sm font-medium">Label</label>
                <input v-model="form.label" type="text" placeholder="EU 42" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.label" class="mt-1 text-sm text-red-600">{{ form.errors.label }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">Value</label>
                <input v-model="form.value" type="text" placeholder="42" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.value" class="mt-1 text-sm text-red-600">{{ form.errors.value }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">Sort order</label>
                <input v-model="form.sort_order" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">{{ form.errors.sort_order }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                Active
            </label>
            <button type="submit" class="border border-black bg-black px-6 py-3 text-sm font-medium text-white">
                Save size
            </button>
        </form>
    </AdminLayout>
</template>
