<script setup>
import ProductCard from '@/Components/Public/ProductCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    relatedProducts: {
        type: Array,
        required: true,
    },
    breadcrumbs: {
        type: Array,
        required: true,
    },
});

const selectedSize = ref(props.product.sizes[0]?.id || null);
const selectedColor = ref(props.product.colors[0]?.id || null);

const reserveUrl = computed(() => {
    const params = new URLSearchParams();
    if (selectedSize.value) params.set('size_id', selectedSize.value);
    if (selectedColor.value) params.set('color_id', selectedColor.value);

    const query = params.toString();

    return `${route('inquiries.create', props.product.slug)}${query ? `?${query}` : ''}`;
});
</script>

<template>
    <Head :title="product.name" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <nav class="flex flex-wrap gap-2 text-xs text-gray-500">
                <template v-for="(crumb, index) in breadcrumbs" :key="`${crumb.label}-${index}`">
                    <Link v-if="crumb.href" :href="crumb.href" class="hover:text-black">{{ crumb.label }}</Link>
                    <span v-else class="text-gray-900">{{ crumb.label }}</span>
                    <span v-if="index < breadcrumbs.length - 1">/</span>
                </template>
            </nav>

            <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_420px]">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div
                        v-for="image in product.images"
                        :key="image.id"
                        class="aspect-[4/5] overflow-hidden bg-gray-100"
                    >
                        <img :src="image.url" :alt="image.alt_text || product.name" class="h-full w-full object-cover" />
                    </div>
                </div>

                <aside class="self-start lg:sticky lg:top-6">
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ product.category?.name }} · {{ product.sex }}</p>
                    <h1 class="mt-3 text-3xl font-semibold">{{ product.name }}</h1>
                    <p class="mt-3 text-lg text-gray-900">{{ product.formatted_price }}</p>
                    <p class="mt-2 text-sm text-gray-500">Factory code {{ product.sku }}</p>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <p class="text-sm font-medium">Select size</p>
                        <div class="mt-3 grid grid-cols-4 gap-2">
                            <button
                                v-for="size in product.sizes"
                                :key="size.id"
                                type="button"
                                @click="selectedSize = size.id"
                                :class="[
                                    'border px-3 py-3 text-sm',
                                    selectedSize === size.id ? 'border-black bg-black text-white' : 'border-gray-300 bg-white text-gray-900',
                                ]"
                            >
                                {{ size.label.replace('EU ', '') }}
                            </button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium">Select color</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button
                                v-for="color in product.colors"
                                :key="color.id"
                                type="button"
                                @click="selectedColor = color.id"
                                :class="[
                                    'flex items-center gap-2 border px-3 py-2 text-sm',
                                    selectedColor === color.id ? 'border-black' : 'border-gray-300',
                                ]"
                            >
                                <span class="h-4 w-4 border border-gray-300" :style="{ backgroundColor: color.hex_code || '#ddd' }" />
                                {{ color.name }}
                            </button>
                        </div>
                    </div>

                    <Link
                        :href="reserveUrl"
                        class="mt-8 flex w-full items-center justify-center border border-black bg-black px-5 py-4 text-sm font-medium text-white"
                    >
                        Reserve / Contact Factory
                    </Link>
                    <p class="mt-3 text-xs leading-5 text-gray-500">
                        No online payment. The factory confirms availability by phone before your visit.
                    </p>

                    <div class="mt-8 space-y-6 border-t border-gray-200 pt-6 text-sm leading-6">
                        <div>
                            <h2 class="font-medium">Details</h2>
                            <p class="mt-2 text-gray-700">{{ product.description }}</p>
                        </div>
                        <div>
                            <h2 class="font-medium">Highlights</h2>
                            <ul class="mt-2 list-inside list-disc text-gray-700">
                                <li>Factory-made in Tbilisi</li>
                                <li>Reserve online and pay on-site</li>
                                <li>{{ product.stock_quantity }} pairs currently listed in stock</li>
                            </ul>
                        </div>
                        <div>
                            <h2 class="font-medium">Composition</h2>
                            <p class="mt-2 text-gray-700">
                                Upper: locally sourced leather or textile by colorway. Sole: molded rubber. Lining: breathable textile.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section v-if="relatedProducts.length" class="mx-auto max-w-7xl border-t border-gray-200 px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold">Related products</h2>
            <div class="mt-6 grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                <ProductCard v-for="related in relatedProducts" :key="related.id" :product="related" />
            </div>
        </section>
    </PublicLayout>
</template>
