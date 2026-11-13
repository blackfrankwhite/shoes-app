<script setup>
defineProps({ product: { type: Object, required: true } });
</script>

<template>
    <span class="inline-flex flex-wrap items-center gap-x-2 gap-y-1">
        <span v-if="product.on_sale" class="sr-only">{{ $t('common.original_price') }}</span>
        <del v-if="product.on_sale" class="text-sm font-normal text-gray-400 decoration-gray-400">{{ product.formatted_original_price }}</del>
        <span v-if="product.on_sale" class="sr-only">{{ $t('common.sale_price') }}</span>
        <span :class="product.on_sale ? 'font-semibold text-rose-700' : ''">{{ product.formatted_price }}</span>
        <span v-if="product.on_sale" class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-700">−{{ product.discount_percent }}%</span>
    </span>
</template>
