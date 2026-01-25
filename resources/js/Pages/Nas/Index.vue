<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const props = defineProps({
    nas: { type: Object },
    total: { type: Array },
});


const form = useForm({
    nasname: '',
    shortname: '',
    type: '',
    ports: '',
    secret: '',
    description: '',
    /*  host: '', */
    user: '',
    pass: '',
    port: '',
    status: '',

});

const eform = ref({
    nasname: '',
    shortname: '',
    type: '',
    ports: '',
    secret: '',
    description: '',
    /* host:'', */
    user: '',
    pass: '',
    port: '',
    status: '',
});


//ENVIO DE DATOS AL CONTROLADOR
const save = () => {
    if (operation.value == 1) {
        form.post(route('nas.store'), {
            onSuccess: () => {
                ok('Router registrado');
                closeModalForm();
                window.location.reload();
            },
        });
    } else {
        form.put(route('nas.update', eform.value.id), {
            onSuccess: () => {
                ok('Router actualizado')
                closeModalForm();
                window.location.reload();
            },
        });
    }
};

//ELIMINACION DE DATOS
const deleteNas = () => {
    form.delete(route('nas.destroy', eform.value.id), {
        onSuccess: () => {
            ok('NAS eliminado');
            closeModalDel();
            window.location.reload();
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
        title.value = 'Agregar Nuevo Nas';
    } else {
        title.value = 'Actualizar Nas';
        form.nasname = n.nasname;
        form.shortname = n.shortname;
        form.type = n.type;
        form.ports = n.ports;
        form.secret = n.secret;
        form.server = n.server;
        form.description = n.description;
        form.user = n.user;
        form.port = n.port;
        form.status = n.status;


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

        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de NAS</h1>
                    <p class="text-sm text-gray-500">Total: <span class="font-medium text-gray-700">{{ total }}</span>
                        NAS</p>
                </div>
                <div class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar NAS
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
                    <table class=" min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600">
                        <thead class="bg-gray-100 text-xs font-medium text-black uppercase tracking-wider text-center">
                            <tr>
                                <th class="px-4 py-3 ">#</th>
                                <th class="px-4 py-3 ">NAS / HOST</th>
                                <th class="px-4 py-3 ">Nombre Corto</th>
                                <th class="px-4 py-3 ">Tipo</th>
                                <th class="px-4 py-3 "># Puertos</th>
                                <th class="px-4 py-3 ">Clave</th>
                                <th class="px-4 py-3">Descripcion</th>
                                <th class="px-4 py-3"> Estado</th>

                                <th class="px-4 py-3">user</th>
                                <th class="px-4 py-3">password</th>
                                <th colspan="2" class="px-4 py-3 text-center">Acciones</th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-md">
                            <tr v-for="n, i in nas" :key="n.id" class="text-gray-700 hover:bg-gray-50 transition">
                                <td class="px-4 py-3 border-b border-gray-500 ">
                                    {{ (i + 1) }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500">
                                    {{ n.nasname }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500">
                                    {{ n.shortname }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500 lowercase">
                                    <span
                                        class=" bg-fuchsia-300 text-fuchsia-900 inline-block px-2  rounded-xl font-semibold">{{
                                            n.type }}</span>
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500">
                                    {{ n.ports }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500">
                                    {{ n.secret }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500 max-w-0">
                                    {{ n.description }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-500">
                                    <span v-if="n.status == 'active'"
                                        class="bg-green-300 text-green-900 inline-block px-3 rounded-xl font-semibold">
                                        {{ n.status }}
                                    </span>
                                    <span v-else
                                        class="bg-red-300 text-red-900 inline-block  px-3 rounded-xl font-semibold">
                                        {{ n.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3  border-b border-gray-500">
                                    {{ n.user }}
                                </td>
                                <td class="px-4 py-3  border-b border-gray-500 max-w-0 truncate">
                                    {{ n.pass }}
                                </td>


                                <td class="px-2 py-3 text-center">
                                    <button @click="openModalForm(2, n)"
                                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <button @click="openModalDel(n)"
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




        <!-- MODAL PARA FORMULARIO DE REGISTRO y MODIFICACION -->

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
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">NAS IP</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                            </div>
                            <input @input="form.nasname = form.nasname.replace(/[^0-9.]/g, '')" type="text" name="ipv4"
                                id="nasname" v-model="form.nasname"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="192.168.1.1" required />
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nombre
                                Corto</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5  text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>

                                </div>
                            </div>
                            <input type="text" v-model="form.shortname"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="NAS-Central" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Tipo</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>

                                </div>
                            </div>
                            <input type="text" v-model="form.type"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="Cisco" required />
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nro. de
                                Puertos</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class=" size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                                    </svg>
                                </div>
                            </div>
                            <input type="number" v-model="form.ports"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="1812" required />
                        </div>
                    </div>


                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Clave</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="text" v-model="form.secret"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="•••••" required />
                        </div>
                    </div>
                    <div class="pb-4">
                        <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Descripcion</label>
                        <textarea v-model="form.description" rows="3"
                            class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 shadow-xs placeholder:text-body rounded-md"
                            placeholder="Descripcion del NAS" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">HOST*</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z" />
                                    </svg>
                                </div>
                            </div>
                            <input type="text" name="host" id="host" v-bind:value="form.nasname"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="192.168.1.1" required disabled />
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">USUARIO*</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>


                                </div>
                            </div>
                            <input type="text" v-model="form.user"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="admin" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pb-4">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">PUERTO*</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class=" size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                                    </svg>
                                </div>
                            </div>
                            <input type="number" v-model="form.port"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="8728" required />
                        </div>
                        <div>
                            <label for="visitors"
                                class="block mb-1.5 text-sm font-medium text-heading">CONTRASEÑA*</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>

                                </div>
                            </div>
                            <input type="password" v-model="form.pass"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-md"
                                placeholder="•••••" required />
                        </div>
                    </div>



                    <h2 class="pb-1">Estado</h2>
                    <div class="grid grid-cols-6 pb-8 ">

                        <div>
                            <input v-model="form.status" id="default-radio-1" type="radio" value="active"
                                name="default-radio"
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="default-radio-1"
                                class="select-none ms-2 text-sm font-medium text-heading">Activo</label>
                        </div>
                        <div>
                            <input v-model="form.status" id="default-radio-2" type="radio" value="inactive"
                                name="default-radio"
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
                            <PrimaryButton class="w-full" @submit.prevent="save">Guardar</PrimaryButton>
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
                                Está por eliminar el siguiente NAS:
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900">
                                {{ eform.nasname }} <span class="text-gray-500 font-medium">({{ eform.shortname
                                }})</span>
                            </p>
                            <p class="mt-3 text-sm text-red-600">
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 p-1">
                    <div>
                        <SecondaryButton class="w-full" @click="closeModalDel">Cancelar</SecondaryButton>
                    </div>
                    <div>
                        <PrimaryButton class="w-full bg-red-600 hover:bg-red-700" @click="deleteNas">Eliminar
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>




    </AuthenticatedLayout>
</template>

<style>
/* ESTILO DE DATATABLE */
div.top {
    display: flex;
    justify-content: space-between;
    /* separa a izquierda y derecha */
    align-items: center;
    /* centra verticalmente */
    margin-bottom: 10px;
    /* espacio debajo */
}

div.dataTables_length {
    flex: 1;
    text-align: left;
}

div.dataTables_filter {
    flex: 1;
    text-align: right;
}

div.bottom {
    display: flex;
    justify-content: space-between;
    /* separa izquierda/derecha */
    align-items: center;
    /* centra verticalmente */
    margin-top: 10px;
    /* espacio arriba */
}

div.dataTables_info {
    flex: 1;
    text-align: left;
}

div.dataTables_paginate {
    flex: 1;
    text-align: right;
}
</style>