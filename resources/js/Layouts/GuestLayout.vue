<template>
    <div class="flex h-screen items-center justify-center bg-gradient-to-br from-gray-100 via-gray-50 to-white px-6">
        <div class="w-full max-w-md">
            <!-- Card Container -->
            <div class="rounded-2xl bg-white p-8 shadow-2xl border border-gray-200">
                <slot />
            </div>

            <!-- Footer Text -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>© 2026 Dashboard. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add smooth transitions */
:deep(input),
:deep(button) {
    @apply transition-all duration-200;
}
</style>
