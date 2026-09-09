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
    username: '',
    password: '',
    remember: false
});

const errorMessage = ref('');
const errorType = ref(''); // 'blocked', 'invalid', 'throttle'

const submit = () => {
    errorMessage.value = '';
    errorType.value = '';
    
    form.post(route('login'), {
        onSuccess: () => {
            errorMessage.value = '';
        },
        onError: (errors) => {
            if (errors.username) {
                const message = errors.username;
                
                // Detectar tipo de error
                if (message.includes('bloqueada') || message.includes('bloqueado')) {
                    errorType.value = 'blocked';
                } else if (message.includes('demasiadas') || message.includes('throttle')) {
                    errorType.value = 'throttle';
                } else {
                    errorType.value = 'invalid';
                }
                
                errorMessage.value = message;
            }
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Iniciar Sesión" />

    <GuestLayout>
        <div class="mb-6 text-center">
            <div class="flex min-w-0 items-center justify-center gap-2 text-lg font-serif text-black dark:text-white sm:text-2xl md:text-4xl">
                <img src="/images/fenix.png" alt="Logo de Fenix Telecom" class="h-12 w-auto shrink-0 sm:h-16 md:h-20">
                <b class="shrink-0 whitespace-nowrap">FENIX TELECOM</b>
            </div>
            <h1 class="mt-2 text-xl font-bold text-slate-900 dark:text-slate-100 sm:text-2xl">Portal ISP</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Accede a tu cuenta de cliente</p>
        </div>

        <div v-if="errorMessage" :class="[
            'mb-6 rounded-xl border p-4',
            errorType === 'blocked'
                ? 'border-red-300 bg-red-50 dark:border-red-500/50 dark:bg-red-900/20'
                : errorType === 'throttle'
                    ? 'border-amber-300 bg-amber-50 dark:border-amber-500/50 dark:bg-amber-900/20'
                    : 'border-red-300 bg-red-50 dark:border-red-500/50 dark:bg-red-900/20'
        ]">
            <div class="flex items-start gap-3">
                <svg v-if="errorType === 'blocked'" xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                <svg v-else-if="errorType === 'throttle'" xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>

                <div>
                    <p v-if="errorType === 'blocked'" class="text-sm font-medium text-red-900 dark:text-red-200">⛔ Cuenta Bloqueada</p>
                    <p v-else-if="errorType === 'throttle'" class="text-sm font-medium text-amber-900 dark:text-amber-200">⏱️ Demasiados Intentos</p>
                    <p v-else class="text-sm font-medium text-red-900 dark:text-red-200">⚠️ Error de Autenticación</p>
                    <p :class="['mt-1 text-sm', errorType === 'blocked' ? 'text-red-700 dark:text-red-300' : errorType === 'throttle' ? 'text-amber-700 dark:text-amber-300' : 'text-red-700 dark:text-red-300']">
                        {{ errorMessage }}
                    </p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="username" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Nombre de usuario</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-slate-400">
                            <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                            <path d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                        </svg>
                    </div>
                    <input type="text" id="username" autocomplete="username" class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-11 pr-4 text-slate-900 placeholder:text-slate-400 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="jperez" v-model="form.username" required autofocus="autofocus" />
                </div>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">Contraseña</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-slate-400">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" id="password" autocomplete="current-password" class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-11 pr-4 text-slate-900 placeholder:text-slate-400 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500" placeholder="••••••••" v-model="form.password" required />
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" :disabled="form.processing" class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-2.5 font-medium text-white shadow-md transition hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-50">
                    <span v-if="form.processing" class="inline-block">
                        <svg class="-ml-1 mr-2 inline h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span v-if="form.processing">Iniciando sesión...</span>
                    <span v-else>Iniciar Sesión</span>
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
