<script setup>
defineProps({
    showBrandPanel: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="relative flex min-h-screen items-center justify-center overflow-y-auto bg-[#f7f8fc] px-4 py-6 sm:px-6 dark:bg-slate-950">
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[#ff4f78]/10 blur-3xl dark:bg-[#ff4f78]/10"></div>
            <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-[#ffd84d]/10 blur-3xl dark:bg-[#ffd84d]/5"></div>
        </div>

        <div :class="showBrandPanel ? 'max-w-5xl lg:grid lg:grid-cols-[0.92fr_1.08fr]' : 'max-w-md'" class="relative w-full">
            <aside v-if="showBrandPanel" class="relative hidden overflow-hidden rounded-l-[2rem] bg-[#111827] p-10 text-white lg:flex lg:min-h-[620px] lg:flex-col lg:justify-between">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full border-[28px] border-[#ff4f78]/20"></div>
                <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full border-[36px] border-[#ffd84d]/10"></div>
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/15">
                            <img src="/images/fenix.png" alt="" class="h-9 w-9 object-contain">
                        </div>
                        <span class="text-sm font-semibold uppercase tracking-[0.24em] text-white/80">Fenix Telecom</span>
                    </div>
                    <div class="mt-24 max-w-sm">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#ffd84d]">Portal ISP</p>
                        <h2 class="mt-4 text-4xl font-bold leading-tight tracking-tight">Tu conexión, siempre bajo control.</h2>
                        <p class="mt-5 text-base leading-7 text-slate-300">Gestiona tus servicios y mantente conectado desde un solo lugar.</p>
                    </div>
                </div>
                <div class="relative flex items-center gap-3 text-sm text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-[#ff4f78] shadow-[0_0_0_5px_rgba(255,79,120,0.15)]"></span>
                    Acceso seguro para clientes
                </div>
            </aside>

            <main class="rounded-[2rem] border border-slate-200/80 bg-white p-5 shadow-[0_24px_70px_-28px_rgba(15,23,42,0.3)] sm:p-10 lg:min-h-[620px] dark:border-slate-700 dark:bg-slate-900 dark:shadow-slate-950/50" :class="showBrandPanel ? 'lg:rounded-l-none' : ''">
                <slot />
            </main>

            <div class="mt-6 text-center text-sm text-slate-500 lg:col-span-2 dark:text-slate-400">
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
