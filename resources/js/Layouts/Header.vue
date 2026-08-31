<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="flex items-center justify-between border-b border-slate-200 bg-white/90 px-6 py-3 shadow-sm backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/90">
        <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu"
            class="rounded-lg p-2 text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 lg:hidden dark:text-slate-200 dark:hover:bg-slate-700">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <div class="hidden flex-1 lg:block"></div>

        <div class="flex items-center gap-3">
            <dropdown>
                <template #trigger>
                    <button class="flex items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 text-left transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 font-semibold text-sm text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200">
                            {{ $page.props.auth.user.username.charAt(0).toUpperCase() }}
                        </div>

                        <div class="hidden sm:block">
                            <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ $page.props.auth.user.username }}
                            </p>
                            <p class="truncate text-xs uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                                {{ ($page.props.auth.user.roles && $page.props.auth.user.roles.length) ? $page.props.auth.user.roles[0] : 'Sin rol' }}
                            </p>
                        </div>

                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-500 sm:block dark:text-slate-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </template>

                <template #content>
                    <div class="flex min-w-[180px] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg dark:border-slate-700 dark:bg-slate-800">
                        <Link :href="route('profile.edit')"
                            class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a4 4 0 100 8 4 4 0 000-8zm-6 14a6 6 0 0112 0v1H4v-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Perfil
                        </Link>

                        <div class="my-1 border-t border-slate-200 dark:border-slate-700"></div>

                        <Link :href="route('logout')" method="post"
                            class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-red-600 hover:text-white dark:text-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            Cerrar sesión
                        </Link>
                    </div>
                </template>
            </dropdown>
        </div>
    </header>
</template>
