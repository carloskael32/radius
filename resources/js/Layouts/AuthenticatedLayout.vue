<script setup>
import Header from '@/Layouts/Header.vue';
import Navigation from '@/Layouts/Navigation.vue';
import Toast from 'primevue/toast';
</script>

<template>
    <Toast/>
    <div class="flex h-screen bg-slate-100 font-roboto dark:bg-slate-950">
        <Navigation />

        <div class="flex flex-1 flex-col overflow-hidden">
            <Header />

            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-slate-100 dark:bg-slate-950">
                <div class="container mx-auto px-4 py-6 sm:px-6">
                    <h3 class="mb-4 text-3xl font-bold text-slate-900 dark:text-slate-50">
                        <slot name="header" />
                    </h3>

                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
.p-paginator {
    @apply mt-2 flex justify-center gap-2;
}

.p-paginator-first,
.p-paginator-prev,
.p-paginator-next,
.p-paginator-last {
    @apply rounded-full border border-slate-200 bg-white px-3 py-1 text-slate-700 transition hover:bg-slate-100 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700;
}

.p-paginator-page {
    @apply rounded-full border border-slate-200 bg-white px-3 py-1 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700;
}

.p-highlight {
    @apply border-indigo-500 bg-indigo-500 text-white dark:border-indigo-400 dark:bg-indigo-500;
}
</style>