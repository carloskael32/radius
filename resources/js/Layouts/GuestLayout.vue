<template>
    <div class="flex min-h-screen items-center justify-center overflow-y-auto bg-slate-100 px-4 py-6 sm:px-6 dark:bg-slate-950">
        <div class="w-full max-w-md">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-200/60 sm:p-8 dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/40">
                <slot />
            </div>

            <div class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                <p>© 2026 Dashboard. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(input),
:deep(button) {
    @apply transition-all duration-200;
}
</style>
