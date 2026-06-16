<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="product.name" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('admin.products.show_eyebrow') }}</p>
                <h1 class="mt-2 break-words text-2xl font-semibold sm:text-3xl">{{ product.name }}</h1>
            </div>
            <div class="grid gap-3 sm:flex">
                <Link :href="route('products.show', { locale: 'ka', product: product.slug })" class="border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium text-gray-800">{{ $t('common.public_page') }}</Link>
                <Link :href="route('admin.products.edit', product.slug)" class="border border-black bg-black px-4 py-3 text-center text-sm font-medium text-white">{{ $t('common.edit') }}</Link>
            </div>
        </div>

        <div class="mt-6 grid gap-6 sm:mt-8 lg:grid-cols-[1fr_360px] lg:gap-8">
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div v-for="image in product.images" :key="image.id" class="relative">
                    <img :src="image.url" :alt="image.alt_text || product.name" class="aspect-[4/5] w-full bg-gray-100 object-cover" />
                    <span v-if="image.color" class="absolute left-2 top-2 inline-flex items-center gap-1 bg-white px-2 py-1 text-xs text-gray-800">
                        <span class="h-3 w-3 border border-gray-300" :style="{ backgroundColor: image.color.hex_code || '#ddd' }" />
                        {{ image.color.name }}
                    </span>
                </div>
            </div>
            <aside class="border border-gray-200 bg-white p-5">
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="font-medium">{{ $t('common.category') }}</dt>
                        <dd class="mt-1 text-gray-600">{{ product.category?.name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">{{ $t('common.price') }}</dt>
                        <dd class="mt-1 text-gray-600">{{ product.formatted_price }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">{{ $t('common.stock') }}</dt>
                        <dd class="mt-1 text-gray-600">{{ product.stock_quantity }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">{{ $t('common.sizes') }}</dt>
                        <dd class="mt-1 text-gray-600">{{ product.sizes.map((size) => size.label).join(', ') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">{{ $t('common.colors') }}</dt>
                        <dd class="mt-1 space-y-1 text-gray-600">
                            <p v-for="color in product.colors" :key="color.id">
                                {{ color.name }}<span v-if="color.sku"> · {{ color.sku }}</span>
                            </p>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium">{{ $t('common.description') }}</dt>
                        <dd class="mt-1 text-gray-600">{{ product.description }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </AdminLayout>
</template>
