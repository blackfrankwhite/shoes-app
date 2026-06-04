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
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Product</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ product.name }}</h1>
            </div>
            <div class="flex gap-3">
                <Link :href="route('products.show', product.slug)" class="border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-800">Public page</Link>
                <Link :href="route('admin.products.edit', product.slug)" class="border border-black bg-black px-4 py-3 text-sm font-medium text-white">Edit</Link>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_360px]">
            <div class="grid gap-4 sm:grid-cols-2">
                <img v-for="image in product.images" :key="image.id" :src="image.url" :alt="image.alt_text || product.name" class="aspect-[4/5] w-full bg-gray-100 object-cover" />
            </div>
            <aside class="border border-gray-200 bg-white p-5">
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="font-medium">SKU</dt>
                        <dd class="mt-1 text-gray-600">{{ product.sku }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Category</dt>
                        <dd class="mt-1 text-gray-600">{{ product.category?.name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Price</dt>
                        <dd class="mt-1 text-gray-600">{{ product.formatted_price }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Stock</dt>
                        <dd class="mt-1 text-gray-600">{{ product.stock_quantity }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Sizes</dt>
                        <dd class="mt-1 text-gray-600">{{ product.sizes.map((size) => size.label).join(', ') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Colors</dt>
                        <dd class="mt-1 text-gray-600">{{ product.colors.map((color) => color.name).join(', ') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium">Description</dt>
                        <dd class="mt-1 text-gray-600">{{ product.description }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </AdminLayout>
</template>
