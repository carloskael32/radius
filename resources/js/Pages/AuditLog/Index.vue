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

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none">
            <div class="flex items-start justify-between gap-4 pb-5">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de LOGs</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Numero de LOG's: <span class="font-semibold text-slate-700 dark:text-slate-200">{{
                        Object.keys(logs).length }}</span></p>
                </div>

            </div>

            <div class="w-full overflow-hidden">
                <div class="relative overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950">

                    <DataTable :value="logs" v-model:filters="filters" ref="dt" selectionMode="single" size="small"
                        :globalFilterFields="['log_name', 'description']" paginator :rows="10"
                        :rowsPerPageOptions="[5, 10, 20, 50]"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} a {last} de {totalRecords}"
                        class="dark:bg-slate-950">

                        <template #header>
                            <div class="flex flex-col gap-4 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/80 md:flex-row md:items-center md:justify-between">
                                <div class="w-full md:w-auto">
                                    <IconField iconPosition="left" class="w-full md:w-72">
                                        <InputIcon>
                                            <i class="pi pi-search text-slate-400 dark:text-slate-500" />
                                        </InputIcon>
                                        <InputText v-model="filters['global'].value" placeholder="Buscar log..."
                                            class="w-full rounded-xl border-slate-300 bg-white pl-9 text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" />
                                    </IconField>
                                </div>

                                <div class="flex gap-2">
                                    <Button label="📊 Excel" @click="exportCSV" size="large" raised
                                        class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500" />
                                </div>
                            </div>
                        </template>


                        <Column field="log_name" sortable header="menu"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>
                        <Column field="description" sortable header="evento"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>
                        <Column field="autor_nombre" header="autor"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">

                        </Column>
                        <Column field="properties" sortable header="acciones realizadas"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>

                      
                        <Column field="created_at" sortable header="fecha"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>


                    </DataTable>
                </div>
            </div>





        </div>





    </AuthenticatedLayout>

</template>