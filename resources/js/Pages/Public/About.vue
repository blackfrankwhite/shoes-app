<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head :title="$t('public.about.title')" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_.9fr]">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-gray-500">{{ $t('public.about.eyebrow') }}</p>
                    <h1 class="mt-4 text-4xl font-semibold">{{ $t('public.about.headline') }}</h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-gray-700">
                        {{ $t('public.about.body') }}
                    </p>
                    <Link :href="route('products.index', { locale: $page.props.i18n.locale })" class="mt-8 inline-flex border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                        {{ $t('public.about.browse_stock') }}
                    </Link>
                </div>

                <div class="border border-gray-200 p-6">
                    <dl class="space-y-5 text-sm">
                        <div>
                            <dt class="font-medium text-gray-950">{{ $t('public.about.address') }}</dt>
                            <dd class="mt-1 text-gray-600">{{ $t('public.about.address_value') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">{{ $t('common.phone') }}</dt>
                            <dd class="mt-1 text-gray-600">+995 599 00 00 00</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">{{ $t('public.about.hours') }}</dt>
                            <dd class="mt-1 text-gray-600">{{ $t('public.about.hours_value') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-950">{{ $t('public.about.payment') }}</dt>
                            <dd class="mt-1 text-gray-600">{{ $t('public.about.payment_value') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
