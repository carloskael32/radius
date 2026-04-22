<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';

//datatable primevue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
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
const toast = useToast();

const exportCSV = () => {
    if (dt.value) {
        dt.value.exportCSV({
            selectionOnly: false, // Exportar todos los datos
            fileName: 'usuarios.csv'
        });
    }
};



const props = defineProps({
    logs: { type: Array }
})

</script>

<template>

    <Head title="LOGS" />

    <AuthenticatedLayout>

        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de LOGs</h1>
                    <p class="text-sm text-gray-500">Numero de LOG's: <span class="font-medium text-gray-700">{{
                        Object.keys(logs).length }}</span></p>
                </div>

            </div>

            <!-- CUERPO -->
            <div class="w-full overflow-hidden ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <DataTable :value="logs" v-model:filters="filters" ref="dt" selectionMode="single" size="small"
                        :globalFilterFields="['log_name', 'description', 'properties']" paginator :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
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
                                        <InputText v-model="filters['global'].value" placeholder="Buscar log..."
                                            class="w-full pl-8 rounded-lg" />
                                    </IconField>
                                </div>

                                <!-- Botones de exportación -->
                                <div class="flex gap-2">
                                    <Button label="📊 Excel" @click="exportCSV" size="large" raised
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm" />
                                </div>
                            </div>
                        </template>


                        <Column field="log_name" sortable header="menu"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="description" sortable header="evento"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="autor_nombre" header="autor"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">

                        </Column>
                        <Column field="properties" sortable header="acciones realizadas"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>

                      
                        <Column field="created_at" sortable header="fecha"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>


                    </DataTable>
                </div>
            </div>





        </div>





    </AuthenticatedLayout>

</template>