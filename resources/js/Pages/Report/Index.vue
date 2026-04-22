<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';


//datatable primevue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';


const props = defineProps({
    bandwidthData: Object,
    topConsumers: Array,
    monthlyComparison: Array,
    disconnectionData: Object,
    activeSessionsCount: Object,
    failedAuthCount: Object,
    nasStats: Object,
    clients: { type: Array },
    grupos: { type: Array },
});


//para el filter
const FilterMatchMode = {
    CONTAINS: 'contains',
};

//para el boton de filtrado de datos
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS } // CORREGIDO
});

//para exportar la informacion
// Función para exportar a CSV
const dt = ref();

const exportCSV = () => {
    if (dt.value) {
        dt.value.exportCSV({
            selectionOnly: false, // Exportar todos los datos
            fileName: 'usuarios.csv'
        });
    }
};




// Estados y datos
const activeTab = ref('bandwidth');
const userStats = ref([]);
const userHistory = ref([]);
const loading = ref(false);

const bandwidthUsername = ref('');
const bandwidthStartDate = ref('');
const bandwidthEndDate = ref('');
const bandwidthStats = ref([]);
const bandwidthLoading = ref(false);

const nasSearchUsername = ref('');
const nasSearchResults = ref([]);
const nasSearchLoading = ref(false);

const toast = useToast();

// Filtros para desconexiones
const disconnectionUsername = ref('');
const disconnectionStartDate = ref('');
const disconnectionEndDate = ref('');
const disconnectionStats = ref([]);
const disconnectionLoading = ref(false);

// Estados para seguridad
const failedAuthAttempts = ref([]);
const simultaneousSessions = ref([]);

// Estados para clientes
const newClientsThisWeek = ref([]);
const inactiveClients = ref([]);
const clientsWithoutPlan = ref([]);

// Tipos de reportes disponibles
const reportTypes = [
    { id: 'bandwidth', label: 'Ancho de Banda', icon: '📊' },
    { id: 'disconnections', label: 'Desconexiones', icon: '🔌' },
    { id: 'clients', label: 'Clientes', icon: '👥' },
    { id: 'security', label: 'Seguridad', icon: '🔒' },
    { id: 'nas', label: 'NAS/Nodos', icon: '🏢' },
];

// Métodos de carga de datos
const fetchUserBandwidthStats = async () => {
    bandwidthLoading.value = true;
    try {
        const response = await axios.get('/reports/user-bandwidth-stats', {
            params: {
                username: bandwidthUsername.value,
                start_date: bandwidthStartDate.value,
                end_date: bandwidthEndDate.value,
            }
        });
        bandwidthStats.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        bandwidthLoading.value = false;
    }
};

const fetchDisconnectionStats = async () => {
    disconnectionLoading.value = true;
    try {
        const response = await axios.get('/reports/disconnection-stats', {
            params: {
                username: disconnectionUsername.value || null,
                start_date: disconnectionStartDate.value,
                end_date: disconnectionEndDate.value,
            }
        });
        disconnectionStats.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        disconnectionLoading.value = false;
    }
};

const fetchNasByUser = async () => {
    nasSearchLoading.value = true;
    try {
        if (!nasSearchUsername.value) {
            nasSearchResults.value = [];
            return;
        }

        const response = await axios.get('/reports/nas-by-user', {
            params: {
                username: nasSearchUsername.value,
            }
        });

        nasSearchResults.value = response.data.data || [];
    } catch (error) {
        console.error('Error fetching NAS by user:', error);
        toast.add({ severity: 'error', summary: 'Error al buscar el usuario.', detail: '', life: 3000 });
    } finally {
        nasSearchLoading.value = false;
    }
};

const resetNasSearch = () => {
    nasSearchUsername.value = '';
    nasSearchResults.value = [];
};

const fetchFailedAuthAttempts = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/reports/failed-auth-attempts', {
            params: { days: 7 }
        });
        failedAuthAttempts.value = response.data.top_failed_users;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

