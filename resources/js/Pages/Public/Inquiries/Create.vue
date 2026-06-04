<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const params = new URLSearchParams(window.location.search);
const page = usePage();
const locale = computed(() => page.props.i18n?.locale || 'ka');

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
    form.post(route('inquiries.store', { locale: locale.value, product: props.product.slug }));
};
</script>

<template>
    <Head :title="$t('public.inquiry.title', { product: product.name })" />

    <PublicLayout>
        <section class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <Link :href="route('products.show', { locale, product: product.slug })" class="text-sm text-gray-600 hover:text-black">
                {{ $t('public.inquiry.back_to_product') }}
            </Link>

            <div class="mt-6 grid gap-8 lg:mt-8 lg:grid-cols-[320px_1fr] lg:gap-10">
                <aside class="flex gap-4 lg:block">
                    <div class="h-28 w-24 shrink-0 overflow-hidden bg-gray-100 lg:h-auto lg:w-auto lg:aspect-[4/5]">
                        <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                    </div>
                    <div>
                        <h1 class="break-words text-xl font-semibold lg:mt-4 lg:text-2xl">{{ product.name }}</h1>
                        <p class="mt-2 text-sm text-gray-600">{{ product.formatted_price }} · {{ product.sku }}</p>
                    </div>
                </aside>

                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('public.inquiry.eyebrow') }}</p>
                        <h2 class="mt-2 text-2xl font-semibold sm:text-3xl">{{ $t('public.inquiry.headline') }}</h2>
                        <p class="mt-3 text-sm leading-6 text-gray-600">
                            {{ $t('public.inquiry.intro') }}
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium">{{ $t('common.name') }}</label>
                            <input v-model="form.name" type="text" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">{{ $t('common.phone') }}</label>
                            <input v-model="form.phone" type="tel" :placeholder="$t('public.inquiry.placeholder_phone')" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">{{ $t('common.email_optional') }}</label>
                        <input v-model="form.email" type="email" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="text-sm font-medium">{{ $t('common.size') }}</label>
                            <select v-model="form.size_id" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option v-for="size in product.sizes" :key="size.id" :value="size.id">
                                    {{ size.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.size_id" class="mt-1 text-sm text-red-600">{{ form.errors.size_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">{{ $t('common.color') }}</label>
                            <select v-model="form.color_id" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black">
                                <option v-for="color in product.colors" :key="color.id" :value="color.id">
                                    {{ color.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.color_id" class="mt-1 text-sm text-red-600">{{ form.errors.color_id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">{{ $t('common.quantity') }}</label>
                            <input v-model="form.quantity" type="number" min="1" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                            <p v-if="form.errors.quantity" class="mt-1 text-sm text-red-600">{{ form.errors.quantity }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">{{ $t('common.comment') }}</label>
                        <textarea v-model="form.comment" rows="5" class="mt-2 w-full border-gray-300 text-sm focus:border-black focus:ring-black" />
                        <p v-if="form.errors.comment" class="mt-1 text-sm text-red-600">{{ form.errors.comment }}</p>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full border border-black bg-black px-6 py-3 text-sm font-medium text-white disabled:opacity-50 sm:w-auto"
                    >
                        {{ $t('public.inquiry.submit') }}
                    </button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
