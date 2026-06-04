<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    inquiry: {
        type: Object,
        required: true,
    },
    statuses: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    status: props.inquiry.status,
});

const updateStatus = () => {
    form.patch(route('admin.inquiries.status', props.inquiry.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Inquiry #${inquiry.id}`" />

    <AdminLayout>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-gray-500">Reservation</p>
                <h1 class="mt-2 text-3xl font-semibold">Inquiry #{{ inquiry.id }}</h1>
            </div>
            <Link :href="route('admin.inquiries.index')" class="text-sm text-gray-600 hover:text-black">Back to inquiries</Link>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_360px]">
            <section class="space-y-8">
                <div class="border border-gray-200 bg-white p-5">
                    <h2 class="text-lg font-semibold">Customer</h2>
                    <dl class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <dt class="font-medium">Name</dt>
                            <dd class="mt-1 text-gray-600">{{ inquiry.name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Phone</dt>
                            <dd class="mt-1 text-gray-600">{{ inquiry.phone }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Email</dt>
                            <dd class="mt-1 text-gray-600">{{ inquiry.email || 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium">Created</dt>
                            <dd class="mt-1 text-gray-600">{{ inquiry.created_at }}</dd>
                        </div>
                    </dl>
                    <div class="mt-5">
                        <p class="font-medium text-sm">Comment</p>
                        <p class="mt-1 text-sm leading-6 text-gray-600">{{ inquiry.comment || 'No comment.' }}</p>
                    </div>
                </div>

                <div class="border border-gray-200 bg-white p-5">
                    <h2 class="text-lg font-semibold">Requested item</h2>
                    <div class="mt-4 flex gap-4">
                        <img :src="inquiry.product.image" :alt="inquiry.product.name" class="h-32 w-28 bg-gray-100 object-cover" />
                        <div class="text-sm">
                            <p class="font-medium">{{ inquiry.product.name }}</p>
                            <p class="mt-1 text-gray-600">{{ inquiry.product.sku }}</p>
                            <p class="mt-3 text-gray-600">Size {{ inquiry.size }} · {{ inquiry.color }} · Qty {{ inquiry.quantity }}</p>
                            <Link :href="route('admin.products.show', inquiry.product.slug)" class="mt-4 inline-flex text-gray-700 hover:text-black">
                                Open product
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="self-start border border-gray-200 bg-white p-5">
                <h2 class="text-lg font-semibold">Status</h2>
                <form class="mt-4 space-y-4" @submit.prevent="updateStatus">
                    <select v-model="form.status" class="w-full border-gray-300 text-sm capitalize focus:border-black focus:ring-black">
                        <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                    </select>
                    <button type="submit" class="w-full border border-black bg-black px-4 py-3 text-sm font-medium text-white">
                        Update status
                    </button>
                </form>
            </aside>
        </div>
    </AdminLayout>
</template>
