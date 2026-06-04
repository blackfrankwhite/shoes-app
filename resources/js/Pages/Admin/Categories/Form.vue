<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    category: {
        type: Object,
        default: null,
    },
});

const isEdit = computed(() => Boolean(props.category));
const form = useForm({
    name: props.category?.name || '',
    slug: props.category?.slug || '',
    description: props.category?.description || '',
    is_active: props.category?.is_active ?? true,
});

const submit = () => {
    if (isEdit.value) {
        form.put(route('admin.categories.update', props.category.slug));
        return;
    }

    form.post(route('admin.categories.store'));
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Category' : 'Create Category'" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Catalog</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ isEdit ? 'Edit category' : 'Create category' }}</h1>
            </div>
            <Link :href="route('admin.categories.index')" class="text-sm text-gray-600 hover:text-black">Back</Link>
        </div>

        <form class="mt-8 max-w-2xl space-y-5 border border-gray-200 bg-white p-5" @submit.prevent="submit">
            <div>
                <label class="text-sm font-medium">Name</label>
                <input v-model="form.name" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">Slug</label>
                <input v-model="form.slug" type="text" placeholder="Auto-generated if blank" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">Description</label>
                <textarea v-model="form.description" rows="4" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                Active
            </label>
            <button type="submit" class="border border-black bg-black px-6 py-3 text-sm font-medium text-white">
                Save category
            </button>
        </form>
    </AdminLayout>
</template>
