<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({
    clients: { type: Object },
    errors: { type: Object, default: () => ({}) }
});

// Añade una ref para manejar errores específicos para validaciones de entrada
const validationErrors = ref({});

const form = useForm({
    username: '',
    nombre_completo: '',
    email: '',
    telefono: '',
    direccion: '',
    estado: '',
    observaciones: '',
    password_radius: '',

});

const eform = ref({

    username: '',
    nombre_completo: '',
    email: '',
    telefono: '',
    direccion: '',
    estado: '',
    observaciones: '',
    //password_radius: '',

})
//const mn = defineProps(['success']);

const ClassMsj = ref('hidden');
const msj = ref('');
const operation = ref(1);

// envio de datos al controlador insert update
const save = () => {
    //Limpiar errores previos
    validationErrors.value = {};

    if (operation.value == 1) {
        form.post(route('client.store'), {
            onSuccess: () => {
                ok('Cliente guardado exitosamente'); // Mostrar mensaje de éxito
                closeModalForm(); // Cerrar el modal y resetear el formulario
            },
            onError: (errors) => {
                // Captura errores de validacion
                validationErrors.value = errors;
            }
        });
    } else {
        form.put(route('client.update', eform.value.id), {
            onSuccess: () => {
                ok('Cliente actualizado')
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
    msj.value = m;
    ClassMsj.value = 'block';
    setTimeout(() => {
        ClassMsj.value = 'hidden';
    }, 5000);
}

//controlador de modal de formulario
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');

const openModalForm = (op, c) => {
    showModalForm.value = true;
    operation.value = op;
    validationErrors.value = {}; // Limpiar errores al abrir modal
    if (op == 1) {
        title.value = 'Nuevo cliente';
    } else {
        title.value = 'Actualizar cliente';

        form.username = c.username;
        form.password_radius = c.password_radius;
        form.nombre_completo = c.nombre_completo;
        form.email = c.email;
        form.direccion = c.direccion;
        form.telefono = c.telefono;
        form.observaciones = c.observaciones;
        form.estado = c.estado;

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
    validationErrors.value = {}; // Limpiar errores al cerrar
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

</script>


<template>

    <Head title="User PPPoE" />

    <AuthenticatedLayout>
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de Clientes PPPoE</h1>
                    <p class="text-sm text-gray-500">Manage users: <span class="font-medium text-gray-700">{{
                        Object.keys(clients).length }}</span> usuarios</p>
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


            <!-- MENSAJE DE CONFIRMACION DE REGISTRO -->
            <div :class="ClassMsj" class="mb-4">
                <div class="rounded-lg bg-green-50 border border-green-100 p-3 flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-green-800">Success</p>
                        <p class="text-sm text-green-700">{{ msj }}</p>
                    </div>
                </div>
            </div>


            <!-- CUERPO -->
            <div class="w-full overflow-hidden ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600">
                        <thead class="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider text-center">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Nombre de usuario</th>
                                <th class="px-4 py-3">nombre completo</th>
                                <th class="px-4 py-3">correo</th>
                                <th class="px-4 py-3">telefono</th>
                                <th class="px-4 py-3">direccion</th>
                                <th class="px-4 py-3">estado</th>
                                <th class="px-4 py-3">observaciones</th>

                                <th colspan="2" class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr v-for="c, i in clients" :key="c.id" class="text-gray-700 hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ (i + 1) }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.username }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.nombre_completo }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.email }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.telefono }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.direccion }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.estado }}
                                </td>
                                <td class="px-4 py-3 text-sm border-b border-gray-500">
                                    {{ c.observaciones }}
                                </td>

                                <td class="px-2 py-3 text-sm text-center">
                                    <button @click="openModalForm(2, c)"
                                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-2 py-3 text-sm text-center">
                                    <button @click="openModalDel(c)"
                                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

                <!-- Mostrar errores generales si existen -->
                <div v-if="Object.keys(validationErrors).length > 0"
                    class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 font-semibold mb-2">Por favor, corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside text-sm text-red-600">
                        <li v-for="(error, field) in validationErrors" :key="field">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <form @submit.prevent="save">


                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nombre de
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
                            <input type="text" id="username" v-model="form.username"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="user1" required />
                                 <p v-if="hasError('username')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('username') }}
                            </p>
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Clave de
                                acceso</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="text" v-model="form.password_radius"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="•••••" required />
                            <p v-if="hasError('password_radius')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('password_radius') }}
                            </p>
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nombre
                                completo</label>
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
                            <input type="text" v-model="form.nombre_completo"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="juan juanito perez " required />
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
                            <input type="text" v-model="form.email"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="user1@gmail.com" required />
                            <p v-if="hasError('email')" class="mt-1 text-sm text-red-600">
                                {{ getErrorMessage('email') }}
                            </p>
                        </div>

                    </div>


                    <div class="grid grid-cols-2 gap-4 pb-4">
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
                            <input type="text" v-model="form.direccion"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="Z. perdidos " required />
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nro. telefono
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
                            <input type="tel" v-model="form.telefono"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="77712312" required />
                        </div>
                    </div>
                    <div class="pb-4">
                        <label for="visitors"
                            class="block mb-1.5 text-sm font-medium text-heading">Observaciones</label>
                        <textarea v-model="form.observaciones" rows="3"
                            class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 shadow-xs placeholder:text-body rounded-md"
                            placeholder="alguna observacion ?" required></textarea>
                    </div>





                    <h2 class="pb-1">Estado</h2>
                    <div class="grid grid-cols-6 pb-8 ">

                        <div>
                            <input v-model="form.estado" id="default-radio-1" type="radio" value="activo" name="activo"
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="default-radio-1"
                                class="select-none ms-2 text-sm font-medium text-heading">Activo</label>
                        </div>
                        <div>
                            <input v-model="form.estado" id="default-radio-2" type="radio" value="inactivo"
                                name="inactivo"
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="default-radio-2"
                                class="select-none ms-2 text-sm font-medium text-heading">Inactivo</label>
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <SecondaryButton class="w-full" @click="closeModalForm">Cancelar</SecondaryButton>

                        </div>
                        <div>
                            <PrimaryButton class="w-full" type="submit">Guardar</PrimaryButton>
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
                                {{ eform.nombre_completo }}
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