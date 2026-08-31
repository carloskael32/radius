<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const services = ref([
    {
        name: 'freeRADIUS',
        label: 'Servidor de autenticación',
        icon: '🛡️',
        status: 'online',
        detail: 'Respuesta en 18 ms',
    },
    {
        name: 'Base de datos',
        label: 'MySQL / MariaDB',
        icon: '🗄️',
        status: 'online',
        detail: 'Conexión estable',
    },
    {
        name: 'Mikrotiks',
        label: 'Routers activos',
        icon: '📡',
        status: 'warning',
        detail: '2 de 4 en línea',
    },
])

const mikrotiks = ref([
    { name: 'MikroTik Core', ip: '192.168.1.1', status: 'online' },
    { name: 'MikroTik Backup', ip: '192.168.1.2', status: 'offline' },
    { name: 'MikroTik WiFi', ip: '192.168.1.3', status: 'online' },
])

const overallStatus = computed(() => {
    const onlineCount = services.value.filter((service) => service.status === 'online').length

    if (onlineCount === services.value.length) return 'Operativo'
    if (onlineCount === 0) return 'Sin conexión'
    return 'Parcialmente operativo'
})
</script>

<template>
    <Head title="Estado del sistema" />

    <AuthenticatedLayout>
    

        <div class="py-6 dark:bg-slate-950">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-3xl border border-sky-100 bg-gradient-to-r from-sky-600 via-cyan-500 to-emerald-500 p-6 text-white shadow-lg">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sky-100">
                                Monitoreo general
                            </p>
                            <h2 class="mt-2 text-2xl font-semibold">
                                Estado en tiempo real del sistema Radius
                            </h2>
                            <p class="mt-2 max-w-2xl text-sm text-sky-50">
                                Supervisa la disponibilidad de freeRADIUS, la base de datos y los Mikrotiks conectados.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/30 bg-white/20 px-4 py-3 backdrop-blur">
                            <div class="flex items-center gap-2">
                                <span
                                    class="h-3 w-3 rounded-full"
                                    :class="overallStatus === 'Operativo'
                                        ? 'bg-emerald-300'
                                        : overallStatus === 'Parcialmente operativo'
                                            ? 'bg-amber-300'
                                            : 'bg-rose-300'"
                                />
                                <span class="text-sm font-semibold">{{ overallStatus }}</span>
                            </div>
                            <p class="mt-1 text-xs text-sky-50">Última verificación: hace 12 segundos</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div
                        v-for="service in services"
                        :key="service.name"
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-slate-700 dark:bg-slate-900 dark:shadow-none"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ service.label }}</p>
                                <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ service.name }}</h3>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                                :class="service.status === 'online'
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300'
                                    : service.status === 'warning'
                                        ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300'
                                        : 'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300'"
                            >
                                {{ service.status === 'online' ? 'Conectado' : service.status === 'warning' ? 'Parcial' : 'Caído' }}
                            </span>
                        </div>

                        <div class="mt-5 flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl text-xl"
                                :class="service.status === 'online'
                                    ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300'
                                    : service.status === 'warning'
                                        ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-300'
                                        : 'bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-300'"
                            >
                                {{ service.icon }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ service.detail }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Estado visual de monitoreo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:shadow-none">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Mikrotiks</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Lista de routers con indicador de conexión</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                            3/4 activos
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div
                            v-for="router in mikrotiks"
                            :key="router.name"
                            class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 transition hover:border-sky-200 hover:bg-sky-50 dark:border-slate-700 dark:hover:border-sky-500/40 dark:hover:bg-slate-800/80"
                        >
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-lg dark:bg-slate-800">
                                    📡
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-slate-100">{{ router.name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ router.ip }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="h-2.5 w-2.5 rounded-full"
                                    :class="router.status === 'online' ? 'bg-emerald-500' : 'bg-rose-500'"
                                />
                                <span
                                    class="text-sm font-semibold"
                                    :class="router.status === 'online' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                                >
                                    {{ router.status === 'online' ? 'En línea' : 'Sin conexión' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
