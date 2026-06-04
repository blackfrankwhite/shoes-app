<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="group">
        <Link :href="route('products.show', product.slug)" class="block">
            <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                <img
                    :src="product.image"
                    :alt="product.name"
                    class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                    loading="lazy"
                />
            </div>
            <div class="mt-4 space-y-1">
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ product.category?.name || product.sex }}
                </p>
                <h3 class="text-base font-medium text-gray-950">
                    {{ product.name }}
                </h3>
                <p class="text-sm text-gray-700">{{ product.formatted_price }}</p>
            </div>
        </Link>
    </article>
</template>
