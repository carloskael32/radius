<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Chart from 'primevue/chart';

// Props recibidas del controlador
const props = defineProps({
    totalClient: {
        type: Number,
        default: 0
    },
    totalNAS: {
        type: Number,
        default: 0
    },
    connectedClient: {
        type: Number,
        default: 0
    },
    disconnectedClient: {
        type: Number,
        default: 0
    },
    Planes: {
        type: Array,
        default: () => []
    },
    clientesPorPlan: {
        type: Object,
        default: () => ({})
    },

    //para mostrar usuarios sin perfil de navegacion
     clientesSinGrupo: {
        type: Number,
        default: 0
    }, 
})

// Colores para los gráficos
const chartColors = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#84cc16'];

// Datos para gráfico de pie - clientes por grupo
const usersByGroupData = computed(() => {
    const labels = props.Planes.length > 0 ? props.Planes : ['Administrators', 'Premium', 'Standard', 'Básico'];
    const data = props.Planes.length > 0
        ? props.Planes.map(plan => props.clientesPorPlan[plan] || 0)
        : [120, 450, 520, 160];

    return {
        labels,
        datasets: [{
            data,
            backgroundColor: chartColors.slice(0, labels.length),
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    };
});

const usersByGroupOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                font: { size: 12 },
                padding: 15
            }
        }
    }
});

// Datos para gráfico de línea - Conexiones diarias
const dailyConnectionsData = ref({
    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
    datasets: [
        {
            label: 'Conexiones Exitosas',
            data: [620, 580, 710, 690, 750, 600, 550],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        },
        {
            label: 'Conexiones Fallidas',
            data: [45, 52, 38, 41, 35, 48, 55],
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }
    ]
});

const dailyConnectionsOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: { font: { size: 12 } }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: '#f0f0f0' }
        },
        x: {
            grid: { color: '#f0f0f0' }
        }
    }
});

// Datos para gráfico de barras horizontal - Usuarios por NAS
const usersByNASData = ref({
    labels: ['NAS-Office-01', 'NAS-Warehouse-02', 'NAS-Branch1-03', 'NAS-Branch2-04', 'NAS-Remote-05'],
    datasets: [{
        label: 'Usuarios Conectados',
        data: [185, 156, 142, 128, 95],
        backgroundColor: '#3b82f6',
        borderColor: '#1e40af',
        borderWidth: 1
    }]
});

const usersByNASOptions = ref({
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        x: {
            beginAtZero: true,
            grid: { color: '#f0f0f0' }
        }
    }
});

// Datos para gráfico de intento de conexión (Pie)
const connectionAttemptsData = ref({
    labels: ['Exitosos', 'Fallidos'],
    datasets: [{
        data: [4250, 380],
        backgroundColor: ['#10b981', '#ef4444'],
        borderColor: '#ffffff',
        borderWidth: 2
    }]
});

const connectionAttemptsOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: { font: { size: 12 }, padding: 15 }
        }
    }
});

// Métrica adicional - Tasa de éxito
const successRate = computed(() => {
    const total = 4250 + 380;
    return ((4250 / total) * 100).toFixed(1);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            
        </template>

        <div class="space-y-6">
            <!-- Tarjetas de Métricas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Total Usuarios -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-blue-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total Usuarios</span>
                                <!-- <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-green-500">↑ 12%</span> -->
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.totalClient }}</p>
                            <p class="text-xs text-gray-600 mt-3">Desde el mes pasado</p>
                        </div>
                        <div class="bg-blue-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total NAS -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-purple-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total NAS</span>
                                <!-- <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-purple-500">Activos</span> -->
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.totalNAS }}</p>
                            <p class="text-xs text-gray-600 mt-3">Dispositivos conectados</p>
                        </div>
                        <div class="bg-purple-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Conectados -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-green-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Conectados</span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-green-500">{{ ((props.connectedClient / props.totalClient) * 100).toFixed(1) }}%</span>
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.connectedClient }}</p>
                            <p class="text-xs text-gray-600 mt-3">Usuarios en línea ahora</p>
                        </div>
                        <div class="bg-green-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Desconectados -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-red-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Desconectados</span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-red-500">{{ ((props.disconnectedClient / props.totalClient) * 100).toFixed(1) }}%</span>
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.disconnectedClient }}</p>
                            <p class="text-xs text-gray-600 mt-3">Sin conexión activa</p>
                        </div>
                        <div class="bg-red-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Clientes Sin Grupo -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-sm hover:shadow-md transition-all p-6 border border-orange-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Sin Grupo</span>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-orange-500">{{ ((props.clientesSinGrupo / props.totalClient) * 100).toFixed(1) }}%</span>
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.clientesSinGrupo }}</p>
                            <p class="text-xs text-gray-600 mt-3">Sin plan asignado</p>
                        </div>
                        <div class="bg-orange-500 p-4 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Conexiones Diarias -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Conexiones</h3>
                        <p class="text-sm text-gray-500 mt-1">Últimos 7 días</p>
                    </div>
                    <Chart type="line" :data="dailyConnectionsData" :options="dailyConnectionsOptions" class="w-full" style="height: 320px;" />
                </div>

                <!-- Intentos de Conexión -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Tasa de Éxito</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ successRate }}% de conexiones exitosas</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <Chart type="doughnut" :data="connectionAttemptsData" :options="connectionAttemptsOptions" class="w-full" style="height: 280px;" />
                        <div class="mt-6 w-full space-y-3">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <span class="text-sm text-gray-700">Exitosas</span>
                                </div>
                                <span class="font-black text-green-600">4,250</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <span class="text-sm text-gray-700">Fallidas</span>
                                </div>
                                <span class="font-black text-red-600">380</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clientes por Grupo -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Clientes por Plan</h3>
                        <p class="text-sm text-gray-500 mt-1">Distribución total: {{ props.totalClient }} clientes</p>
                    </div>
                    <Chart type="pie" :data="usersByGroupData" :options="usersByGroupOptions" class="w-full" style="height: 320px;" />
                </div>

                <!-- Usuarios por NAS -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Usuarios por NAS</h3>
                        <p class="text-sm text-gray-500 mt-1">Conexiones activas por dispositivo</p>
                    </div>
                    <Chart type="bar" :data="usersByNASData" :options="usersByNASOptions" class="w-full" style="height: 320px;" />
                </div>
            </div>

            <!-- Resumen estadístico adicional -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Promedio Conexiones</p>
                            <p class="text-4xl font-black mt-2">650</p>
                            <p class="text-blue-100 text-xs mt-2">Por día</p>
                        </div>
                        <div class="text-6xl opacity-20">📊</div>
                    </div>
                    <div class="w-full bg-blue-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full animate-pulse" style="width: 75%"></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-green-100 text-sm font-semibold uppercase tracking-wider">Disponibilidad NAS</p>
                            <p class="text-4xl font-black mt-2">99.8%</p>
                            <p class="text-green-100 text-xs mt-2">Sistema operativo</p>
                        </div>
                        <div class="text-6xl opacity-20">✓</div>
                    </div>
                    <div class="w-full bg-green-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 99.8%"></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider">Tiempo Promedio</p>
                            <p class="text-4xl font-black mt-2">4.2h</p>
                            <p class="text-purple-100 text-xs mt-2">Por sesión</p>
                        </div>
                        <div class="text-6xl opacity-20">⏱</div>
                    </div>
                    <div class="w-full bg-purple-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
