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
    //STARTS_WITH: 'startsWith',
    CONTAINS: 'contains',
    /*   NOT_CONTAINS: 'notContains',
      ENDS_WITH: 'endsWith',
      EQUALS: 'equals',
      NOT_EQUALS: 'notEquals',
      IN: 'in',
      LESS_THAN: 'lt',
      LESS_THAN_OR_EQUAL_TO: 'lte',
      GREATER_THAN: 'gt',
      GREATER_THAN_OR_EQUAL_TO: 'gte',
      BETWEEN: 'between',
      DATE_IS: 'dateIs',
      DATE_IS_NOT: 'dateIsNot',
      DATE_BEFORE: 'dateBefore',
      DATE_AFTER: 'dateAfter' */
};

//para mostrar los datos en cada columna del datatable
const columns = [{ data: "id" }, { data: "username" }, { data: "value" }];

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
    radchecks: { type: Array },
});

const form = useForm({
    username: '',
    password: ''
});

const eform = ref({
    id: '',
    username: '',
})
//const mn = defineProps(['success']);

const operation = ref(1);

// envio de datos al controlador insert update
const save = () => {
    if (operation.value == 1) {
        form.post(route('radcheck.store'), {
            onSuccess: () => {
                ok('Usuario guardado exitosamente'); // Mostrar mensaje de éxito
                closeModalForm(); // Cerrar el modal y resetear el formulario

            },
        });
    } else {
        form.put(route('radcheck.update', eform.value.id), {
            onSuccess: () => {
                ok('Usuario actualizado')
                closeModalForm();

            },
        });
    }
}
const ok = (m) => {
    form.reset();
    toast.add({ severity: 'success', summary: 'Éxito', detail: m, life: 3000 });
}

//controlador de modal de formulario
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');

const openModalForm = (op, r) => {
    showModalForm.value = true;
    operation.value = op;
    if (op == 1) {
        title.value = 'Nuevo usuario';
    } else {
        title.value = 'Actualizar usuario';
        form.username = r.username;
        form.password = r.value;
        eform.value.id = r.id;
    }
}

const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
}
//controlador de modal para eliminar
const openModalDel = (r) => {
    showModalDel.value = true;
    eform.value.id = r.id;
    eform.value.username = r.username;

}
const closeModalDel = () => {
    showModalDel.value = false;
}
const deleteUser = () => {
    form.delete(route('radcheck.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Usuario eliminado');

            closeModalDel();

        }
    });

}
</script>


<template>

    <Head title="User PPPoE" />
    <Toast />

    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de Usuarios PPPoE</h1>
                    <p class="text-sm text-gray-500">Manage users: <span class="font-medium text-gray-700">{{
                        Object.keys(radchecks).length }}</span> usuarios</p>
                </div>
                <div class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar Usuario
                    </PrimaryButton>
                </div>
            </div>


            <!-- CUERPO -->
               <div class="w-full overflow-hidden ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <DataTable :value="radchecks" v-model:filters="filters" ref="dt" selectionMode="single"
                        :globalFilterFields="['username', 'value']" paginator :rows="5"
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
                                        <InputText v-model="filters['global'].value" placeholder="Buscar usuario..."
                                            class="w-full pl-8 rounded-lg" />
                                    </IconField>
                                </div>

                                <!-- Botones de exportación -->
                                <div class="flex gap-2">
                                    <Button icon="pi pi-file-excel" label="CSV" @click="exportCSV" size="large" raised
                                        class="rounded-md bg-green-700 px-2 py-1 text-center text-md text-white hover:bg-green-600 active:bg-green-800" />
                                </div>
                            </div>
                        </template>

                        <!--      <Column header="#" style="width: 5%;">
                            <template #body="slotProps">
                                {{ slotProps.index + 1 }}
                            </template>
                            headerClass="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column> -->

                        <Column field="username" sortable header="nombre"
                            headerClass="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="value" sortable header="pass"
                            headerClass="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column header="acciones" #body="slotProps" bodyClass="border border-gray-300"
                            headerClass="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider">
                            <div class="flex gap-2">
                                <button @click="openModalForm(2, slotProps.data)"
                                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>

                                <button @click="openModalDel(slotProps.data)"
                                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </Column>

                    </DataTable>
                </div>
            </div>
        </div>





        <!-- MODAL PARA FORMULARIO DE REGISTRO -->
        <Modal :show="showModalForm" @close="closeModalForm" maxWidth="xl">
            <div class="p-5">
                <div class="flex justify-between items-center pb-4">
                    <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>
                    <button @click="closeModalForm" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="username" class="block mb-1.5 text-sm font-medium text-gray-900">Nombre de
                                usuario</label>
                            <input type="text" id="username" v-model="form.username"
                                class="block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-md shadow-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="usuario" required />
                        </div>
                        <div>
                            <label for="password"
                                class="block mb-1.5 text-sm font-medium text-gray-900">Contraseña</label>
                            <input type="text" id="password" v-model="form.password"
                                class="block w-full px-2.5 py-2 text-sm text-gray-900 border border-gray-300 rounded-md shadow-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="••••••" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <SecondaryButton class="w-full" @click="closeModalForm">Cancelar</SecondaryButton>
                        </div>
                        <div>
                            <PrimaryButton class="w-full">Guardar</PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- MODAL PARA ELIMINAR DATOS -->
        <Modal :show="showModalDel" @close="closeModalDel" maxWidth="md">
            <div class="p-5">
                <div class="flex justify-between items-center pb-6">
                    <h2 class="text-lg font-medium text-gray-900">Confirmar eliminación</h2>
                    <button @click="closeModalDel" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col gap-4 pb-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 pt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-20 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                Está por eliminar el siguiente usuario:
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900">
                                {{ eform.username }}
                            </p>
                            <p class="mt-3 text-sm text-red-600">
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <SecondaryButton class="w-full" @click="closeModalDel">Cancelar</SecondaryButton>
                    </div>
                    <div>
                        <PrimaryButton class="w-full bg-red-600 hover:bg-red-700" @click="deleteUser">Eliminar
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
