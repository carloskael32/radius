<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const errorMessage = ref('');

const submit = () => {
    errorMessage.value = '';
    form.post(route('login'), {
        onSuccess: () => {
            errorMessage.value = '';
        },
        onError: (errors) => {
            if (errors.email || errors.password) {
                errorMessage.value = 'Correo o contraseña incorrectos. Intenta de nuevo.';
                //alert('❌ ' + errorMessage.value);
            }
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Login" />

    <GuestLayout>

        <img src="/images/logo_fenix.png" alt="">
        <p class=" text-center">
            Portal ISP <br>
            accede a tu cuenta de cliente
        </p>
        <br>


        <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ errorMessage }}
        </div>

        <form @submit.prevent="submit">

            <!-- INPUT DE CORREO -->
            <label for="input-group-1" class="block mb-2.5 text-sm font-medium text-heading">Correo electronico</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path
                            d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                        <path
                            d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                    </svg>
                </div>
                <input type="email" id="email"
                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-lg focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                    placeholder="tu@correo.com" v-model="form.email" required autofocus />
            </div>
            <br>

            <!-- INPUT DE CONTRASEÑA -->
            <label for="input-group-1" class="block mb-2.5 text-sm font-medium text-heading">Contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="password" id="password"
                    class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-lg focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                    placeholder="••••••••" v-model="form.password" required />
            </div>




            <div class="mt-6">
                <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iniciar sesión
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
