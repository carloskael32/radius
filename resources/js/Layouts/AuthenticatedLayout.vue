<script setup>
import Header from '@/Layouts/Header.vue';
import Navigation from '@/Layouts/Navigation.vue';
</script>

<template>
    <div class="flex h-screen bg-red font-roboto">
        <Navigation />

        <div class="flex flex-1 flex-col overflow-hidden">
            <Header />

            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-100">
                <div class="container mx-auto px-6 py-6">
                    <h3 class="text-3xl font-medium text-black ">
                        <slot name="header" />
                    </h3>

                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
