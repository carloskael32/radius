<script setup>
import Header from '@/Layouts/Header.vue';
import Navigation from '@/Layouts/Navigation.vue';
</script>

<template>
    <Toast />
    <div class="flex h-screen bg-white font-roboto">
        <Navigation />

        <div class="flex flex-1 flex-col overflow-hidden">
            <Header />

            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <h3 class="text-4xl font-bold text-gray-900 mb-6">
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
    @apply flex justify-center gap-2 mt-2;
}

.p-paginator-first,
.p-paginator-prev,
.p-paginator-next,
.p-paginator-last {
    @apply px-3 py-1 rounded-full bg-gray-200 text-gray-800 hover:bg-gray-300 disabled:opacity-50 transition;
}
</style>