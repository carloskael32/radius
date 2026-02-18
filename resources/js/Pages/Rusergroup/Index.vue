<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import { router } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';



const props = defineProps({
    usrprf: { type: Array },
    usrpp: { type: Array },
    grppf: { type: String },
});

// el vaor del perfil 
//const perfil = ref(props.grppf);
const perfil = props.grppf;


//para seleccionar usuarios
const selectedUsers = ref([])


// enviar datos seleccionados hacie el controlador
const enviarSeleccionados = () => {
    const usuarios = selectedUsers.value.map(u => u.username);
    router.post(route('ruserg.store'), { usuarios, perfil });
    selectedUsers.value = []; // Limpiar la selección después de enviar

}




//Volver atras
const goBack = () => {
    window.history.back();
}
//Sirven para manejar el estado de los 
// campos del formulario en Vue usando inertia



// manejo de datos de formulario
//Sirven para manejar el estado de los 
// campos del formulario en Vue usando inertia
const form = useForm({
    username: '',
});

const uform = ref({
    id: '',
    username: '',
})

//inicializacion de modal
const showModalDel = ref(false);


// Cerrar modal
const closeModalDel = () => {
    showModalDel.value = false;
}

// Modal para eliminar
const openModalDel = (u) => {
    showModalDel.value = true;
    uform.value.id = u.id;
    uform.value.username = u.username;

}


//Eliminacion de datos
const deluser = () => {
    form.delete(route('ruserg.destroy', uform.value.id), {
        onSuccess: () => {
            //ok('Perfil eliminado correctamente');
            closeModalDel();
        },
    })
}


const toggleUser = (user) => {
    const idx = selectedUsers.value.findIndex(u => u.id === user.id);
    if (idx === -1) selectedUsers.value.push(user);
    else selectedUsers.value.splice(idx, 1);
};


</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between w-full"> <!-- Contenedor flex -->
                <span class="font-semibold">Usuarios del perfil - {{ grppf }}</span>
                <!-- Título a la izquierda -->
                <button @click="goBack"
                    class="bg-gray-950 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-full shadow-md transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                </button>
            </div>
        </template>

        <!-- Multi select arriba de la tabla -->
        <!-- original  multiselec-->
        <!--  <div class="max-w-3xl mx-auto my-3">
            <label class="block mb-1 font-semibold">Selecciona los usuarios:</label>
            <multiselect v-model="selectedUsers" :options="usrpp" :multiple="true" :close-on-select="false"
                placeholder="Selecciona usuarios" label="username" track-by="id" class="w-full" />
            <div class="mt-2 text-sm text-gray-700">
                Seleccionados:
                <span v-if="selectedUsers.length">
                    {{selectedUsers.map(u => u.username).join(', ')}}
                </span>
                <span v-else class="text-gray-400">
                    Ninguno
                </span>
            </div>
        </div> -->



<!-- CUERPO -->
        <div class="grid grid-cols-2 gap-2">

            <div>
                <table class="w-full divide-y divide-gray-200">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                #
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                username
                            </th>
                            <th
                                class=" border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(u, i) in usrprf" :key="u.id" class="text-gray-700">
                            <th class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                            </th>
                            <td class="text-center border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ u.username }}</p>
                            </td>

                            <td class="border-b border-gray-200 bg-white text-center">
                                <DangerButton @click="openModalDel(u)">
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


            <div class="p-2">
                <label class="block mb-1 font-semibold">Selecciona los usuarios:</label>

                <!-- Lista de usuarios -->
                <div class="grid grid-cols-1 gap-2">
                    <div v-for="user in usrpp" :key="user.id" class="cursor-pointer px-3 py-2 border rounded-md"
                        :class="selectedUsers.find(u => u.id === user.id) ? 'bg-blue-500 text-white' : 'bg-red-100'"
                        @click="toggleUser(user)">
                        {{ user.username }}
                    </div>
                </div>

                <!-- Mostrar seleccionados -->
                <div class="mt-2 text-sm text-gray-700">
                    Seleccionados:
                    <span v-if="selectedUsers.length">
                        {{selectedUsers.map(u => u.username).join(', ')}}
                    </span>
                    <span v-else class="text-gray-400">
                        Ninguno
                    </span>
                </div>
                <div class="flex justify-center">
                    <button class="mt-3 mb-3 px-4 py-2 bg-blue-600 text-white rounded" @click="enviarSeleccionados">
                        Registrar seleccionados
                    </button>

                </div>
            </div>


        </div>

        <!-- MODAL PARA ELIMINAR DATOS -->
        <Modal :show="showModalDel" @close="closeModalDel">
            <div class="p-6">
                <p class="text-2xl text-gray-500">
                    Are you sure detele to
                    <span class="text-2xl font-medium text-gray-900">{{ uform.username }}</span>
                    ?
                </p>
                <PrimaryButton @click="deluser">Yes, delete</PrimaryButton>
            </div>
            <div class="m-6 flex justify-end">
                <SecondaryButton @click="closeModalDel">Cancel</SecondaryButton>
            </div>
        </Modal>


    </AuthenticatedLayout>
</template>