const fetchSimultaneousSessions = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/reports/simultaneous-sessions');
        simultaneousSessions.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

// Funciones para clientes
const fetchNewClientsThisWeek = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/reports/new-clients-this-week');
        newClientsThisWeek.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

const fetchInactiveClients = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/reports/inactive-clients');
        inactiveClients.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

const fetchClientsWithoutPlan = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/reports/clients-without-plan');
        clientsWithoutPlan.value = response.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
};

// Funciones de exportación
const getExportParams = (type, extraData = null) => {
    const params = { type };
    if (type === 'bandwidth') {
        params.username = bandwidthUsername.value;
        if (bandwidthStartDate.value) params.start_date = bandwidthStartDate.value;
        if (bandwidthEndDate.value) params.end_date = bandwidthEndDate.value;
    } else if (type === 'disconnections') {
        params.username = disconnectionUsername.value || null;
        if (disconnectionStartDate.value) params.start_date = disconnectionStartDate.value;
        if (disconnectionEndDate.value) params.end_date = disconnectionEndDate.value;
    } else if (type === 'nas_individual') {
        params.nas_ip = extraData;
    }
    return params;
};

const exportToFormat = async (type, format, extraData = null) => {
    // Validación para bandwidth: requiere al menos un filtro
    if (type === 'bandwidth' && !bandwidthUsername.value && !bandwidthStartDate.value && !bandwidthEndDate.value) {
        toast.add({ severity: 'warn', summary: 'Por favor, ingresa al menos un valor en usuario, fecha inicio o fecha fin para generar el reporte.', detail: '', life: 3000 });
        return;
    }

    // Validación para disconnections: requiere fechas
    if (type === 'disconnections' && (!disconnectionStartDate.value || !disconnectionEndDate.value)) {
        toast.add({ severity: 'warn', summary: 'Por favor, ingresa las fechas de inicio y fin para generar el reporte de desconexiones..', detail: '', life: 3000 });
        return;
    }

    try {
        const url = format === 'excel'
            ? '/reports/export-excel'
            : '/reports/export-pdf';
        const response = await axios.get(url, {
            params: getExportParams(type, extraData),
        });

        if (format === 'excel') {
            if (!response.data.data || response.data.data.length === 0) {
                toast.add({ severity: 'warn', summary: 'No hay datos para exportar con los filtros actuales.', life: 3000 });
                return;
            }
            downloadExcel(response.data.data, response.data.filename);
        } else {
            console.log('PDF export data:', response.data);
        }
    } catch (error) {
        console.error('Error exporting:', error);
        toast.add({ severity: 'info', summary: 'No hay resultados con los filtros actuales, Ajusta el usuario o fechas y vuelve a intentar', detail: '', life: 3000 });
    }
};

