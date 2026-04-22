<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
    users: { type: Object },
    roles: { type: Array, default: () => [] },
    errors: { type: Object, default: () => ({}) }
});

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

// Añade una ref para manejar errores específicos para validaciones de entrada
const validationErrors = ref({});

const form = useForm({
    username: '',
    email: '',
    nombres: '',
    apellidos: '',
    telefono: '',
    direccion: '',
    password: '',
    id_rol: '',
    activo: true,
});

const eform = ref({
    id: '',
    username: '',
    email: '',
    nombres: '',
    apellidos: '',
    telefono: '',
    direccion: '',
    password: '',
    activo: true,
});

const operation = ref(1);

//controlador de modal de formulario
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');

const openModalForm = (op, u) => {
    showModalForm.value = true;
    operation.value = op;
    validationErrors.value = {}; // Limpiar errores al abrir modal
    if (op == 1) {
        title.value = 'Nuevo usuario';
    } else {
        title.value = 'Actualizar usuario';
        form.username = u.username;
        form.email = u.email;
        form.nombres = u.nombres;
        form.apellidos = u.apellidos;
        form.telefono = u.telefono;
        form.direccion = u.direccion;
        form.id_rol = u.id_rol;
        form.activo = u.activo;

        eform.value.id = u.id;
    }
}
// envio de datos al controlador insert update
const save = () => {
    //Limpiar errores previos
    validationErrors.value = {};

    if (operation.value == 1) {
        form.post(route('users.store'), {
            onSuccess: () => {
                ok('Usuario guardado exitosamente'); // Mostrar mensaje de éxito
                closeModalForm(); // Cerrar el modal y resetear el formulario
            },
            onError: (errors) => {
                // Captura errores de validacion
                validationErrors.value = errors;
            }
        });
    } else {
        form.put(route('users.update', eform.value.id), {
            onSuccess: () => {
                ok('Usuario actualizado')
                closeModalForm();
            },
            onError: (errors) => {
                // Capturar errores de validación
                validationErrors.value = errors;
            },
        });
    }
}

const ok = (m) => {
    form.reset();
    toast.add({ severity: 'success', summary: 'Éxito', detail: m, life: 3000 });
}



const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
}

//controlador de modal para eliminar
const openModalDel = (u) => {
    showModalDel.value = true;
    eform.value.id = u.id;
    eform.value.username = u.username;
    eform.value.nombres = u.nombres
    eform.value.apellidos = u.apellidos
}

const closeModalDel = () => {
    showModalDel.value = false;
    form.reset();
    validationErrors.value = {}; // Limpiar errores al cerrar
}

//Eliminacion de datos
const deleteUser = () => {
    form.delete(route('users.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Usuario eliminado');
            closeModalDel();
        }
    });
}

// para Validaciones de entrada
//Funcion para limpiar errores cuando se cambia un campo
const clearError = (field) => {
    if (validationErrors.value[field]) {
        delete validationErrors.value[field];
    }
}

// Función para obtener mensaje de error de un campo específico
const getErrorMessage = (field) => {
    return validationErrors.value[field] ? validationErrors.value[field] : '';
}

// Función para verificar si un campo tiene error
const hasError = (field) => {
    return validationErrors.value[field] ? true : false;
}

// estados por fila para switches
const rowStates = ref({});

const initRowStates = () => {
    rowStates.value = {};
    if (props.users.data && Array.isArray(props.users.data)) {
        props.users.data.forEach(u => {
            rowStates.value[u.id] = u.activo;
        });
    }
};

//para iniciar nuevamente la funcion intRowStates cuando se actualice el props usuarios
watch(() => props.users.data, () => {
    initRowStates();
}, { immediate: true, deep: true });

const toggleEstado = (user, checked) => {
    // Optimista: actualizar UI primero
    rowStates.value[user.id] = checked;
    const newEstado = checked ? 1 : 0;

    axios.patch(route('users.toggle', user.id), { activo: newEstado })
        .then(() => {
            user.activo = newEstado;
            toast.add({ severity: 'success', summary: 'Éxito', detail: 'Estado actualizado', life: 3000 });
        })
        .catch((error) => {
            // Revertir cambio en caso de error
            rowStates.value[user.id] = !checked;
            console.error('Error al actualizar estado:', error);
            toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo actualizar estado', life: 3000 });
        });
};
</script>

