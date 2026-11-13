<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    sale: { type: Object, required: true },
    homeCover: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    sale_enabled: props.sale.enabled,
    sale_percent: props.sale.percent || '',
    home_cover: null,
    remove_home_cover: false,
});

const setHomeCover = (event) => {
    form.home_cover = event.target.files?.[0] || null;
    form.remove_home_cover = false;
};

const submit = () => {
    form.post(route('admin.storefront.update'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="$t('admin.storefront.title')" />

    <AdminLayout>
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.storefront.eyebrow') }}</p>
            <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('admin.storefront.title') }}</h1>
        </div>

        <form class="mt-6 max-w-3xl space-y-6 border border-gray-200 bg-white p-4 sm:mt-8 sm:p-5" @submit.prevent="submit">
            <section class="rounded-lg border border-rose-200 bg-rose-50/40 p-4">
                <h2 class="text-lg font-semibold">{{ $t('common.store_sale') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $t('common.store_sale_help') }}</p>
                <label for="store-sale-percent" class="mt-4 block text-sm font-medium">{{ $t('common.sale_percent') }}</label>
                <input id="store-sale-percent" v-model="form.sale_percent" type="number" min="1" max="100" step="1" class="mt-2 w-40 border-gray-300 text-sm focus:border-black focus:ring-black" />
                <p v-if="form.errors.sale_percent" class="mt-1 text-sm text-red-600">{{ form.errors.sale_percent }}</p>
                <label class="mt-4 flex items-center gap-2 text-sm font-medium">
                    <input v-model="form.sale_enabled" type="checkbox" class="border-gray-300 text-rose-700 focus:ring-rose-700" />
                    {{ $t('common.sale_enabled') }}
                </label>
                <p v-if="form.errors.sale_enabled" class="mt-1 text-sm text-red-600">{{ form.errors.sale_enabled }}</p>
            </section>
            <section>
                <h2 class="text-lg font-semibold">{{ $t('admin.storefront.home_cover') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ $t('admin.storefront.home_cover_help') }}</p>

                <div v-if="homeCover.url" class="mt-5">
                    <img :src="homeCover.url" alt="" class="aspect-[16/7] w-full bg-gray-100 object-cover" />
                    <label class="mt-3 flex items-center gap-2 text-sm text-red-700">
                        <input v-model="form.remove_home_cover" type="checkbox" class="border-gray-300 text-red-700 focus:ring-red-700" />
                        {{ $t('admin.storefront.remove_home_cover') }}
                    </label>
                </div>

                <input type="file" accept="image/*" class="mt-5 block w-full text-sm" @change="setHomeCover" />
                <p v-if="form.home_cover" class="mt-2 break-all text-sm text-gray-600">{{ form.home_cover.name }}</p>
                <p v-if="form.errors.home_cover" class="mt-1 text-sm text-red-600">{{ form.errors.home_cover }}</p>
            </section>

            <button type="submit" :disabled="form.processing" class="border border-black bg-black px-6 py-3 text-sm font-medium text-white disabled:opacity-50">
                {{ $t('admin.storefront.save') }}
            </button>
        </form>
    </AdminLayout>
</template>
