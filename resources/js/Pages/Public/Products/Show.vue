<script setup>
import ProductCard from '@/Components/Public/ProductCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

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

const page = usePage();
const selectedSize = ref(props.product.sizes[0]?.id || null);
const selectedColor = ref(props.product.colors[0]?.id || null);
const selectedImageIndex = ref(0);
const lightboxIndex = ref(0);
const lightboxOpen = ref(false);
const selectedColorSku = computed(() => props.product.colors.find((color) => Number(color.id) === Number(selectedColor.value))?.sku || props.product.sku);

const visibleImages = computed(() => {
    const colorImages = selectedColor.value
        ? props.product.images.filter((image) => Number(image.color_id) === Number(selectedColor.value))
        : [];

    return colorImages.length ? colorImages : props.product.images;
});
const primaryImage = computed(() => visibleImages.value[selectedImageIndex.value] || visibleImages.value[0] || props.product.images[0]);
const canNavigateImages = computed(() => visibleImages.value.length > 1);

watch(selectedColor, () => {
    selectedImageIndex.value = 0;
    lightboxIndex.value = 0;
    lightboxOpen.value = false;
});

const selectImage = (index) => {
    selectedImageIndex.value = index;
};

const showNextMobileImage = () => {
    if (! canNavigateImages.value) return;

    selectedImageIndex.value = (selectedImageIndex.value + 1) % visibleImages.value.length;
};

