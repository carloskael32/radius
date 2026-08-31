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
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none">
            <div class="flex items-start justify-between gap-4 pb-5">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de Usuarios PPPoE</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Manage users: <span class="font-semibold text-slate-700 dark:text-slate-200">{{
                        Object.keys(radchecks).length }}</span> usuarios</p>
                </div>
                <div class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
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
            <div class="w-full overflow-hidden">
                <div class="relative overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950">

                    <DataTable :value="radchecks" v-model:filters="filters" ref="dt"
                        :globalFilterFields="['username', 'value']" paginator :rows="5"
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
                                        <InputText v-model="filters['global'].value" placeholder="Buscar usuario..."
                                            class="w-full rounded-xl border-slate-300 bg-white pl-9 text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" />
                                    </IconField>
                                </div>

                                <div class="flex gap-2">
                                    <Button icon="pi pi-file-excel" label="CSV" @click="exportCSV" size="large" raised
                                        class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500" />
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
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>
                        <Column field="value" sortable header="pass"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                            bodyClass="border border-slate-200 bg-white text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        </Column>
                        <Column header="acciones" #body="slotProps" bodyClass="border border-slate-200 bg-white text-center dark:border-slate-700 dark:bg-slate-900"
                            headerClass="border border-slate-200 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
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
            <div class="bg-slate-50 p-6 dark:bg-slate-950">
                <div class="mb-6 flex items-center justify-between gap-3 border-b border-slate-200 pb-4 dark:border-slate-700">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ title }}</h2>
                    <button @click="closeModalForm" class="rounded-full p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="save" class="space-y-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre de usuario</label>
                            <input type="text" id="username" v-model="form.username" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="usuario" required />
                        </div>
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Contraseña</label>
                            <input type="text" id="password" v-model="form.password" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="••••••" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <SecondaryButton class="w-full" @click="closeModalForm">Cancelar</SecondaryButton>
                        <PrimaryButton class="w-full">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- MODAL PARA ELIMINAR DATOS -->
        <Modal :show="showModalDel" @close="closeModalDel" maxWidth="md">
            <div class="bg-slate-50 p-6 dark:bg-slate-950">
                <div class="mb-5 flex items-center justify-between gap-3 border-b border-slate-200 pb-4 dark:border-slate-700">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Confirmar eliminación</h2>
                    <button @click="closeModalDel" class="rounded-full p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex gap-4 pb-6">
                    <div class="flex-shrink-0 rounded-2xl bg-red-100 p-3 dark:bg-red-900/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 text-red-600 dark:text-red-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-slate-600 dark:text-slate-300">Está por eliminar el siguiente usuario:</p>
                        <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ eform.username }}</p>
                        <p class="text-sm text-red-600 dark:text-red-400">Esta acción no se puede deshacer.</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <SecondaryButton class="w-full" @click="closeModalDel">Cancelar</SecondaryButton>
                    <PrimaryButton class="w-full bg-red-600 hover:bg-red-700" @click="deleteUser">Eliminar</PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
