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

    <Head title="Iniciar Sesión" />

    <GuestLayout>
        <!-- Logo Section -->
        <div class="mb-8 text-center">
            <div class="mb-4 flex justify-center">
                <img src="/images/logo_fenix.png" alt="Logo" class="h-16 w-auto">
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Portal ISP</h1>
            <p class="mt-2 text-sm text-gray-600">Accede a tu cuenta de cliente</p>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-red-700 font-medium">{{ errorMessage }}</p>
            </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="space-y-5">

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400">
                            <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                            <path d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                        </svg>
                    </div>
                    <input type="email" id="email"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-transparent transition-all shadow-sm hover:border-gray-400"
                        placeholder="tu@correo.com"
                        v-model="form.email"
                        required
                        autofocus />
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-gray-400">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" id="password"
                        class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:border-transparent transition-all shadow-sm hover:border-gray-400"
                        placeholder="••••••••"
                        v-model="form.password"
                        required />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    :disabled="form.processing"
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-gray-700 to-gray-800 text-white font-medium rounded-lg hover:from-gray-800 hover:to-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <span v-if="form.processing" class="inline-block">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span v-if="form.processing">Iniciando sesión...</span>
                    <span v-else>Iniciar Sesión</span>
                </button>
            </div>
        </form>

        <!-- Footer Links -->
        <div v-if="canResetPassword" class="mt-6 text-center">
            <Link :href="route('password.request')" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition">
                ¿Olvidaste tu contraseña?
            </Link>
        </div>
    </GuestLayout>
</template>
