<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import WarningButton from '@/Components/WarningButton.vue';
import DarkButton from '@/Components/DarkButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({
    radchecks: { type: Object },
    //success: { type: String },
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

const ClassMsj = ref('hidden');
const msj = ref('');
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
    }else{
        form.put(route('radcheck.update', eform.value.id),{
            onSuccess: () =>{
                ok('Usuario actualizado')
                closeModalForm();
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

const openModalForm = (op, r) =>{
    showModalForm.value = true;
    operation.value = op;
    if(op == 1){
        title.value = 'Nuevo usuario';
    }else{
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

    <Head title="About us" />

    <AuthenticatedLayout>
        <template #header>
            User PPPoE
        </template>

        <div class="pb-2">
            <DarkButton @click="openModalForm(1)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </DarkButton>
        </div>


        <!-- MENSAJE DE CONFIRMACION DE REGISTRO -->
        <div class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md" :class="ClassMsj">
            <div class="flex justify-center items-center w-12 bg-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-gree-500">Success</span>
                    <p class="text-sm text-black">{{ msj }}</p>
                </div>
            </div>
        </div>


        <!--  cuerpo -->

        <div class="w-full overflow-hidden rounded-lg border shadow-md">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Nombre de usuario</th>
                            <th class="px-4 py-3">Contraseña</th>                           
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Edit</th>
                            <th class="px-4 py-3">Delete</th>
                        </tr>
                    </thead>
                    <tbody
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200 text-black dark:text-gray-300">
                        <tr v-for="r, i in radchecks" :key="r.id">
                            <td class="px-4 py-3 text-sm">
                                {{ (i + 1) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.username }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.value }}
                            </td>
                            <td>
                                <span>
                                    Online/ offline    
                                </span>
                            </td>
                            <!--   <td class="px-4 py-3 text-sm">
                                <SecondaryButton @click="openModalView(a)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>


                                </SecondaryButton>
                            </td> -->
                            <td class="px-4 py-3 text-sm">
                                <WarningButton @click="openModalForm(2, r)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                </WarningButton>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <DangerButton @click="openModalDel(r)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>

                                </DangerButton>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>

        </div>



        <!-- MODAL PARA FORMULARIO DE REGISTRO -->

        <Modal :show="showModalForm" @close="closeModalForm">

            <div class="p-4">
                <h2 class="pb-4 text-lg font-medium text-gray-900">{{ title }}</h2>
                <form @submit.prevent="save">

                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="username" id="floating_first_name" v-model="form.username"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-black dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            Nombre de usuario</label>
                    </div>
                    <div class="relative z-0 mb-5 group">
                        <input type="text" name="password" id="floating_password" v-model="form.password"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-black dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_password"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            Contraseña</label>
                    </div>

                    <div class="m-2 flex justify-end">
                        <PrimaryButton>save</PrimaryButton>

                        <SecondaryButton @click="closeModalForm">Cancel</SecondaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- MODAL PARA ELIMINAR DATOS -->

        <Modal :show="showModalDel" @close="closeModalDel">
            <div class="p-4">
                <p class="text-2xl text-gray-500">
                    Are you sure detele to
                    <span class="text-2xl font-medium text-gray-900">{{ eform.username }}</span> ?
                </p>
                <div class="pt-1">

                    <PrimaryButton @click="deleteUser">Yes, delete</PrimaryButton>

                    <SecondaryButton @click="closeModalDel">Cancel</SecondaryButton>
                </div>


            </div>

        </Modal>



    </AuthenticatedLayout>
</template>
