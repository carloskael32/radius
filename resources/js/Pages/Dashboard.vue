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

    // Usuarios por NAS (desde el controlador)
    usersByNas: {
        type: Array,
        default: () => []
    },

    // Conexiones exitosas y fallidas desde radpostauth
    successfulAttempts: {
        type: Number,
        default: 0
    },
    failedAttempts: {
        type: Number,
        default: 0
    },

    // Datos diarios para el gráfico de línea (desde radpostauth)
    dailyLabels: {
        type: Array,
        default: () => ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
    },
    dailySuccess: {
        type: Array,
        default: () => []
    },
    dailyFailed: {
        type: Array,
        default: () => []
    },
    online: {
        type: Array,
        default: () => []
    },
    offline: {
        type: Array,
        default: () => []
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

// Datos para gráfico de línea - Conexiones diarias (dinámico desde radpostauth)
const dailyConnectionsData = computed(() => ({
    labels: props.dailyLabels,
    datasets: [
        {
            label: 'Conexiones Exitosas',
            data: props.dailySuccess,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        },
        {
            label: 'Conexiones Fallidas',
            data: props.dailyFailed,
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }
    ]
}));

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

// Datos para gráfico de barras horizontal - Total de usuarios por NAS (dinámico desde el backend)
const usersByNASData = computed(() => {
    const labels = props.usersByNas.map(item => item.name);
    const data = props.usersByNas.map(item => item.count);
    const backgroundColors = labels.map((_, index) => chartColors[index % chartColors.length]);

    return {
        labels,
        datasets: [{
            label: 'Total de Usuarios',
            data,
            backgroundColor: backgroundColors,
            borderColor: backgroundColors.map(c => c + '80'),
            borderWidth: 1
        }]
    };
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

// Datos para gráfico de intento de conexión (Pie) - dinámico desde radpostauth
const connectionAttemptsData = computed(() => ({
    labels: ['Exitosos', 'Fallidos'],
    datasets: [{
        data: [props.successfulAttempts, props.failedAttempts],
        backgroundColor: ['#10b981', '#ef4444'],
        borderColor: '#ffffff',
        borderWidth: 2
    }]
}));

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

// Métrica adicional - Tasa de éxito (dinámica)
const successRate = computed(() => {
    const total = props.successfulAttempts + props.failedAttempts;
    if (total === 0) return '0.0';
    return ((props.successfulAttempts / total) * 100).toFixed(1);
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
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm hover:shadow-md transition-all p-4 border border-blue-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total
                                    Clientes</span>

                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.totalClient }}</p>

                        </div>
                        <div class="bg-blue-500 p-3 rounded-2xl shadow-lg">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>

                        </div>
                    </div>
                </div>

                <!-- Total NAS -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm hover:shadow-md transition-all p-4 border border-purple-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total
                                    NAS</span>
                                <!-- <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-purple-500">Activos</span> -->
                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.totalNAS }}</p>

                        </div>
                        <div class="bg-purple-500 p-3 rounded-2xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                            </svg>

                        </div>
                    </div>
                </div>

                <!-- Usuarios Conectados -->
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm hover:shadow-md transition-all p-4 border border-green-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Clientes
                                    Activos</span>


                            </div>

                            <p class="text-4xl font-black text-gray-900">{{ props.connectedClient }}</p>

                        </div>
                        <div class="bg-green-500 p-3 rounded-2xl shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <span class="rounded-full text-xs font-semibold text-white bg-green-500">{{
                                ((props.connectedClient / props.totalClient) * 100).toFixed(1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Usuarios Desconectados -->
                <div
                    class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl shadow-sm hover:shadow-md transition-all p-4 border border-red-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Clientes
                                    inactivos</span>

                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.disconnectedClient }}</p>

                        </div>
                        <div class="bg-red-500 p-3 rounded-2xl shadow-lg">
                            <svg class="size-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                            <span class="rounded-full text-xs font-semibold text-white bg-red-500">{{
                                ((props.disconnectedClient / props.totalClient) * 100).toFixed(1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Clientes Sin Grupo -->
                <div
                    class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-sm hover:shadow-md transition-all p-4 border border-orange-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">clientes sin
                                    plan</span>

                            </div>
                            <p class="text-4xl font-black text-gray-900">{{ props.clientesSinGrupo }}</p>

                        </div>
                        <div class="bg-orange-500 p-3 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <span class="rounded-full text-xs font-semibold text-white bg-orange-500">{{
                                ((props.clientesSinGrupo / props.totalClient) * 100).toFixed(1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CLIENTES EN ACTIVOS EN LINEA Y CLIENTES DESCONECTADOS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- clientes en ONLINE -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">🟢 Clientes en Linea </h3>
                        <p class="text-sm text-gray-500 mt-1">Hoy</p>
                    </div>

                    <div v-if="online.length > 0" class="max-h-64 overflow-y-auto">
                      
                        <table class="w-full text-sm">
                            <thead class="text-center bg-gray-100">
                                <tr>
                                    <td>Usuarios</td>
                                    <td>NAs</td>
                                    
                                </tr>                                
                            </thead>
                            <tbody class="divide-y divide-green-200">
                                <tr v-for="on in online" :key="on.username" class="hover:bg-green-100">
                                    <td class="px-2 py-2 font-medium">{{ on.username }}</td>
                                    <td class="px-2 py-2 text-gray-600 text-xs">{{ on.nasipaddress }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- clientes OFFLINE -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">🔴 Cientes Fuera de Linea </h3>
                        <p class="text-sm text-gray-500 mt-1">Hoy</p>
                    </div>
                    <div v-if="offline.length > 0" class="max-h-64 overflow-y-auto">
                        <table class="w-full text-sm">
                            <thead class="text-center bg-gray-100">
                                <tr>
                                    <td>Usuarios</td>
                                    <td>NAS</td>
                                    <td>Ultima Conexion</td>
                                </tr>                            
                            </thead>
                            <tbody class="divide-y divide-green-200">
                                <tr v-for="off in offline" :key="off.username" class="hover:bg-green-100">
                                    <td class="px-2 py-2 font-medium">{{ off.username }}</td>
                                    <td class="px-2 py-2 text-gray-600 text-xs">{{ off.nasipaddress }}</td>
                                    <td class="px-2 py-2 text-gray-600 text-xs">{{ off.acctstoptime }}

                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                    <Chart type="line" :data="dailyConnectionsData" :options="dailyConnectionsOptions" class="w-full"
                        style="height: 320px;" />
                </div>

                <!-- Intentos de Conexión -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Tasa de Éxito</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ successRate }}% de conexiones exitosas</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <Chart type="doughnut" :data="connectionAttemptsData" :options="connectionAttemptsOptions"
                            class="w-full" style="height: 280px;" />
                        <div class="mt-6 w-full space-y-3">
                            <div
                                class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <span class="text-sm text-gray-700">Exitosas</span>
                                </div>
                                <span class="font-black text-green-600">{{ props.successfulAttempts.toLocaleString()
                                    }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <span class="text-sm text-gray-700">Fallidas</span>
                                </div>
                                <span class="font-black text-red-600">{{ props.failedAttempts.toLocaleString() }}</span>
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
                    <Chart type="pie" :data="usersByGroupData" :options="usersByGroupOptions" class="w-full"
                        style="height: 320px;" />
                </div>

                <!-- Usuarios por NAS -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-lg transition-all">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-gray-900">Usuarios por NAS</h3>
                        <p class="text-sm text-gray-500 mt-1">Total de usuarios por Dispositivo</p>
                    </div>
                    <Chart type="bar" :data="usersByNASData" :options="usersByNASOptions" class="w-full"
                        style="height: 320px;" />
                </div>
            </div>



            <!-- Resumen estadístico adicional -->
            <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Promedio Conexiones
                            </p>
                            <p class="text-4xl font-black mt-2">650</p>
                            <p class="text-blue-100 text-xs mt-2">Por día</p>
                        </div>
                        <div class="text-6xl opacity-20">📊</div>
                    </div>
                    <div class="w-full bg-blue-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full animate-pulse" style="width: 75%"></div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-green-100 text-sm font-semibold uppercase tracking-wider">Disponibilidad NAS
                            </p>
                            <p class="text-4xl font-black mt-2">99.8%</p>
                            <p class="text-green-100 text-xs mt-2">Sistema operativo</p>
                        </div>
                        <div class="text-6xl opacity-20">✓</div>
                    </div>
                    <div class="w-full bg-green-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 99.8%"></div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider">Tiempo Promedio
                            </p>
                            <p class="text-4xl font-black mt-2">4.2h</p>
                            <p class="text-purple-100 text-xs mt-2">Por sesión</p>
                        </div>
                        <div class="text-6xl opacity-20">⏱</div>
                    </div>
                    <div class="w-full bg-purple-400 bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 60%"></div>
                    </div>
                </div>
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>
