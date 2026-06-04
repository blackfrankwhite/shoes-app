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
    name_translations: {
        en: props.color?.name_translations?.en || '',
        ru: props.color?.name_translations?.ru || '',
    },
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
    <Head :title="isEdit ? $t('admin.colors.edit') : $t('admin.colors.create')" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.attributes') }}</p>
                <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ isEdit ? $t('admin.colors.edit') : $t('admin.colors.create') }}</h1>
            </div>
            <Link :href="route('admin.colors.index')" class="text-sm text-gray-600 hover:text-black">{{ $t('common.back') }}</Link>
        </div>

        <form class="mt-6 max-w-xl space-y-5 border border-gray-200 bg-white p-4 sm:mt-8 sm:p-5" @submit.prevent="submit">
            <div>
                <label class="text-sm font-medium">{{ $t('common.name') }}</label>
                <input v-model="form.name" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>
            <div class="border-t border-gray-200 pt-5">
                <h2 class="text-lg font-semibold">{{ $t('common.translations') }}</h2>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('common.name') }} · {{ $t('locales.en') }}</label>
                <input v-model="form.name_translations.en" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors['name_translations.en']" class="mt-1 text-sm text-red-600">{{ form.errors['name_translations.en'] }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('common.name') }} · {{ $t('locales.ru') }}</label>
                <input v-model="form.name_translations.ru" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors['name_translations.ru']" class="mt-1 text-sm text-red-600">{{ form.errors['name_translations.ru'] }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('common.slug') }}</label>
                <input v-model="form.slug" type="text" :placeholder="$t('admin.products.auto_slug')" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
            </div>
            <div>
                <label class="text-sm font-medium">{{ $t('common.swatch') }}</label>
                <div class="mt-2 flex gap-3">
                    <input v-model="form.hex_code" type="color" class="h-10 w-14 border border-gray-300 bg-white" />
                    <input v-model="form.hex_code" type="text" class="w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                </div>
                <p v-if="form.errors.hex_code" class="mt-1 text-sm text-red-600">{{ form.errors.hex_code }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                {{ $t('common.active') }}
            </label>
            <button type="submit" class="w-full border border-black bg-black px-6 py-3 text-sm font-medium text-white sm:w-auto">
                {{ $t('admin.colors.save') }}
            </button>
        </form>
    </AdminLayout>
</template>
