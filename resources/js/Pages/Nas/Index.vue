<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DarkButton from '@/Components/DarkButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({
    nas: { type: Array },
});
const form = useForm({
    nasname: '',
    shortname: '',
    type: '',
    ports: '',
    secret: '',
    server: '',
    /*     community: '',
        description: '',
        require_ma: '',
        limit_proxy_state: '', */
});

const eform = ref({
    nasname: '',
    shortname: '',
    type: '',
    ports: '',
    secret: '',
    server: '',
    community: '',
    description: '',
    require_ma: '',
    limit_proxy_state: '',

});


//ENVIO DE DATOS AL CONTROLADOR
const save = () => {
    if (operation.value == 1) {
        form.post(route('nas.store'), {
            onSuccess: () => {
                ok('Router registrado');
                closeModalForm();
            },
        });
    } else {
        form.put(route('nas.update', eform.value.id), {
            onSuccess: () => {
                 ok('Router actualizado') 
                 closeModalForm();
                },
        });
    }
};

//ELIMINACION DE DATOS
const deleteNas = () => {
    form.delete(route('nas.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Router eliminado');
            closeModalDel();
        },
    });
}


//MENSAJES DE CONFIRMACION
const ClassMsj = ref('hidden');
const msj = ref('');

const ok = (m) => {
    form.reset();
    msj.value = m;
    ClassMsj.value = 'block';
    setTimeout(() => {
        ClassMsj.value = 'hidden';
    }, 5000);
}



//CONTROLADOR DE MODAL DE FORMULARIO
const showModalForm = ref(false);
const showModalDel = ref(false);
const operation = ref(1);
const title = ref('');

const openModalForm = (op, n) => {
    showModalForm.value = true;
    operation.value = op;
    if (op == 1) {
        title.value = 'Registrar Router';
    } else {
        title.value = 'Actualizar Router';
        form.nasname = n.nasname;
        form.shortname = n.shortname;
        form.type = n.type;
        form.ports = n.ports;
        form.secret = n.secret;
        form.server = n.server;
        eform.value.id = n.id;
    };
};
const closeModalForm = () => {
    showModalForm.value = false;
    form.reset();
}
const openModalDel = (n) => {
    showModalDel.value = true;
    eform.value.id = n.id;
    eform.value.nasname = n.nasname;
    eform.value.shortname = n.shortname;
}

const closeModalDel = () => {
    showModalDel.value = false;
}


</script>


<template>

    <Head title="Nas" />

    <AuthenticatedLayout>
        <template #header>
            Mikrotiks
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
                    <p class="text-sm text-gray-600">{{ msj }}</p>
                </div>
            </div>
        </div>

        <!-- CUERPO -->
        <div class="w-full overflow-hidden rounded-lg border shadow-md">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Ipv4</th>
                            <th class="px-4 py-3">shortname</th>
                            <th class="px-4 py-3">type</th>
                            <th class="px-4 py-3">ports</th>
                            <th class="px-4 py-3">secret</th>
                            <th class="px-4 py-3">server</th>
                            <th class="px-4 py-3">community</th>
                            <th class="px-4 py-3">description</th>
                            <th class="px-4 py-3">require_ma</th>
                            <th class="px-4 py-3">limit_proxy_state</th>

                            <th class="px-4 py-3">Edit</th>
                            <th class="px-4 py-3">Delete</th>
                        </tr>
                    </thead>
                    <tbody
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <tr v-for="n, i in nas" :key="n.id" class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                {{ (i + 1) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.nasname }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.shortname }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.type }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.ports }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.secret }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.server }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.community }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.description }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.require_ma }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ n.limit_proxy_state }}
                            </td>


                            <td class="px-4 py-3 text-sm">
                                <WarningButton @click="openModalForm(2, n)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                </WarningButton>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <DangerButton @click="openModalDel(n)">
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
                        <input @input="form.nasname = form.nasname.replace(/[^0-9.]/g, '')" type="text" name="ipv4"
                            id="floating_first_name" v-model="form.nasname"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            IPv4</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="shortname" id="floating_first_name" v-model="form.shortname"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            shortname</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="type" id="floating_first_name" v-model="form.type"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "  />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            type</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="number" name="ports" id="floating_first_name" v-model="form.ports"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "  />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            ports</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="secret" id="floating_first_name" v-model="form.secret"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            secret</label>
                    </div>
                    <div class="relative z-0  mb-4 group">
                        <input type="text" name="server" id="floating_first_name" v-model="form.server"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "  />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            server</label>
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
                    <span class="text-2xl font-medium text-gray-900">{{ eform.nasname + ' ' + eform.shortname }}</span>
                    ?
                </p>
                <PrimaryButton @click="deleteNas">Yes, delete</PrimaryButton>
            </div>
            <div class="m-6 flex justify-end">
                <SecondaryButton @click="closeModalDel">Cancel</SecondaryButton>
            </div>
        </Modal>




    </AuthenticatedLayout>
</template>
