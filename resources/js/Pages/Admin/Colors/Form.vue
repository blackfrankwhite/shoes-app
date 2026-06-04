<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    color: {
        type: Object,
        default: null,
    },
});

const isEdit = computed(() => Boolean(props.color));
const form = useForm({
    name: props.color?.name || '',
    slug: props.color?.slug || '',
    hex_code: props.color?.hex_code || '#111111',
    is_active: props.color?.is_active ?? true,
});

const submit = () => {
    if (isEdit.value) {
        form.put(route('admin.colors.update', props.color.slug));
        return;
    }

    form.post(route('admin.colors.store'));
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Color' : 'Create Color'" />

    <AdminLayout>
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Attributes</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ isEdit ? 'Edit color' : 'Create color' }}</h1>
            </div>
            <Link :href="route('admin.colors.index')" class="text-sm text-gray-600 hover:text-black">Back</Link>
        </div>

        <form class="mt-8 max-w-xl space-y-5 border border-gray-200 bg-white p-5" @submit.prevent="submit">
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
                <label class="text-sm font-medium">Swatch</label>
                <div class="mt-2 flex gap-3">
                    <input v-model="form.hex_code" type="color" class="h-10 w-14 border border-gray-300 bg-white" />
                    <input v-model="form.hex_code" type="text" class="w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                </div>
                <p v-if="form.errors.hex_code" class="mt-1 text-sm text-red-600">{{ form.errors.hex_code }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                Active
            </label>
            <button type="submit" class="border border-black bg-black px-6 py-3 text-sm font-medium text-white">
                Save color
            </button>
        </form>
    </AdminLayout>
</template>