const openLightbox = (index) => {
    lightboxIndex.value = index;
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

const previousLightboxImage = () => {
    lightboxIndex.value = (lightboxIndex.value - 1 + visibleImages.value.length) % visibleImages.value.length;
};

const nextLightboxImage = () => {
    lightboxIndex.value = (lightboxIndex.value + 1) % visibleImages.value.length;
};

const lightboxImage = computed(() => visibleImages.value[lightboxIndex.value] || primaryImage.value);

const reserveUrl = computed(() => {
    const params = new URLSearchParams();
    if (selectedSize.value) params.set('size_id', selectedSize.value);
    if (selectedColor.value) params.set('color_id', selectedColor.value);

    const query = params.toString();

    return `${route('inquiries.create', { locale: page.props.i18n?.locale || 'ka', product: props.product.slug })}${query ? `?${query}` : ''}`;
});
</script>

<template>
    <Head :title="product.name" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 pb-28 pt-5 sm:px-6 sm:py-8 lg:px-8">
            <nav class="flex gap-2 overflow-x-auto whitespace-nowrap text-xs text-gray-500">
                <template v-for="(crumb, index) in breadcrumbs" :key="`${crumb.label}-${index}`">
                    <Link v-if="crumb.href" :href="crumb.href" class="hover:text-black">{{ crumb.label }}</Link>
                    <span v-else class="text-gray-900">{{ crumb.label }}</span>
                    <span v-if="index < breadcrumbs.length - 1">/</span>
                </template>
            </nav>

            <div class="mt-5 grid gap-7 sm:mt-8 lg:grid-cols-[minmax(0,1.1fr)_420px] lg:gap-10">
                <div class="lg:hidden">
                    <button type="button" class="block aspect-[4/5] w-full overflow-hidden bg-gray-100 text-left" @click="showNextMobileImage">
                        <img :src="primaryImage.url" :alt="primaryImage.alt_text || product.name" class="h-full w-full object-cover" />
                    </button>

                    <div v-if="canNavigateImages" class="mt-3 flex gap-3 overflow-x-auto pb-1">
                        <button
                            v-for="(image, index) in visibleImages"
                            :key="image.id"
                            type="button"
                            :class="[
                                'h-24 w-20 shrink-0 overflow-hidden border bg-gray-100',
                                selectedImageIndex === index ? 'border-black' : 'border-transparent',
                            ]"
                            @click="selectImage(index)"
                        >
                            <img :src="image.url" :alt="image.alt_text || product.name" class="h-full w-full object-cover" />
                        </button>
                    </div>
                </div>

                <div class="hidden gap-4 lg:grid lg:grid-cols-2">
                    <button
                        v-for="(image, index) in visibleImages"
                        :key="image.id"
                        type="button"
                        class="group aspect-[4/5] overflow-hidden bg-gray-100 text-left"
                        @click="openLightbox(index)"
                    >
                        <img :src="image.url" :alt="image.alt_text || product.name" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]" />
                    </button>
                </div>

                <aside class="self-start lg:sticky lg:top-24">
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ product.category?.name }} · {{ $t(`sexes.${product.sex}`) }}</p>
                    <h1 class="mt-3 break-words text-2xl font-semibold sm:text-3xl">{{ product.name }}</h1>
                    <p class="mt-3 text-lg text-gray-900">{{ product.formatted_price }}</p>
                    <p v-if="selectedColorSku" class="mt-2 text-sm text-gray-500">{{ $t('public.show.factory_code', { sku: selectedColorSku }) }}</p>

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <p class="text-sm font-medium">{{ $t('public.show.select_size') }}</p>
                        <div class="mt-3 grid grid-cols-5 gap-2 sm:grid-cols-4">
                            <button
                                v-for="size in product.sizes"
                                :key="size.id"
                                type="button"
                                @click="selectedSize = size.id"
                                :class="[
                                    'min-h-11 border px-3 py-3 text-sm',
                                    selectedSize === size.id ? 'border-black bg-black text-white' : 'border-gray-300 bg-white text-gray-900',
                                ]"
                            >
                                {{ size.label.replace('EU ', '') }}
                            </button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium">{{ $t('public.show.select_color') }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button
                                v-for="color in product.colors"
                                :key="color.id"
                                type="button"
                                @click="selectedColor = color.id"
                                :class="[
                                    'min-h-11 flex items-center gap-2 border px-3 py-2 text-sm',
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
                        class="mt-8 hidden w-full items-center justify-center border border-black bg-black px-5 py-4 text-sm font-medium text-white lg:flex"
                    >
                        {{ $t('public.show.reserve') }}
                    </Link>

                    <div class="mt-8 space-y-6 border-t border-gray-200 pt-6 text-sm leading-6">
                        <div>
                            <h2 class="font-medium">{{ $t('common.details') }}</h2>
                            <p class="mt-2 text-gray-700">{{ product.description }}</p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <div class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white p-3 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] lg:hidden">
            <Link :href="reserveUrl" class="flex min-h-12 items-center justify-center border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                {{ $t('public.show.reserve') }}
            </Link>
        </div>

        <section v-if="relatedProducts.length" class="mx-auto max-w-7xl border-t border-gray-200 px-4 py-12 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold">{{ $t('public.show.related') }}</h2>
            <div class="mt-6 grid grid-cols-2 gap-x-3 gap-y-8 sm:gap-x-5 sm:gap-y-10 lg:grid-cols-4">
                <ProductCard v-for="related in relatedProducts" :key="related.id" :product="related" />
            </div>
        </section>

        <div v-if="lightboxOpen" class="fixed inset-0 z-50 hidden bg-white lg:flex lg:items-center lg:justify-center">
            <button type="button" class="absolute right-6 top-6 flex size-11 items-center justify-center border border-gray-200 text-2xl leading-none text-gray-700 hover:border-black hover:text-black" aria-label="Close" @click="closeLightbox">
                &times;
            </button>

            <button
                v-if="canNavigateImages"
                type="button"
                class="absolute left-6 top-1/2 flex size-12 -translate-y-1/2 items-center justify-center border border-gray-200 text-3xl leading-none text-gray-700 hover:border-black hover:text-black"
                aria-label="Previous image"
                @click="previousLightboxImage"
            >
                ‹
            </button>

            <img :src="lightboxImage.url" :alt="lightboxImage.alt_text || product.name" class="max-h-[88vh] max-w-[82vw] object-contain" />

            <button
                v-if="canNavigateImages"
                type="button"
                class="absolute right-6 top-1/2 flex size-12 -translate-y-1/2 items-center justify-center border border-gray-200 text-3xl leading-none text-gray-700 hover:border-black hover:text-black"
                aria-label="Next image"
                @click="nextLightboxImage"
            >
                ›
            </button>
        </div>
    </PublicLayout>
</template>
