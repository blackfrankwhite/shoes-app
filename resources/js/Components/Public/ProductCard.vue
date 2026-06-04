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
        <Link :href="route('products.show', { locale: $page.props.i18n.locale, product: product.slug })" class="block">
            <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                <img
                    :src="product.image"
                    :alt="product.name"
                    class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                    loading="lazy"
                />
            </div>
            <div class="mt-3 space-y-1 sm:mt-4">
                <p class="break-words text-[11px] uppercase tracking-wide text-gray-500 sm:text-xs">
                    {{ product.category?.name || $t(`sexes.${product.sex}`) }}
                </p>
                <h3 class="break-words text-sm font-medium leading-5 text-gray-950 sm:text-base">
                    {{ product.name }}
                </h3>
                <p class="text-sm text-gray-700">{{ product.formatted_price }}</p>
            </div>
        </Link>
    </article>
</template>
