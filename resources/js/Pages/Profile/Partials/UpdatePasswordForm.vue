<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const toast = useToast();

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Éxito', detail: 'Contraseña actualizada correctamente', life: 3000 });
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
        <header class="flex items-start gap-3 border-b border-slate-200 pb-5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100">
                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Actualizar Contraseña</h2>
                <p class="mt-1 text-sm text-slate-500">Usa una contraseña larga y única para proteger tu cuenta.</p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-5">
            <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 text-sm text-slate-600">
                <p class="font-medium text-slate-700">Consejo de seguridad</p>
                <p class="mt-1">Combina letras, números y símbolos para que tu contraseña sea más difícil de adivinar.</p>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <InputLabel for="current_password" value="Contraseña Actual" />
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 0 1 21.75 8.25Z" />
                            </svg>
                        </span>
                        <TextInput id="current_password" ref="currentPasswordInput" v-model="form.current_password"
                            type="password" class="block w-full rounded-xl border-slate-300 bg-white py-3 pl-10 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" autocomplete="current-password" placeholder="••••••••" />
                    </div>
                    <InputError :message="form.errors.current_password" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="password" value="Nueva Contraseña" />
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <TextInput id="password" ref="passwordInput" v-model="form.password"
                            type="password" class="block w-full rounded-xl border-slate-300 bg-white py-3 pl-10 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="password_confirmation" value="Confirmar Nueva Contraseña" />
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </span>
                        <TextInput id="password_confirmation" v-model="form.password_confirmation"
                            type="password" class="block w-full rounded-xl border-slate-300 bg-white py-3 pl-10 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" autocomplete="new-password" placeholder="••••••••" />
                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <PrimaryButton :disabled="form.processing" class="inline-flex min-w-[168px] items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-300">
                    <svg v-if="form.processing" class="h-4 w-4 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <span>{{ form.processing ? 'Guardando...' : 'Guardar cambios' }}</span>
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
