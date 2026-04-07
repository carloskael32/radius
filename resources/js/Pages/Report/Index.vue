<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps({
    // Props para pasar datos después
});

// Estados y datos
const filterType = ref('usuarios'); // usuarios, conexiones, ingresos
const dateFrom = ref('');
const dateTo = ref('');
const searchQuery = ref('');
const selectedFormat = ref('view'); // view, pdf, csv, excel

// Tipos de reportes disponibles
const reportTypes = [
    { id: 'usuarios', label: 'Reporte de Usuarios', icon: '👥' },
    { id: 'conexiones', label: 'Reporte de Conexiones', icon: '🔗' },
    { id: 'ingresos', label: 'Reporte de Ingresos', icon: '💰' },
    { id: 'actividad', label: 'Reporte de Actividad', icon: '📊' },
];

// Métodos
const generateReport = () => {
    console.log('Generando reporte...', {
        type: filterType.value,
        from: dateFrom.value,
        to: dateTo.value,
        search: searchQuery.value,
        format: selectedFormat.value
    });
};

const exportReport = (format) => {
    console.log('Exportando en formato:', format);
};

const resetFilters = () => {
    dateFrom.value = '';
    dateTo.value = '';
    searchQuery.value = '';
    filterType.value = 'usuarios';
};
</script>

<template>
    <Head title="Reportes" />

    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <!-- Encabezado -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Reportes</h1>
                <p class="text-gray-600 mt-1">Genera y descarga reportes de tu sistema ISP</p>
            </div>

            <!-- Selección de tipo de reporte -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <button 
                    v-for="report in reportTypes" 
                    :key="report.id"
                    @click="filterType = report.id"
                    :class="[
                        'p-4 rounded-lg border-2 transition-all text-center',
                        filterType === report.id 
                            ? 'border-blue-500 bg-blue-50' 
                            : 'border-gray-200 hover:border-gray-300 bg-white'
                    ]"
                >
                    <div class="text-3xl mb-2">{{ report.icon }}</div>
                    <p :class="[
                        'font-medium text-sm',
                        filterType === report.id ? 'text-blue-900' : 'text-gray-700'
                    ]">
                        {{ report.label }}
                    </p>
                </button>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 p-6 rounded-lg mb-8 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <!-- Fecha Desde -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                        <input 
                            type="date" 
                            v-model="dateFrom"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Fecha Hasta -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                        <input 
                            type="date" 
                            v-model="dateTo"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Usuario, email, etc..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <!-- Formato de Exportación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Formato</label>
                        <select 
                            v-model="selectedFormat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="view">Ver en pantalla</option>
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex gap-3 justify-end">
                    <SecondaryButton @click="resetFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.947.34A5.002 5.002 0 005.999 5V3a1 1 0 01-1-1zm.008 9a1 1 0 011.992 0A5.002 5.002 0 0014 13v2a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 11 1.947-.34A5.002 5.002 0 0014.001 15z" clip-rule="evenodd" />
                        </svg>
                        Limpiar
                    </SecondaryButton>
                    <PrimaryButton @click="generateReport">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 11-2 0V5H5v12h6a1 1 0 110 2H4a1 1 0 01-1-1V4z" />
                            <path fill-rule="evenodd" d="M15.5 1a.5.5 0 01.5.5v13a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5V2a.5.5 0 01.5-.5h1z" clip-rule="evenodd" />
                        </svg>
                        Generar Reporte
                    </PrimaryButton>
                </div>
            </div>

            <!-- Tabla de Reportes -->
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Fecha</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tipo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Descripción</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Registros</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Generado por</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- Fila de ejemplo 1 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">02 Apr 2026</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    Usuarios
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Reporte de usuarios activos</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">1,245</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Admin</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-green-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila de ejemplo 2 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">01 Apr 2026</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                    Ingresos
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Reporte de ingresos mensuales</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">156</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Admin</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-green-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila de ejemplo 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">31 Mar 2026</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                    Conexiones
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Reporte de conexiones activas</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">892</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Admin</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-green-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Info adicional -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>💡 Tip:</strong> Selecciona el tipo de reporte, establece los filtros y haz clic en "Generar Reporte" para obtener los datos más recientes.
                </p>
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
