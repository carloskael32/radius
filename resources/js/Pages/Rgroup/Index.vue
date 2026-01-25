<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import DangerButton from '@/Components/DangerButton.vue';
import WarningButton from '@/Components/WarningButton.vue';
import DarkButton from '@/Components/DarkButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';


//recibimos los datos del controlador
const props = defineProps({
    rgroupchk: { type: Array },
    grppf: { type: Array },
    error: { type: String, default: '' }
});




//Sirven para manejar el estado de los 
// campos del formulario en Vue usando inertia
const form = useForm({
    groupname: '',
    value: '',
});

const eform = ref({
    id: '',
    groupname: '',
    value: '',
});

//ENVIO DE DATOS AL CONTROLADOR
const save = () => {
    if (operation.value == 1) {
        form.post(route('rgroup.store'), {
            onSuccess: () => {
                ok('Perfil registrado correctamente');
                closeModalForm();
            },
            onError: () => {
                error('Error: El grupo/perfil ya existe');
                closeModalForm();
            }
        })
    } else {
        form.put(route('rgroup.update', eform.value.id), {
            onSuccess: () => {
                ok('Perfil actualizado correctamente');
                closeModalForm();
            },
        });
    }
};
//Eliminacion de datos
const deletrgroup = () => {
    form.delete(route('rgroup.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Perfil eliminado correctamente');
            closeModalDel();
        },
    })
}

//mensaje de confirmacion
const ClassMsj = ref('hidden');
const msj = ref('');
const ok = (m) => {
    form.reset();
    msj.value = m;
    ClassMsj.value = 'block bg-green-500';
    setTimeout(() => {
        ClassMsj.value = 'hidden';
    }, 5000);
}
const error = (m) => {
    form.reset();
    msj.value = m;
    ClassMsj.value = 'block bg-yellow-300';
    setTimeout(() => {
        ClassMsj.value = 'hidden';
    }, 5000);

}

//CONTROLADOR DE MODAL DE FORMULARIO
const showModalForm = ref(false);
const showModalDel = ref(false);
const operation = ref(1);
const title = ref('');

const openModalForm = (op, r) => {
    showModalForm.value = true;
    operation.value = op;
    if (op == 1) {
        title.value = 'Adicionar nuevo grupo/perfil';
    } else {
        title.value = 'Editar grupo/perfil';
        form.groupname = r.groupname;
        form.value = r.value;
        eform.value.id = r.id;
    }
}


//operaciones con el modal de formulario
const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
}

const openModalDel = (r) => {
    showModalDel.value = true;
    eform.value.id = r.id;
    eform.value.groupname = r.groupname;
    eform.value.value = r.value;
}

const closeModalDel = () => {
    showModalDel.value = false;
}


</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            Perfiles de Navegacion
        </template>

        <div class="pb-2">
            <DarkButton @click="openModalForm(1)" class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Adicionar
            </DarkButton>
        </div>



        <!-- MENSAJE DE ADVERTENCIA DE REGISTRO DUPLICADO Y -->
        <!-- MENSAJE DE CONFIRMACION DE REGISTRO -->
        <div class="inline-flex overflow-hidden mb-4 w-full  rounded-lg shadow-md" :class="ClassMsj">
            <div class="flex justify-center items-center w-12 ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-gree-500">Success</span>
                    <p class="text-sm text-gray-600">{{ msj }}</p>
                </div>
            </div>
        </div>


        <!--  cuerpo -->

        
            <div class="relative overflow-x-auto shadow-lg sm:rounded-xs ">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class=" text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-2 py-3 text-center">#</th>
                            <th class="text-center">Nombre de grupo</th>
                            <th class="text-center">Velocidad UP/DOWN</th>
                            <th class="text-center">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="(r, i) in rgroupchk" :key="r.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ (i + 1) }}
                            </th>
                            <td class="text-center">
                                {{ r.groupname }}
                            </td>
                            <td class="text-center">
                                {{ r.value }}
                            </td>

                            <td class="py-2 text-center">

                                <div class="inline-flex rounded-md shadow-xs" role="group">
                                    <Link :href="route('ruserg.show', r.groupname)" class="px-2">
                                    <PrimaryButton class="flex items-center gap-2" aria-label="Ver detalles">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </PrimaryButton>
                                    </Link>
                                    <p class="px-2">
                                        <WarningButton @click="openModalForm(2, r)" class="px-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </WarningButton>
                                    </p>
                                    <p class="px-2">
                                        <DangerButton @click="openModalDel(r)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </DangerButton>
                                    </p>
                                </div>



                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>

    
        <!-- MODAL PARA FORMULARIO DE REGISTRO Y EDITAR -->

        <Modal :show="showModalForm" @close="closeModalForm">

            <div class="p-4">
                <h2 class="pb-4 text-lg font-medium text-gray-900">{{ title }}</h2>
                <form @submit.prevent="save">


                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="groupname" id="floating_first_name" v-model="form.groupname"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-black dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            nombre grupo/perfil</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="value" id="floating_first_name" v-model="form.value"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-black dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            valor de velocidad</label>
                    </div>




                    <div class="m-2 flex justify-end">
                        <PrimaryButton @submit.prevent="save">save</PrimaryButton>

                        <SecondaryButton @click="closeModalForm">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </Modal>


        <!-- MODAL PARA ELIMINAR DATOS -->
        <Modal :show="showModalDel" @close="closeModalDel">
            <div class="p-6">
                <p class="text-2xl text-gray-500">
                    Are you sure detele to
                    <span class="text-2xl font-medium text-gray-900">{{ eform.groupname }}</span>
                    ?
                </p>
                <PrimaryButton @click="deletrgroup">Yes, delete</PrimaryButton>
            </div>
            <div class="m-6 flex justify-end">
                <SecondaryButton @click="closeModalDel">Cancel</SecondaryButton>
            </div>
        </Modal>


    </AuthenticatedLayout>
</template>
