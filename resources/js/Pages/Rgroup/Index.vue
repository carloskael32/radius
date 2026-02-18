<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from "primevue/usetoast";


//recibimos los datos del controlador
const props = defineProps({
    rgroupchk: { type: Array },

});

 

//Sirven para manejar el estado de los 
// campos del formulario en Vue usando inertia
const form = useForm({
    groupname: '',
    value: '',
    valued: '',
    valueu: '',
    navd: '',
    navu: '',
});

const eform = ref({
    id: '',
    groupname: '',
    valued: '',
    valueu: '',
    navd: '',
    navu: '',
});


//ENVIO DE DATOS AL CONTROLADOR
const save = () => {
    if (operation.value == 1) {
        form.post(route('rgroup.store'), {
            onSuccess: () => {
                ok('Perfil registrado correctamente');
                closeModalForm();
            },
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
//Eliminacion de GRUPOS DE SERVICIO
const deletrgroup = () => {
    form.delete(route('rgroup.destroy', eform.value.id), {
        onSuccess: () => {
            ok('Perfil eliminado correctamente');
            closeModalDel();
        },
    })
}

// TOAST MENSAJE DE CONFIRMACION
const toast = useToast();

//mensaje de confirmacion
const ok = (m) => {
    form.reset();
    toast.add({ severity: 'success', summary: 'Éxito', detail: m, life: 3000 });
}
const error = (m) => {
    form.reset();
    toast.add({ severity: 'warn', summary: 'Advertencia', detail: m, life: 3000 });
}

//CONTROLADOR DE MODAL DE FORMULARIO
const showModalForm = ref(false);
const showModalDel = ref(false);
const operation = ref(1);
const title = ref('');


//para ver si es un registro nuevo o edicion
const openModalForm = (op, r) => {
    showModalForm.value = true;
    operation.value = op;
    if (op == 1) {
        title.value = 'Agregar nuevo plan de servicio';
    } else {
        title.value = 'Editar plan de servicio';
        form.groupname = r.groupname;
        form.value = r.value;

        //para manejar la cadena de texto
        const cadena = r.value;
        const partes = cadena.split("/");
        const valor = ref([])

        partes.forEach(partes => {
            const match = partes.match(/^(\d+)([A-Za-z]+)$/);

            valor.value.push(match[1])
            valor.value.push(match[2])
            //console.log(valor);
        });
        form.valued = valor.value[0];
        form.navd = valor.value[1];
        form.valueu = valor.value[2];
        form.navu = valor.value[3];
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
    //eform.value.value = r.value;
}

const closeModalDel = () => {
    showModalDel.value = false;
    showModalDelClient.value = false;
}

//CONTROLADOR DE MODAL DE FORMULARIO DE ADICION DE CLIENTES 
// VARIABLES PARA EL MODAL DE GESTIONAR CLIENTES
const modalData = ref({
    groupId: null,
    groupName: '',
    clients: [],
    clsngr: [],
    loading: false,
});

// Variables para gestionar selecciones de usuarios
const selectedClients = ref([]);
const selectedClientsNoGroup = ref([]);
const showModalDelClient = ref(false);
const showModalAdd = ref(false);


// para adicionar clientes al grupo de servicio
const openModalAddUsr = (r) => {
    showModalAdd.value = true;
    modalData.value.loading = true;
    modalData.value.groupId = r.id;
    modalData.value.groupName = r.groupname;

    // Enviar datos al controlador
    axios.get(route('rgroup.show', r.groupname))
        .then(response => {
            modalData.value.clients = response.data.clients || [];
            modalData.value.clsngr = response.data.clsngr || [];
            modalData.value.loading = false;
            
        })
        .catch(error => {
            console.error('Error al cargar datos del grupo:', error);
            error('Error al cargar los datos');
            modalData.value.loading = false;
            closeModalAdd();
        })
        .finally(() => {

        });
}

const closeModalAdd = () => {
    showModalAdd.value = false;
    selectedClients.value = [];
    selectedClientsNoGroup.value = [];
    //router.get(route('rgroup.index'));
}

// Función para recargar datos del modal sin cerrarlo
const refreshModalData = () => {
    modalData.value.loading = true;
    axios.get(route('rgroup.show', modalData.value.groupName))
        .then(response => {
            modalData.value.clients = response.data.clients || [];
            modalData.value.clsngr = response.data.clsngr || [];
            selectedClients.value = [];
            selectedClientsNoGroup.value = [];
            modalData.value.loading = false;
        })
        .catch(error => {
            console.error('Error al recargar datos:', error);
            error('Error al recargar los datos');
            modalData.value.loading = false;
        });
}

// Método para guardar los clientes seleccionados
const saveClientsToGroup = () => {
    // Combinar las selecciones de ambas listas
    const selectedUserIds = [...selectedClients.value, ...selectedClientsNoGroup.value];

    if (selectedUserIds.length === 0) {
        error('Debes seleccionar al menos un cliente');
        return;
    }

    // Enviar datos al controlador
    axios.post(route('rgroup.assignClients', modalData.value.groupId), {
        clients: selectedUserIds
    })
        .then(response => {
            ok('Clientes asignados correctamente');
            refreshModalData();
            closeModalAdd();
            
        })
        .catch(error => {
            console.error('Error al asignar clientes:', error);
            error('Error al asignar los clientes');
        });
}
const Clform = ref({
    id: '',
    username: '',
})

// Modal para eliminar cliente del grupo de servicio
const openModalDelClient = (client) => {
    showModalDelClient.value = true;
    Clform.value.id = client.id;
    Clform.value.username = client.username;

}



// eliminar los clientes de los grupos
const DelteClient = () => {

    axios.post(route('rgroup.delClients', Clform.value.id))
        .then(response => {
            ok('Cliente Eliminado');
            showModalDelClient.value = false;
            refreshModalData();
        })
        .catch(error => {
            console.error('Error al eliminar clientes:', error);
            error('Error al eliminar los clientes');
        });
}

</script>

<template>

    <Head title="Planes de servicio" />

    <AuthenticatedLayout>


        <!-- CABECERA  -->
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-100">
            <div class="flex items-start justify-between pb-4">
                <div>
                    <h1 class="mb-1 text-2xl font-semibold text-gray-900">Gestión de Planes</h1>
                    <p class="text-md text-gray-500">Configura los planes de servicio para tus clientes</p>
                </div>
                <div class="flex items-center gap-3">
                    <PrimaryButton @click="openModalForm(1)" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Crear Plan
                    </PrimaryButton>
                </div>
            </div>

            <!-- CUERPO DE LA PAGINA -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div v-for="(r, i) in rgroupchk" :key="r.id"
                    class="relative bg-neutral-primary-soft max-w-xs p-4 border border-indigo-200 rounded-base shadow-xl rounded-md ">

                    <div class="flex justify-center uppercase">
                        <b>
                            <p>{{ r.groupname }}</p>
                        </b>
                        <!-- <p class="flex justify-end">activo</p> -->
                    </div>


                    <hr class="w-full h-0.5 bg-gray-400 mt-2 mb-2">

                    <div class="grid grid-cols-2 gap-2 items-center p-1">

                        <div
                            class=" flex items-center gap-1 justify-center text-center bg-green-100  border border-green-200 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-6">
                                <path
                                    d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                <path
                                    d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>
                            Download
                            <br>
                            <!-- {{ r.value.split('/')[0].replace(/\D/g, '') }} --> {{ r.value.split('/')[0] }}
                        </div>
                        <div
                            class="flex items-center gap-1 justify-center text-center bg-orange-100  border border-orange-200 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-6">
                                <path
                                    d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" />
                                <path
                                    d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                            </svg>

                            Upload
                            <br>
                            <!-- {{ r.value.split('/')[1].replace(/\D/g, '') }} --> {{ r.value.split('/')[1]}}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 p-4">
                        <p class="flex justify-start">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="size-5">
                                <path
                                    d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z" />
                            </svg>
                        <p class="pl-2">Clientes</p>
                        </p>

                        <p class="flex justify-end">{{ r.total_usuarios }}</p>
                    </div>

                    <button @click="openModalAddUsr(r)"
                        class="inline-flex w-full items-center justify-center p-1 rounded-md text-white bg-blue-600 hover:bg-blue-400 active:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <p class="pl-1 text-white">Gestionar Clientes</p>

                    </button>




                    <hr class="w-full h-0.5  bg-gray-400 mt-2 mb-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <button @click="openModalForm(2, r)"
                                class="inline-flex w-full items-center justify-center p-2 rounded-md hover:bg-blue-100 active:bg-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                                <p class="pl-1 text-blue-600">Editar</p>
                            </button>
                        </div>
                        <div>
                            <button @click="openModalDel(r)"
                                class="inline-flex w-full items-center justify-center p-2 rounded-md hover:bg-red-100 active:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <p class="pl-1 text-red-600">Eliminar</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>








        <!-- MODAL PARA FORMULARIO DE REGISTRO Y EDITAR GRUPOS DE PLANES DE SERVICIO -->

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


                    <div class="pb-6">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Nombre de Plan*
                            </label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>

                                </div>
                            </div>
                            <input type="text" name="plan" v-model="form.groupname" id="plan"
                                class="bext-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs rounded-md"
                                placeholder="premiun" required />
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-4 pb-6">
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Velocidad
                                Descarga*</label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex">
                                <input type="number" v-model="form.valued"
                                    class="bext-heading text-sm focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9 shadow-xs placeholder:text-body rounded-s-md"
                                    placeholder="50" required />
                                <select for v-model="form.navd" class=" rounded-e-md py-1" required>
                                    <option value="">Selecciona</option>
                                    <option value="K">KB</option>
                                    <option value="M">MB</option>
                                    <option value="G">GB</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="visitors" class="block mb-1.5 text-sm font-medium text-heading">Velocidad
                                Subida* </label>
                            <div class="relative">
                                <div class="absolute p-2 start-0 flex items-center ps-2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>

                                </div>
                            </div>
                            <div class="flex">
                                <input type="number" v-model="form.valueu"
                                    class="bext-heading text-sm focus:ring-brand focus:border-brand block w-full px-2.5 py-2 ps-9  rounded-s-md"
                                    placeholder="50" required />
                                <select v-model="form.navu" class=" rounded-e-md py-1" required>
                                    <option value="">Selecciona</option>
                                    <option value="K">KB</option>
                                    <option value="M">MB</option>
                                    <option value="G">GB</option>
                                </select>
                            </div>
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


        <!-- MODAL PARA ELIMINAR GRUPOS DE PLANES DE SERVICIO -->
        <Modal :show="showModalDel" @close="closeModalDel" maxWidth="md">
            <div class="p-5">
                <div class="flex justify-between items-center pb-6">
                    <h2 class="text-lg font-medium text-gray-900"><u>Confirmar eliminación</u></h2>
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
                                Está por eliminar el siguiente Plan de Servicio:
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900">
                                {{ eform.groupname }}
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
                        <DangerButton class="w-full" @click="deletrgroup">Eliminar
                        </DangerButton>
                    </div>
                </div>
            </div>
        </Modal>


        <!-- MODAL PARA ADICIONAR CLIENTES A GRUPOS  DE SERVICIO -->
        <Modal :show="showModalAdd" @close="closeModalAdd" maxWidth="xxxl">
            <div class="p-5">
                <div class="flex justify-between items-center pb-4">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Gestionar Clientes</h2>
                        <p class="text-sm text-gray-500">Plan: <span class="font-semibold">{{ modalData.groupName
                        }}</span></p>
                    </div>
                    <button @click="closeModalAdd"
                        class="rounded-md text-white bg-red-600 hover:bg-red-400 active:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- INDICADOR DE CARGA -->
                <div v-if="modalData.loading" class="flex items-center justify-center py-8">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 animate-spin text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.995-1.465" />
                        </svg>
                        <span class="text-gray-600">Cargando datos...</span>
                    </div>
                </div>


                <!-- CONTENIDO CUANDO LOS DATOS ESTÁN CARGADOS -->
                <div v-else class="pb-6">
                    <!-- LISTA DE CLIENTES -->
                    <div v-if="modalData.clients && modalData.clients.length > 0 || modalData.clsngr && modalData.clsngr.length > 0"
                        class="space-y-3">

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <div class="text-sm font-medium text-gray-700 mb-1">
                                    Clientes registrados: <span class="text-blue-600 font-semibold">{{
                                        modalData.clients.length
                                        }}</span>
                                </div>
                                <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                                    <div v-for="client in modalData.clients" :key="client.id"
                                        class="flex items-center justify-between p-3 border-b border-gray-100 hover:bg-gray-50">
                                        <div class="flex items-center gap-3">
                                            <!--  <input type="checkbox" :id="`client-${client.id}`" :value="client.username"
                                                v-model="selectedClients" class="rounded" /> -->
                                            <div>
                                                <label :for="`client-${client.id}`"
                                                    class="font-medium text-gray-900 cursor-pointer">
                                                    {{ client.username }}
                                                </label>
                                                <p class="text-xs text-gray-500">{{ client.email || 'Sin correo' }}</p>
                                            </div>
                                        </div>
                                        <button @click="openModalDelClient(client)"
                                            class="inline-flex items-center justify-center p-2 rounded-md hover:bg-red-100 active:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-red-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-700 mb-1">
                                    Clientes sin grupo(plan): <span class="text-blue-600 font-semibold">{{
                                        modalData.clsngr.length
                                        }}</span>
                                </div>
                                <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                                    <div v-for="csgrp in modalData.clsngr" :key="csgrp.id"
                                        class="flex items-center justify-between p-3 border-b border-gray-100 hover:bg-gray-50">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" :id="`csgrp-${csgrp.id}`" :value="csgrp.username"
                                                v-model="selectedClientsNoGroup" class="rounded" />
                                            <div>
                                                <label :for="`csgrp-${csgrp.id}`"
                                                    class="font-medium text-gray-900 cursor-pointer">
                                                    {{ csgrp.username }}
                                                </label>
                                                <p class="text-xs text-gray-500">{{ csgrp.email || 'Sin correo' }}</p>
                                            </div>
                                        </div>
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                            {{ csgrp.status || 'activo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- MENSAJE SI NO HAY CLIENTES -->
                    <div v-else class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-12 mx-auto text-gray-300 mb-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <p class="text-gray-500">No hay clientes disponibles</p>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <SecondaryButton class="w-full" @click="closeModalAdd">Cancelar</SecondaryButton>
                    <PrimaryButton class="w-full" :disabled="modalData.loading" @click="saveClientsToGroup">Guardar
                        Clientes Seleccionados</PrimaryButton>
                </div>
            </div>
        </Modal>


        <!-- MODAL PARA ELIMINAR CLIENTE DEL GRUPO DE SERVICIO -->
        <Modal :show="showModalDelClient" @close="closeModalDel" maxWidth="md">
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
                                Está por eliminar el siguiente cliente:
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900">
                                {{ Clform.username }} <span class="text-gray-500 font-medium">({{ Clform.id ||
                                    'ninguno'
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
                        <PrimaryButton class="w-full bg-red-600 hover:bg-red-700" @click="DelteClient">Eliminar
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
