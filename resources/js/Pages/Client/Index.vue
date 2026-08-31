<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import axios from 'axios';

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

const props = defineProps({
    clients: { type: Array },
    grupos: { type: Array },
});


//para las columnas del datatable de forma manual
/* const columns = [{ data: "id" }, { data: "username" }, { data: "nombre_completo" }, { data: "email" }, { data: "telefono" }, { data: "direccion" }, { data: "estado" }, { data: "observaciones" }, { data: "plan" }];
 */
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


// Usaremos `form.errors` de Inertia para manejar errores

const form = useForm({
    username: '',
    nombre_completo: '',
    email: '',
    telefono: '',
    direccion: '',
    estado: 'activo',
    observaciones: '',
    plan: '',
    password_radius: '',

});

const eform = ref({
    id: '',
    nombre_completo: '',

})
//const mn = defineProps(['success']);

const operation = ref(1);

// envio de datos al controlador insert update
const save = () => {
    //Limpiar errores previos

    form.clearErrors();

    if (operation.value == 1) {
        form.post(route('client.store'), {
            onSuccess: () => {
                ok('Cliente guardado exitosamente'); // Mostrar mensaje de éxito
                closeModalForm(); // Cerrar el modal y resetear el formulario
            },
            onError: () => {
                // Los errores se exponen en `form.errors` automáticamente
            }
        });
    } else {
        form.put(route('client.update', eform.value.id), {
            onSuccess: () => {
                ok('Cliente actualizado')
                closeModalForm();
            },
            onError: () => {
                // Capturar errores de validación
                // Los errores se exponen en `form.errors` automáticamente
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

const openModalForm = (op, c) => {
    showModalForm.value = true;
    operation.value = op;
    form.clearErrors(); // Limpiar errores al abrir modal
    if (op == 1) {
        title.value = 'Nuevo cliente';
    } else {
        // Enviar datos al controlador
        axios.post(route('client.showRcheck', c.id))
            .then(response => {
                form.password_radius = response.data.pass;
            })
            .catch(error => {
                console.error('Error al buscar cliente:', error);
            });
        title.value = 'Actualizar cliente';
        form.username = c.username;
        //form.password_radius = c.password_radius;
        form.nombre_completo = c.nombre_completo;
        form.email = c.email;
        form.direccion = c.direccion;
        form.telefono = c.telefono;
        form.observaciones = c.observaciones;
        form.estado = c.estado;
        form.plan = c.plan;

        eform.value.id = c.id;
    }
}

const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
}
//controlador de modal para eliminar
const openModalDel = (c) => {
    showModalDel.value = true;
    eform.value.id = c.id;
    eform.value.nombre_completo = c.nombre_completo;

}
const closeModalDel = () => {
    showModalDel.value = false;
    form.reset();
    form.clearErrors(); // Limpiar errores al cerrar
}



//Eliminacion de datos
const deleteUser = () => {
    form.delete(route('client.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Usuario eliminado');
            closeModalDel();
        }
    });
}

// para Validaciones de entrada
//Funcion para limpiar errores cuando se cambia un campo
const clearError = (field) => {
    form.clearErrors(field);
}

// Función para obtener mensaje de error de un campo específico
const getErrorMessage = (field) => {
    return form.errors[field] ? form.errors[field] : '';
}

// Función para verificar si un campo tiene error
const hasError = (field) => {
    return form.errors[field] ? true : false;
}

// estados por fila para switches
const rowStates = ref({});

const initRowStates = () => {
    rowStates.value = {};
    if (props.clients && Array.isArray(props.clients)) {
        props.clients.forEach(c => {
            rowStates.value[c.id] = c.estado === 'activo';
        });
    }
};

//para iniciar nuevamente la funcion intRowStates cuando se actualice el props clientes
watch(() => props.clients, () => {
    initRowStates();
}, { immediate: true, deep: true });


const toggleEstado = (client, checked) => {
    // Optimista: actualizar UI primero
    rowStates.value[client.id] = checked;
    const newEstado = checked ? 'activo' : 'inactivo';

    axios.post(route('client.toggle', client.id), { estado: newEstado })
        .then(() => {
            client.estado = newEstado;
            toast.add({ severity: 'success', summary: 'Éxito', detail: 'Estado actualizado', life: 3000 });
        })
        .catch((error) => {
            // Revertir cambio en caso de error
            rowStates.value[client.id] = !checked;
            console.error('Error al actualizar estado:', error);
            toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo actualizar estado', life: 3000 });
        });
};

//seccion de permisos
const page = usePage();

const canView = computed(() =>
    page.props.auth.user.permissions.includes('ver clientes')
);
const canAdd = computed(() =>
    page.props.auth.user.permissions.includes('crear clientes')
);
const canDelete = computed(() =>
    page.props.auth.user.permissions.includes('eliminar clientes')
);
const canEdit = computed(() =>
    page.props.auth.user.permissions.includes('modificar clientes')
);





</script>


<template>

    <Head title="User PPPoE" />

    <AuthenticatedLayout>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/80 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none">
            <div class="flex items-start justify-between gap-4 pb-5">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-slate-900 dark:text-slate-100">Gestión de Clientes PPPoE</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Manage users: <span class="font-semibold text-slate-700 dark:text-slate-200">{{
                        Object.keys(clients).length }}</span> usuarios</p>
                </div>
                <div v-if="canAdd" class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar Cliente
                    </PrimaryButton>
                </div>
            </div>






            <!-- CUERPO -->
            <div class="w-full overflow-hidden">
                <div class="relative overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950">
                    <DataTable :value="clients" v-model:filters="filters" ref="dt" selectionMode="single"
                        :globalFilterFields="['username', 'nombre_completo', 'email', 'telefono', 'direccion', 'estado', 'plan']"
                        paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
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
                                        <InputText v-model="filters['global'].value" placeholder="Buscar cliente..."
                                            class="w-full rounded-xl border-slate-300 bg-white pl-9 text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" />
                                    </IconField>
                                </div>

                                <button type="button" @click="exportCSV"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 dark:bg-emerald-600 dark:hover:bg-emerald-500">
                                    📊 Excel
                                </button>
                            </div>
                        </template>

                        <Column field="username" sortable header="username"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="nombre_completo" sortable header="Nombre completo"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="email" sortable header="correo"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="telefono" sortable header="telefono"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="direccion" sortable header="direccion"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column v-if="canEdit" field="estado" sortable header="estado"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                            <template #body="{ data }">
                                <div class="flex items-center justify-center gap-3">
                                    <label class="flex items-center cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" class="sr-only" :checked="rowStates[data.id]"
                                                @change="(e) => toggleEstado(data, e.target.checked)" />
                                            <div class="block w-12 h-6 rounded-full"
                                                :class="rowStates[data.id] ? 'bg-green-500' : 'bg-red-500'">
                                            </div>
                                            <div class="absolute left-1 top-1 w-4 h-4 rounded-full transition"
                                                :class="rowStates[data.id] ? 'translate-x-6 bg-white' : 'bg-white'">
                                            </div>
                                        </div>
                                        <span class="ml-3 text-black dark:text-white">{{ rowStates[data.id] ? 'Activo' : 'Inactivo'
                                        }}</span>
                                    </label>
                                </div>
                            </template>
                        </Column>
                        <Column field="plan" sortable header="plan"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                            <!-- <template #body="{ data }">
                                <span v-if="data.plan"
                                    class="bg-green-400 text-green-900 inline-block px-3 rounded-sm font-semibold">
                                    {{ data.plan }}
                                </span>
                                <span v-else
                                    class="bg-yellow-300 text-red-900 inline-block px-3 rounded-xl font-semibold">
                                    ninguno
                                </span>
                            
                            </template>
                            -->
                            <template #body="{ data }">
                                <span class="bg-blue-200 text-blue-900 inline-block px-1 rounded-md font-semibold text-center">
                                    {{ data.plan }}
                                </span>
                            </template>

                        </Column>
                        <Column field="observaciones" sortable header="observaciones"
                           headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">
                        </Column>

                        <Column v-if="canEdit || canDelete" header="acciones" #body="slotProps"
                         headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider dark:border-white dark:bg-slate-800 dark:text-white"
                            bodyClass="border border-gray-300">

                            <div class="flex gap-2 items-center justify-center">
                                <!-- BOTON PARA VER MAS DETALLES DEL CLIENTE-->
                                <!-- <Link v-if="canView" :href="route('client.show', slotProps.data)">
                                    <button
                                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-purple-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="size-5 text-purple-600">
                                            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                                            <path fill-rule="evenodd"
                                                d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </Link> -->
                                <!-- BOTON PARA EDITAR REGISTRO -->
                                <button v-if="canEdit" @click="openModalForm(2, slotProps.data)"
                                    class="inline-flex p-2 rounded-md hover:bg-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <!-- BOTON PARA ELIMINAR REGISTRO -->
                                <button v-if="canDelete" @click="openModalDel(slotProps.data)"
                                    class="inline-flex p-2 rounded-md hover:bg-red-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-red-600">
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



        <!-- MODAL PARA FORMULARIO DE REGISTRO Y MODIFICACION -->
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
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre de usuario</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                </div>
                                <input type="text" id="username" v-model="form.username" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="user1" />
                            </div>
                            <p v-if="hasError('username')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('username') }}</p>
                        </div>
                        <div>
                            <label for="plan" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Plan de servicio</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path d="M16.555 5.412a8.028 8.028 0 0 0-3.503-2.81 14.899 14.899 0 0 1 1.663 4.472 8.547 8.547 0 0 0 1.84-1.662ZM13.326 7.825a13.43 13.43 0 0 0-2.413-5.773 8.087 8.087 0 0 0-1.826 0 13.43 13.43 0 0 0-2.413 5.773A8.473 8.473 0 0 0 10 8.5c1.18 0 2.304-.24 3.326-.675ZM6.514 9.376A9.98 9.98 0 0 0 10 10c1.226 0 2.4-.22 3.486-.624a13.54 13.54 0 0 1-.351 3.759A13.54 13.54 0 0 1 10 13.5c-1.079 0-2.128-.127-3.134-.366a13.538 13.538 0 0 1-.352-3.758ZM5.285 7.074a14.9 14.9 0 0 1 1.663-4.471 8.028 8.028 0 0 0-3.503 2.81c.529.638 1.149 1.199 1.84 1.66ZM17.334 6.798a7.973 7.973 0 0 1 .614 4.115 13.47 13.47 0 0 1-3.178 1.72 15.093 15.093 0 0 0 .174-3.939 10.043 10.043 0 0 0 2.39-1.896ZM2.666 6.798a10.042 10.042 0 0 0 2.39 1.896 15.196 15.196 0 0 0 .174 3.94 13.472 13.472 0 0 1-3.178-1.72 7.973 7.973 0 0 1 .615-4.115ZM10 15c.898 0 1.778-.079 2.633-.23a13.473 13.473 0 0 1-1.72 3.178 8.099 8.099 0 0 1-1.826 0 13.47 13.47 0 0 1-1.72-3.178c.855.151 1.735.23 2.633.23ZM14.357 14.357a14.912 14.912 0 0 1-1.305 3.04 8.027 8.027 0 0 0 4.345-4.345c-.953.542-1.971.981-3.04 1.305ZM6.948 17.397a8.027 8.027 0 0 1-4.345-4.345c.953.542 1.971.981 3.04 1.305a14.912 14.912 0 0 0 1.305 3.04Z" />
                                    </svg>
                                </div>
                                <select id="plan" v-model="form.plan" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                                    <option value="">Selecciona</option>
                                    <option v-for="g in grupos" :key="g.id" :value="g.groupname">{{ g.groupname }}</option>
                                </select>
                            </div>
                            <p v-if="hasError('plan')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('plan') }}</p>
                        </div>
                        <div>
                            <label for="password_radius" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Clave de acceso</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                                <input type="text" id="password_radius" v-model="form.password_radius" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="246810" />
                            </div>
                            <p v-if="hasError('password_radius')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('password_radius') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="nombre_completo" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre completo</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path fill-rule="evenodd" d="M3 3.5A1.5 1.5 0 0 1 4.5 2h6.879a1.5 1.5 0 0 1 1.06.44l4.122 4.12A1.5 1.5 0 0 1 17 7.622V16.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 16.5v-13Zm10.857 5.691a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 0 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="nombre_completo" v-model="form.nombre_completo" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="juan juanito perez" />
                            </div>
                            <p v-if="hasError('nombre_completo')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('nombre_completo') }}</p>
                        </div>
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Correo electronico</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path fill-rule="evenodd" d="M5.404 14.596A6.5 6.5 0 1 1 16.5 10a1.25 1.25 0 0 1-2.5 0 4 4 0 1 0-.571 2.06A2.75 2.75 0 0 0 18 10a8 8 0 1 0-2.343 5.657.75.75 0 0 0-1.06-1.06 6.5 6.5 0 0 1-9.193 0ZM10 7.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="email" v-model="form.email" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="user1@gmail.com" />
                            </div>
                            <p v-if="hasError('email')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('email') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="direccion" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Direccion</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.544l.062.029.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="direccion" v-model="form.direccion" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="Z. perdidos" />
                            </div>
                            <p v-if="hasError('direccion')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('direccion') }}</p>
                        </div>
                        <div>
                            <label for="telefono" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Nro. telefono</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-slate-400 dark:text-slate-500">
                                        <path d="M8 16.25a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Z" />
                                        <path fill-rule="evenodd" d="M4 4a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V4Zm4-1.5v.75c0 .414.336.75.75.75h2.5a.75.75 0 0 0 .75-.75V2.5h1A1.5 1.5 0 0 1 14.5 4v12a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 16V4A1.5 1.5 0 0 1 7 2.5h1Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="tel" id="telefono" v-model="form.telefono" class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 ps-10 pr-3 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="77712312" />
                            </div>
                            <p v-if="hasError('telefono')" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ getErrorMessage('telefono') }}</p>
                        </div>
                    </div>
                    <div>
                        <label for="observaciones" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-200">Observaciones</label>
                        <textarea id="observaciones" v-model="form.observaciones" rows="3" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="alguna observacion ?"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <SecondaryButton class="w-full" @click="closeModalForm">Cancelar</SecondaryButton>
                        <PrimaryButton class="w-full" type="submit">Guardar</PrimaryButton>
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
                        <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ eform.nombre_completo }}</p>
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
