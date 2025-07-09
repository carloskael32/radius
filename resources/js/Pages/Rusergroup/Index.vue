<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import { router } from '@inertiajs/vue3';

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

</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <span>
                Usuarios del perfil - {{ grppf }}
            </span>

        </template>

        <!-- Multi select arriba de la tabla -->
        <div class="mb-4">
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
        </div>

        <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded" @click="enviarSeleccionados">
            Registrar seleccionados
        </button>

        <!-- CUERPO -->
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        #
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        username
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="u, i in usrprf" :key="u.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ u.username }}</p>
                    </td>
                </tr>
            </tbody>
        </table>




    </AuthenticatedLayout>
</template>
