<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        
            <div class="p-2 overflow-hidden shadow-xl sm:rounded-lg ">
                <!-- <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div> -->

                <div class="p-6 bg-white flex justify-center">
                    <UpdatePasswordForm class="max-w-xl " />
                </div>

                <!-- <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div> -->
            </div>
    
    </AuthenticatedLayout>
</template>