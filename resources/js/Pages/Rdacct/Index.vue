<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import WarningButton from '@/Components/WarningButton.vue';

const props = defineProps({
    rdacct: { type: Object }
});

</script>


<template>

    <Head title="About us" />

    <AuthenticatedLayout>
        <template #header>
            Registro de sesion de usuarios PPPoE
        </template>

        <div class="w-full overflow-hidden rounded-lg border shadow-md">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">#</th>

                            <th class="px-4 py-3">acctsessionid</th>
                            <th class="px-4 py-3">acctuniqueid</th>
                            <th class="px-4 py-3">username</th>
                            <th class="px-4 py-3">groupname</th>
                            <th class="px-4 py-3">nasipaddress</th>
                            <th class="px-4 py-3">nasportid</th>
                            <th class="px-4 py-3">nasporttype</th>
                            <th class="px-4 py-3">acctstarttime</th>
                            <th class="px-4 py-3">acctupdatetime</th>
                            <th class="px-4 py-3">acctauthentic</th>
                            <th class="px-4 py-3">calledstationid</th>
                            <th class="px-4 py-3">servicetype</th>
                            <th class="px-4 py-3">framedprotocol</th>
                            <th class="px-4 py-3">framedipaddress</th>

                           <!--  <th class="px-4 py-3">opciones</th> -->

                        </tr>
                    </thead>
                    <tbody
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                        <tr v-for="r, i in rdacct.data" :key="r.radacctid" class="text-gray-700">
                            <td class="px-4 py-3 text-sm">
                                {{ (i + 1) }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.acctsessionid }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.acctuniqueid }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.username }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.groupname }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.nasipaddress }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.nasportid }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.nasporttype }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.acctstarttime }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.acctupdatetime }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.acctauthentic }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.calledstationid }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.servicetype }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.framedprotocol }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ r.framedipaddress }}
                            </td>

                         <!--    <td class="px-4 py-3 text-sm">
                                <WarningButton @click="openModalForm(2, n)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                </WarningButton>
                            </td> -->
                        </tr>


                    </tbody>
                </table>
            </div>

            <div
                class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9 flex justify-center">
                <pagination :links="rdacct.links" />
            </div>
        </div>




    </AuthenticatedLayout>
</template>