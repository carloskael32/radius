<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'


const props = defineProps({
    usrpp: {
        type: Array,
        
    }
});

//para seleccionar usuarios
const selectedUsers = ref([])

</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            Grupos de usuarios
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




        <div class="mb-4 inline-flex w-full overflow-hidden rounded-lg bg-white shadow-md">
            <div class="flex w-12 items-center justify-center bg-blue-500">
                <svg class="h-6 w-6 fill-current text-white" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z">
                    </path>
                </svg>
            </div>

            <div class="-mx-3 px-4 py-2">
                <div class="mx-3">
                    <span class="font-semibold text-blue-500">Info</span>
                    <p class="text-sm text-gray-600">Sample table page</p>
                </div>
            </div>
        </div>

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
                <tr v-for="u, i in usrpp" :key="u.id" class="text-gray-700">
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
