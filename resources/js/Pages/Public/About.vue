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
                    <div class="mt-6 max-w-2xl space-y-4 text-base leading-7 text-gray-700">
                        <p v-for="paragraph in $t('public.about.body')" :key="paragraph">
                            {{ paragraph }}
                        </p>
                    </div>
                    <Link :href="route('products.index', { locale: $page.props.i18n.locale })" class="mt-8 inline-flex border border-black bg-black px-5 py-3 text-sm font-medium text-white">
                        {{ $t('public.about.browse_stock') }}
                    </Link>
                </div>

                <div class="w-full pt-2 lg:max-w-xs lg:justify-self-end lg:pt-1">
                    <dl class="grid gap-5 text-sm sm:grid-cols-2 lg:grid-cols-1">
                        <div class="min-w-0">
                            <dt class="font-medium text-gray-950">{{ $t('common.phone') }}</dt>
                            <dd class="mt-1 text-gray-600">+995 598 79 26 26</dd>
                        </div>
                        <div class="min-w-0">
                            <dt class="font-medium text-gray-950">{{ $t('public.about.hours') }}</dt>
                            <dd class="mt-1 text-gray-600">{{ $t('public.about.hours_value') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