const downloadExcel = (data, filename) => {
    // Crear CSV
    const csv = [Object.keys(data[0]).join(',')];
    data.forEach(row => {
        csv.push(Object.values(row).map(v => `"${v}"`).join(','));
    });

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

const resetBandwidthFilters = () => {
    bandwidthUsername.value = '';
    bandwidthStartDate.value = '';
    bandwidthEndDate.value = '';
    bandwidthStats.value = [];
};

const resetDisconnectionFilters = () => {
    disconnectionUsername.value = '';
    disconnectionStartDate.value = '';
    disconnectionEndDate.value = '';
    disconnectionStats.value = [];
};

const loadTabData = () => {
    if (activeTab.value === 'bandwidth') {
        // No cargar automáticamente, esperar a que el usuario haga clic en Buscar
    } else if (activeTab.value === 'clients') {
        fetchNewClientsThisWeek();
        fetchInactiveClients();
        fetchClientsWithoutPlan();
    } else if (activeTab.value === 'security') {
        fetchFailedAuthAttempts();
        fetchSimultaneousSessions();
    }
};

// Watchers
watch(activeTab, () => {
    loadTabData();
});

onMounted(() => {
    loadTabData();
});
</script>

<template>

    <Head title="Reportes RADIUS Completos" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <!-- Encabezado -->
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                <h1 class="text-4xl font-bold text-gray-900">Reportes RADIUS</h1>
                <p class="text-gray-600 mt-1">Análisis completo de tu infraestructura de red</p>
            </div>

            <!-- Tabs de navegación -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                <button v-for="tab in reportTypes" :key="tab.id" @click="activeTab = tab.id" :class="[
                    'p-3 rounded-lg border-2 transition-all text-center hover:shadow-md',
                    activeTab === tab.id
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-200 hover:border-gray-300 bg-white'
                ]">
                    <div class="text-2xl mb-1">{{ tab.icon }}</div>
                    <p :class="[
                        'font-medium text-xs',
                        activeTab === tab.id ? 'text-blue-900' : 'text-gray-700'
                    ]">
                        {{ tab.label }}
                    </p>
                </button>
            </div>

            <!-- 📊 ANCHO DE BANDA -->
            <div v-if="activeTab === 'bandwidth'" class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Consumo de Ancho de Banda</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Descargado (Input)</p>
                                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ bandwidthData?.totalInput }}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400 opacity-50"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Subido (Output)</p>
                                    <p class="text-3xl font-bold text-green-600 mt-2">{{ bandwidthData?.totalOutput }}
                                    </p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-400 opacity-50"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Combinado</p>
                                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ bandwidthData?.combined }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Top Consumer y Comparativo -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Usuarios Semanal</h3>
                        <div v-if="topConsumers && topConsumers.length > 0" class="overflow-y-auto max-h-96">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="user in topConsumers.slice(0, 10)" :key="user.username"
                                        class="hover:bg-gray-50">
                                        <td class="px-3 py-2">
                                            <span v-if="user.rank === 1" class="text-lg">🥇</span>
                                            <span v-else-if="user.rank === 2" class="text-lg">🥈</span>
                                            <span v-else-if="user.rank === 3" class="text-lg">🥉</span>
                                            <span v-else class="font-medium">{{ user.rank }}</span>
                                        </td>
                                        <td class="px-3 py-2 font-medium">{{ user.username }}</td>
                                        <td class="px-3 py-2 text-right text-purple-600 font-bold">{{ user.combined }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Últimos 12 Meses</h3>
                        <div v-if="monthlyComparison && monthlyComparison.length > 0" class="overflow-y-auto max-h-96">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="(month, idx) in monthlyComparison.slice(0, 12)" :key="idx"
                                        :class="idx === 0 ? 'bg-yellow-50' : 'hover:bg-gray-50'">
                                        <td class="px-3 py-2 font-medium">{{ month.period }}</td>
                                        <td class="px-3 py-2 text-right text-purple-600 font-bold">{{ month.combined }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- REPORTE POR USUARIO CON FECHA INICIO Y FIN -->
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <form @submit.prevent="fetchUserBandwidthStats">
                        <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-4">
                            <div class="xl:col-span-2 grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                                    <input v-model="bandwidthUsername" type="text" placeholder="Buscar usuario..."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                                </div>
                            </div>

                            <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha inicio</label>
                                    <input v-model="bandwidthStartDate" type="date"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                        required="" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha fin</label>
                                    <input v-model="bandwidthEndDate" type="date"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                        required="" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                            <div class="flex gap-2 flex-wrap">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Buscar</button>
                                <button type="button" @click="resetBandwidthFilters"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Limpiar</button>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="exportToFormat('bandwidth', 'excel')"
                                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">📊
                                    Excel</button>
                            </div>
                        </div>
                    </form>

                    <div class="space-y-3">
                        <div class="text-sm text-gray-600">Filtra por usuario y rango de fechas para ver consumo
                            detallado. <b>Si no ingresa el usuario se obtendra el resultado de todos los usuarios, en
                                las fechas establecidas.</b></div>
                        <div v-if="bandwidthLoading" class="text-sm text-gray-600">Cargando resultados...</div>
                        <div v-else-if="bandwidthStats.length > 0"
                            class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-slate-100 text-left text-xs uppercase tracking-wide text-slate-600">
                                        <th class="px-4 py-3">Usuario</th>
                                        <th class="px-4 py-3">Descargado</th>
                                        <th class="px-4 py-3">Subido</th>
                                        <th class="px-4 py-3">Total</th>
                                        <th class="px-4 py-3">Sesiones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in bandwidthStats" :key="item.Usuario" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-slate-900">{{ item.username }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ item.input }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ item.output }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ item.combinedBytes }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ item.sessions }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-sm text-gray-500">No hay resultados con los filtros actuales. Ajusta
                            usuario o fechas y vuelve a buscar.</div>
                    </div>
                </div>
            </div>

            <!-- 🔌 DESCONEXIONES -->
            <div v-if="activeTab === 'disconnections'" class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Análisis de Desconexiones</h2>
                    </div>



                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="font-bold text-gray-900 mb-3">Causas de Corte (Hoy)</h4>
                            <div v-if="disconnectionData?.disconnectReasons" class="space-y-2">
                                <div v-for="reason in disconnectionData.disconnectReasons"
                                    :key="reason.acctterminatecause" class="flex justify-between text-sm">
                                    <span class="text-gray-700">{{ reason.acctterminatecause }}</span>
                                    <span class="font-bold text-red-600">{{ reason.count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="font-bold text-gray-900 mb-3">Desconexiones (Hoy)</h4>
                            <div v-if="disconnectionData?.topDisconnectors" class="space-y-2 max-h-64 overflow-y-auto">
                                <div v-for="user in disconnectionData.topDisconnectors.slice(0, 5)" :key="user.username"
                                    class="text-sm">
                                    <p class="font-medium text-gray-900">{{ user.username }}</p>
                                    <p class="text-gray-600">{{ user.disconnections }} desconexiones</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="font-bold text-gray-900 mb-3">Sesiones Largas (Hoy)</h4>
                            <div v-if="disconnectionData?.sessionStats" class="space-y-2 max-h-64 overflow-y-auto">
                                <div v-for="stat in disconnectionData.sessionStats.slice(0, 5)" :key="stat.username"
                                    class="text-sm">
                                    <p class="font-medium text-gray-900">{{ stat.username }}</p>
                                    <p class="text-gray-600 text-xs">Prom: {{ stat.avgSessionTime }}</p>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Buscador de Desconexiones -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Buscar Desconexiones</h3>
                        <form @submit.prevent="fetchDisconnectionStats">
                            <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-4">
                                <div class="xl:col-span-2 grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Usuario
                                            (opcional)</label>
                                        <input v-model="disconnectionUsername" type="text"
                                            placeholder="Buscar usuario o dejar vacío para todos..."
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                                    </div>
                                </div>

                                <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha inicio</label>
                                        <input v-model="disconnectionStartDate" type="date"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                            required="" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha fin</label>
                                        <input v-model="disconnectionEndDate" type="date"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                            required="" />
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                                <div class="flex gap-2 flex-wrap">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Buscar</button>
                                    <button type="button" @click="resetDisconnectionFilters"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Limpiar</button>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="exportToFormat('disconnections', 'excel')"
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">📊
                                        Excel</button>
                                </div>
                            </div>
                        </form>

                        <div class="space-y-3">
                            <div class="text-sm text-gray-600">Filtra por usuario y rango de fechas para ver consumo
                                detallado. <b>Si no ingresa el usuario se obtendra el resultado de todos los usuarios,
                                    en las fechas establecidas.</b></div>
                            <div v-if="disconnectionLoading" class="text-sm text-gray-600">Cargando resultados...</div>
                            <div v-else-if="disconnectionStats.length > 0"
                                class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr
                                            class="bg-slate-100 text-left text-xs uppercase tracking-wide text-slate-600">
                                            <th class="px-4 py-3">Usuario</th>
                                            <th class="px-4 py-3">Fecha</th>
                                            <th class="px-4 py-3">Causa</th>
                                            <th class="px-4 py-3">Duración</th>
                                            <th class="px-4 py-3">IP NAS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="item in disconnectionStats" :key="item.id" class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium text-slate-900">{{ item.username }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ item.disconnect_time }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ item.acctterminatecause }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ item.session_time }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ item.nasipaddress }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="text-sm text-gray-500">No hay resultados con los filtros actuales. Ajusta
                                usuario o fechas y vuelve a buscar.</div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- 👥 CLIENTES -->
            <div v-if="activeTab === 'clients'" class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Gestión de Clientes</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-bold text-green-900">Nuevos Clientes (Esta Semana)</h4>
                                <button @click="fetchNewClientsThisWeek"
                                    class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    Cargar
                                </button>
                            </div>
                            <div class="text-xs text-green-700 mb-2">Total: {{ newClientsThisWeek.length }} clientes
                            </div>
                            <div v-if="newClientsThisWeek.length > 0" class="max-h-64 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-green-200">
                                        <tr v-for="client in newClientsThisWeek" :key="client.username"
                                            class="hover:bg-green-100">
                                            <td class="px-2 py-2 font-medium">{{ client.username }}</td>
                                            <td class="px-2 py-2 text-gray-600 text-xs">{{ client.nombre_completo }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-bold text-yellow-900">Clientes Inactivos</h4>
                                <button @click="fetchInactiveClients"
                                    class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                    Cargar
                                </button>
                            </div>
                            <div class="text-xs text-yellow-700 mb-2">Total: {{ inactiveClients.length }} clientes</div>
                            <div v-if="inactiveClients.length > 0" class="max-h-64 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-yellow-200">
                                        <tr v-for="client in inactiveClients" :key="client.username"
                                            class="hover:bg-yellow-100">
                                            <td class="px-2 py-2 font-medium">{{ client.username }}</td>
                                            <td class="px-2 py-2 text-gray-600 text-xs">{{ client.nombre_completo }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-bold text-purple-900">Sin Plan Asignado</h4>
                                <button @click="fetchClientsWithoutPlan"
                                    class="text-xs bg-purple-500 text-white px-2 py-1 rounded hover:bg-purple-600">
                                    Cargar
                                </button>
                            </div>
                            <div class="text-xs text-purple-700 mb-2">Total: {{ clientsWithoutPlan.length }} clientes
                            </div>
                            <div v-if="clientsWithoutPlan.length > 0" class="max-h-64 overflow-y-auto">
                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-purple-200">
                                        <tr v-for="client in clientsWithoutPlan" :key="client.username"
                                            class="hover:bg-purple-100">
                                            <td class="px-2 py-2 font-medium">{{ client.username }}</td>
                                            <td class="px-2 py-2 text-gray-600 text-xs">{{ client.nombre_completo }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- tabla de todos los clientes -->
                    <div class="w-full overflow-hidden border border-gray-200 rounder-lg p-2 mt-4">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <DataTable :value="clients" v-model:filters="filters" ref="dt" selectionMode="single"
                                :globalFilterFields="['username', 'nombre_completo', 'email', 'telefono', 'direccion', 'estado', 'plan']"
                                paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                                paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                                currentPageReportTemplate="{first} a {last} de {totalRecords}">
                                <template #header>
                                    <!-- Filtro de búsqueda -->
                                    <div
                                        class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                                        <div class="w-full md:w-auto">
                                            <IconField iconPosition="left" class="w-full md:w-64">
                                                <InputIcon>
                                                    <i class="pi pi-search" />
                                                </InputIcon>
                                                <InputText v-model="filters['global'].value"
                                                    placeholder="Buscar cliente..." class="w-full pl-8 rounded-lg" />
                                            </IconField>
                                        </div>
                                        <!-- Botones de exportación -->
                                        <div class="flex gap-2">

                                            <Button label="📊 Excel" @click="exportCSV" size="large" raised
                                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm" />
                                        </div>
                                    </div>
                                </template>

                                <Column field="username" sortable header="username"
                                    headerClass=" border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                </Column>
                                <Column field="nombre_completo" sortable header="Nombre completo"
                                   style="min-width: 250px;"
                                    headerClass=" border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                </Column>
                                <Column field="email" sortable header="correo"
                                    headerClass="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                </Column>
                                <Column field="telefono" sortable header="telefono"
                                    headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                </Column>
                                <Column field="direccion" sortable header="direccion"
                                    style="min-width: 250px;"
                                    headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300 whitespace-normal break-words">
                                </Column>


                                <Column field="plan" sortable header="plan"
                                    headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                    <template #body="{ data }">
                                        <span v-if="data.plan"
                                            class="bg-green-400 text-green-900 inline-block px-3 rounded-sm font-semibold">
                                            {{ data.plan }}
                                        </span>
                                        <span v-else
                                            class="bg-yellow-300 text-red-900 inline-block px-3 rounded-xl font-semibold">
                                            ninguno
                                        </span>
                                    </template>
                                </Column>

                                 <Column field="estado" sortable header="estado"
                                    headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300 text-center">
                                    <template #body="{ data }">
                                        <span v-if="data.estado == 'activo'"
                                            class="bg-green-600 text-white inline-block px-3 rounded-xl font-semibold">
                                            {{ data.estado }}
                                        </span>
                                        <span v-else
                                            class="bg-red-600 text-white inline-block px-3 rounded-xl font-semibold">
                                            inactivo
                                        </span>
                                    </template>
                                </Column>

                                <Column field="observaciones" sortable header="observacion"
                                   style="min-width: 250px;"
                                    headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                                    bodyClass="border border-gray-300">
                                </Column>





                            </DataTable>
                        </div>
                    </div>


                </div>
            </div>
            <!--  SEGURIDAD -->
            <div v-if="activeTab === 'security'" class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100 ">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Monitoreo de Seguridad</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-red-900">Intentos Fallidos</h4>
                                <button @click="fetchFailedAuthAttempts"
                                    class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Cargar
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="bg-white p-3 rounded border border-red-200">
                                    <p class="text-xs text-gray-600">Hoy</p>
                                    <p class="text-2xl font-bold text-red-600">{{ failedAuthCount?.today || 0 }}</p>
                                </div>
                                <div class="bg-white p-3 rounded border border-red-200">
                                    <p class="text-xs text-gray-600">Ayer</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ failedAuthCount?.yesterday || 0 }}
                                    </p>
                                </div>
                            </div>
                            <div v-if="failedAuthAttempts.length > 0" class="max-h-48 overflow-y-auto">
                                <table class="w-full text-xs">
                                    <tbody class="divide-y divide-red-200">
                                        <tr v-for="user in failedAuthAttempts.slice(0, 5)" :key="user.username"
                                            class="hover:bg-red-100">
                                            <td class="px-2 py-1 font-medium">{{ user.username }}</td>
                                            <td class="px-2 py-1 text-right text-red-600 font-bold">{{
                                                user.failed_attempts }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-orange-900">Sesiones Simultáneas</h4>
                                <button @click="fetchSimultaneousSessions"
                                    class="text-xs bg-orange-500 text-white px-2 py-1 rounded hover:bg-orange-600">
                                    Cargar
                                </button>
                            </div>
                            <div v-if="simultaneousSessions.length > 0" class="max-h-56 overflow-y-auto">
                                <table class="w-full text-xs">
                                    <tbody class="divide-y divide-orange-200">
                                        <tr v-for="session in simultaneousSessions" :key="session.username"
                                            class="hover:bg-orange-100">
                                            <td class="px-2 py-1 font-medium">{{ session.username }}</td>
                                            <td class="px-2 py-1 text-right">
                                                <span
                                                    class="bg-orange-600 text-white px-2 py-1 rounded text-xs font-bold">{{
                                                        session.active_sessions }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-bold text-blue-900 mb-4">Resumen de Seguridad</h4>
                            <div class="space-y-3">
                                <div
                                    class="flex justify-between items-center p-2 bg-white rounded border border-blue-200">
                                    <span class="text-sm text-gray-700">Auth. Fallidas (Hoy)</span>
                                    <span class="text-lg font-bold text-red-600">{{ failedAuthCount?.today || 0
                                        }}</span>
                                </div>
                                <div
                                    class="flex justify-between items-center p-2 bg-white rounded border border-blue-200">
                                    <span class="text-sm text-gray-700">Sesiones Múltiples</span>
                                    <span class="text-lg font-bold text-orange-600">{{ simultaneousSessions.length || 0
                                        }}</span>
                                </div>
                                <div class="p-3 bg-green-100 rounded border border-green-300 text-sm text-green-800">
                                    ✓ Sistema funcionando correctamente
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🏢 NAS/NODOS -->
            <div v-if="activeTab === 'nas'" class="space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Infraestructura NAS/Nodos</h2>
                        </div>
                        <div class="flex gap-2 flex-wrap items-center">
                            <button @click="exportToFormat('nas_stats', 'excel')"
                                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">📊
                                Excel</button>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar usuario</label>
                                <input v-model="nasSearchUsername" type="text" placeholder="Nombre de usuario"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                            </div>
                            <div class="md:col-span-2 flex flex-wrap gap-2">
                                <button type="button" @click="fetchNasByUser"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Buscar</button>
                                <button type="button" @click="resetNasSearch"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">Limpiar</button>
                                <div v-if="nasSearchLoading" class="text-sm text-gray-600 self-center">Buscando...</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="nasSearchUsername && nasSearchResults.length === 0 && !nasSearchLoading"
                        class="mb-4 text-sm text-gray-500">No se encontraron NAS para ese usuario.</div>

                    <div v-if="nasSearchResults.length > 0"
                        class="overflow-x-auto rounded-lg border border-gray-200 mb-6">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-indigo-100 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">IP del NAS</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Usuario</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Descargado</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Subido</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="result in nasSearchResults" :key="result.nasIp + result.username"
                                    class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ result.nasIp }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ result.username || nasSearchUsername }}</td>
                                    <td class="px-6 py-4 text-sm text-blue-600 font-medium">{{ result.totalInput }}</td>
                                    <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ result.totalOutput }}</td>
                                    <td class="px-6 py-4 text-sm text-purple-600 font-medium">{{ result.combined }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="!nasSearchUsername"
                        class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-indigo-100 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">IP del NAS</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Sesiones Totales
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Usuarios Únicos
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Descargado</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Subido</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Total</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="nas in nasStats.stats" :key="nas.nasIp" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ nas.nasIp }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ nas.totalSessions }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ nas.uniqueUsers }}</td>
                                    <td class="px-6 py-4 text-sm text-blue-600 font-medium">{{ nas.totalInput }}</td>
                                    <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ nas.totalOutput }}</td>
                                    <td class="px-6 py-4 text-sm text-purple-600 font-medium">{{ nas.combined }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <button @click="exportToFormat('nas_individual', 'excel', nas.nasIp)"
                                            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs">📊
                                            Excel</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="nasStats?.disconnections && nasStats.disconnections.length > 0" class="mt-6">
                        <h4 class="font-bold text-gray-900 mb-3">NAS con Más Desconexiones Inesperadas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div v-for="nas in nasStats.disconnections" :key="nas.nasipaddress"
                                class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="font-medium text-gray-900">{{ nas.nasipaddress }}</p>
                                <p class="text-2xl font-bold text-red-600">{{ nas.unexpected_disconnects }}</p>
                                <p class="text-xs text-gray-600">desconexiones inesperadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animaciones opcionales */
@media (prefers-reduced-motion: no-preference) {
    button {
        transition: all 0.2s ease;
    }

    tr {
        transition: background-color 0.15s ease;
    }
}
</style>
