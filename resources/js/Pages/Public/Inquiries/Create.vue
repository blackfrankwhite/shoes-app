<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const params = new URLSearchParams(window.location.search);

const form = useForm({
    name: '',
    phone: '',
    email: '',
    size_id: Number(params.get('size_id')) || props.product.sizes[0]?.id || '',
    color_id: Number(params.get('color_id')) || props.product.colors[0]?.id || '',
    quantity: 1,
    comment: '',
});

const submit = () => {
    form.post(route('inquiries.store', props.product.slug));
};
</script>

<template>
    <Head :title="`Reserve ${product.name}`" />

    <PublicLayout>
        <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <Link :href="route('products.show', product.slug)" class="text-sm text-gray-600 hover:text-black">
                Back to product
            </Link>

            <div class="mt-8 grid gap-10 lg:grid-cols-[320px_1fr]">
                <aside>
                    <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                        <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                    </div>
                    <h1 class="mt-4 text-2xl font-semibold">{{ product.name }}</h1>
                    <p class="mt-2 text-sm text-gray-600">{{ product.formatted_price }} · {{ product.sku }}</p>
                </aside>

                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Reservation request</p>
                        <h2 class="mt-2 text-3xl font-semibold">Contact factory</h2>
                        <p class="mt-3 text-sm leading-6 text-gray-600">
                            Submit your details and the factory team will call to confirm availability. Payment is handled physically on-site.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium">Name</label>
                            <input v-model="form.name" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Phone</label>
                            <input v-model="form.phone" type="tel" placeholder="+995 599 00 00 00" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email optional</label>
                        <input v-model="form.email" type="email" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium">Size</label>
                            <select v-model="form.size_id" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option v-for="size in product.sizes" :key="size.id" :value="size.id">
                                    {{ size.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.size_id" class="mt-1 text-sm text-red-600">{{ form.errors.size_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Color</label>
                            <select v-model="form.color_id" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option v-for="color in product.colors" :key="color.id" :value="color.id">
                                    {{ color.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.color_id" class="mt-1 text-sm text-red-600">{{ form.errors.color_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Quantity</label>
                            <input v-model="form.quantity" type="number" min="1" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-600">{{ form.errors.quantity }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Comment</label>
                        <textarea v-model="form.comment" rows="5" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.comment" class="mt-1 text-sm text-red-600">{{ form.errors.comment }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="border border-black bg-black px-6 py-3 text-sm font-medium text-white disabled:opacity-50"
                    >
                        Submit reservation
                    </button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