<template>

    <Head title="Users" />
    <Toast />

    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de Usuarios</h1>
                    <p class="text-sm text-gray-500">Total: <span class="font-medium text-gray-700">{{ users.data.length
                    }}</span> usuarios</p>
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
                    <DataTable :value="users.data" v-model:filters="filters" ref="dt" selectionMode="single"
                        :globalFilterFields="['username', 'email', 'nombres', 'apellidos', 'rol.nombre_rol']" paginator
                        :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
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

                        <Column field="username" sortable header="username"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="email" sortable header="email"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="nombres" sortable header="nombres"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="apellidos" sortable header="apellidos"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                        </Column>
                        <Column field="rol.nombre_rol" sortable header="rol"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300 text-center">
                            <template #body="{ data }">
                                <span v-if="data.rol"
                                    class="bg-blue-400 text-blue-900 inline-block px-3 rounded-sm font-semibold">
                                    {{ data.rol.nombre_rol }}
                                </span>
                                <span v-else
                                    class="bg-gray-300 text-gray-900 inline-block px-3 rounded-xl font-semibold">
                                    Sin rol
                                </span>
                            </template>
                        </Column>
                        <Column field="activo" sortable header="estado"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300 text-center">
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
                                        <span class="ml-3 text-gray-700">{{ rowStates[data.id] ? 'Activo' : 'Inactivo'
                                        }}</span>
                                    </label>
                                </div>
                            </template>
                        </Column>

                        <Column header="acciones" #body="slotProps"
                            headerClass="border border-gray-300 bg-gray-100 text-xs font-medium text-black uppercase tracking-wider"
                            bodyClass="border border-gray-300">
                            <div class="flex gap-2">
                                <!-- BOTON PARA EDITAR REGISTRO -->
                                <button @click="openModalForm(2, slotProps.data)"
                                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </button>
                                <!-- BOTON PARA ELIMINAR REGISTRO -->
                                <button @click="openModalDel(slotProps.data)"
                                    class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-200">
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

        <!-- Modal para Crear/Editar Usuario -->
        <Modal :show="showModalForm" @close="closeModalForm" maxWidth="xl">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                <form @submit.prevent="save" class="mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label for="username" class="block mb-1.5 text-sm font-medium text-heading">Nombre de
                                usuario</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path
                                            d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>

                                </div>
                            </div>
                            <input type="text" id="username" v-model="form.username" autocomplete="username"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="user1" />



                            <p v-if="hasError('username')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('username') }}
                            </p>
                        </div>


                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Correo
                                electronico</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M5.404 14.596A6.5 6.5 0 1 1 16.5 10a1.25 1.25 0 0 1-2.5 0 4 4 0 1 0-.571 2.06A2.75 2.75 0 0 0 18 10a8 8 0 1 0-2.343 5.657.75.75 0 0 0-1.06-1.06 6.5 6.5 0 0 1-9.193 0ZM10 7.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5Z"
                                            clip-rule="evenodd" />
                                    </svg>


                                </div>
                            </div>
                            <input type="text" v-model="form.email" autocomplete="email"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="user1@gmail.com" />
                            <p v-if="hasError('email')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('email') }}
                            </p>
                        </div>

                        <div>
                            <label for="Nombres" class="block mb-1.5 text-sm font-medium text-heading">Nombres</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M3 3.5A1.5 1.5 0 0 1 4.5 2h6.879a1.5 1.5 0 0 1 1.06.44l4.122 4.12A1.5 1.5 0 0 1 17 7.622V16.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 16.5v-13Zm10.857 5.691a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 0 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <input type="text" id="nombres" v-model="form.nombres" autocomplete="given-name"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="pablito" />

                            <p v-if="hasError('nombres')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('nombres') }}
                            </p>
                        </div>

                        <div>
                            <label for="apellidos"
                                class="block mb-1.5 text-sm font-medium text-heading">Apellidos</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M3 3.5A1.5 1.5 0 0 1 4.5 2h6.879a1.5 1.5 0 0 1 1.06.44l4.122 4.12A1.5 1.5 0 0 1 17 7.622V16.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 16.5v-13Zm10.857 5.691a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 0 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <input type="text" id="apellidos" v-model="form.apellidos" autocomplete="family-name"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="perez gomez" />

                            <p v-if="hasError('apellidos')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('apellidos') }}
                            </p>
                        </div>


                        <div>
                            <label for="telefono" class="block mb-1.5 text-sm font-medium text-heading">Nro. telefono
                            </label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path
                                            d="M8 16.25a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Z" />
                                        <path fill-rule="evenodd"
                                            d="M4 4a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V4Zm4-1.5v.75c0 .414.336.75.75.75h2.5a.75.75 0 0 0 .75-.75V2.5h1A1.5 1.5 0 0 1 14.5 4v12a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 16V4A1.5 1.5 0 0 1 7 2.5h1Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </div>
                            </div>
                            <input type="number" v-model="form.telefono" autocomplete="tel"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="77712332" />

                            <p v-if="hasError('telefono')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('telefono') }}
                            </p>

                        </div>

                        <div>
                            <label for="visitors"
                                class="block mb-1.5 text-sm font-medium text-heading">Direccion</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.544l.062.029.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                </div>
                            </div>
                            <input type="text" v-model="form.direccion" autocomplete="street-address"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="Z. perdidos " />

                            <p v-if="hasError('direccion')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('direccion') }}
                            </p>
                        </div>

                        <div>
                            <label for="id_rol" class="block text-sm font-medium text-gray-700">Rol</label>
                            <select v-model="form.id_rol" @change="clearError('id_rol')"
                                :class="{ 'border-red-500': hasError('id_rol') }" id="id_rol"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Seleccionar rol</option>
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.nombre_rol }}
                                </option>
                            </select>
                            <span v-if="hasError('id_rol')" class="text-red-500 text-sm">{{ getErrorMessage('id_rol') }}</span>
                        </div>

                        <div>
                            <label for="password"
                                class="block mb-1.5 text-sm font-medium text-heading">Contraseña</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="password" v-model="form.password" autocomplete="new-password"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="C0ntras3ñ4" />
                            <p v-if="hasError('password')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('password') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <SecondaryButton @click="closeModalForm" class="mr-3">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Modal para Eliminar Usuario -->
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
                               {{ eform.username }} - {{ eform.nombres }} {{ eform.apellidos }}
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
