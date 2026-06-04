<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
    options: {
        type: Object,
        required: true,
    },
});

const isEdit = computed(() => Boolean(props.product));

const imageOrder = Object.fromEntries((props.product?.images || []).map((image) => [image.id, image.sort_order]));
const mainImage = (props.product?.images || []).find((image) => image.is_main) || props.product?.images?.[0];

const form = useForm({
    category_id: props.product?.category?.id || props.options.categories[0]?.id || '',
    name: props.product?.name || '',
    slug: props.product?.slug || '',
    sex: props.product?.sex || props.options.sexes[0],
    price: props.product?.price || '',
    description: props.product?.description || '',
    sku: props.product?.sku || '',
    stock_quantity: props.product?.stock_quantity ?? 0,
    featured: props.product?.featured || false,
    is_active: props.product?.is_active ?? true,
    sizes: (props.product?.sizes || []).map((size) => size.id),
    colors: (props.product?.colors || []).map((color) => color.id),
    images: [],
    delete_images: [],
    image_order: imageOrder,
    main_image_id: mainImage?.id || '',
});

const submit = () => {
    if (isEdit.value) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.products.update', props.product.slug), {
            forceFormData: true,
            preserveScroll: true,
        });

        return;
    }

    form.post(route('admin.products.store'), {
        forceFormData: true,
        preserveScroll: true,
    });
};

const setFiles = (event) => {
    form.images = Array.from(event.target.files || []);
};
</script>

<template>
    <Head :title="isEdit ? `Edit ${product.name}` : 'Create Product'" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Catalog</p>
                <h1 class="mt-2 text-3xl font-semibold">{{ isEdit ? 'Edit product' : 'Create product' }}</h1>
            </div>
            <Link :href="route('admin.products.index')" class="text-sm text-gray-600 hover:text-black">Back to products</Link>
        </div>

        <form class="mt-8 space-y-8" @submit.prevent="submit">
            <section class="grid gap-5 border border-gray-200 bg-white p-5 lg:grid-cols-2">
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
                    <label class="text-sm font-medium">SKU / factory code</label>
                    <input v-model="form.sku" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                    <p v-if="form.errors.sku" class="mt-1 text-sm text-red-600">{{ form.errors.sku }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium">Category</label>
                    <select v-model="form.category_id" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                        <option v-for="category in options.categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                    <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium">Sex</label>
                    <select v-model="form.sex" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                        <option v-for="sex in options.sexes" :key="sex" :value="sex">{{ sex }}</option>
                    </select>
                    <p v-if="form.errors.sex" class="mt-1 text-sm text-red-600">{{ form.errors.sex }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Price</label>
                        <input v-model="form.price" type="number" min="0" step="0.01" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">{{ form.errors.price }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Stock</label>
                        <input v-model="form.stock_quantity" type="number" min="0" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.stock_quantity" class="mt-1 text-sm text-red-600">{{ form.errors.stock_quantity }}</p>
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-medium">Description</label>
                    <textarea v-model="form.description" rows="5" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input v-model="form.featured" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                        Featured
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input v-model="form.is_active" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                        Active
                    </label>
                </div>
            </section>

            <section class="grid gap-6 border border-gray-200 bg-white p-5 lg:grid-cols-2">
                <div>
                    <h2 class="text-lg font-semibold">Sizes</h2>
                    <div class="mt-4 grid grid-cols-3 gap-2 sm:grid-cols-5">
                        <label v-for="size in options.sizes" :key="size.id" class="flex items-center gap-2 border border-gray-200 px-3 py-2 text-sm">
                            <input v-model="form.sizes" :value="size.id" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                            {{ size.label }}
                        </label>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Colors</h2>
                    <div class="mt-4 grid gap-2 sm:grid-cols-2">
                        <label v-for="color in options.colors" :key="color.id" class="flex items-center gap-2 border border-gray-200 px-3 py-2 text-sm">
                            <input v-model="form.colors" :value="color.id" type="checkbox" class="border-gray-300 text-black focus:ring-black" />
                            <span class="h-4 w-4 border border-gray-300" :style="{ backgroundColor: color.hex_code || '#ddd' }" />
                            {{ color.name }}
                        </label>
                    </div>
                </div>
            </section>

            <section class="border border-gray-200 bg-white p-5">
                <h2 class="text-lg font-semibold">Images</h2>
                <p class="mt-1 text-sm text-gray-600">Upload product images. Existing images can be reordered and one image can be marked as main.</p>

                <input type="file" multiple accept="image/*" class="mt-5 block w-full text-sm" @change="setFiles" />
                <p v-if="form.errors.images" class="mt-1 text-sm text-red-600">{{ form.errors.images }}</p>

                <div v-if="product?.images?.length" class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="image in product.images" :key="image.id" class="border border-gray-200 p-3">
                        <img :src="image.url" :alt="image.alt_text || product.name" class="aspect-[4/5] w-full bg-gray-100 object-cover" />
                        <div class="mt-3 space-y-3 text-sm">
                            <label class="block">
                                <span class="text-gray-600">Order</span>
                                <input v-model="form.image_order[image.id]" type="number" min="0" class="mt-1 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            </label>
                            <label class="flex items-center gap-2">
                                <input v-model="form.main_image_id" :value="image.id" type="radio" class="border-gray-300 text-black focus:ring-black" />
                                Main image
                            </label>
                            <label class="flex items-center gap-2 text-red-700">
                                <input v-model="form.delete_images" :value="image.id" type="checkbox" class="border-gray-300 text-red-700 focus:ring-red-700" />
                                Delete
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex gap-3">
                <button type="submit" :disabled="form.processing" class="border border-black bg-black px-6 py-3 text-sm font-medium text-white disabled:opacity-50">
                    {{ isEdit ? 'Save changes' : 'Create product' }}
                </button>
                <Link :href="route('admin.products.index')" class="border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-800">
                    Cancel
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
