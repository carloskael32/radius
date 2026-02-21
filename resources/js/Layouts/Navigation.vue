<script setup>
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

let showingTwoLevelMenu = ref(false)
</script>

<template>
    <!-- Mobile Overlay -->
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-slate-800 to-slate-900 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-700">

        <!-- Logo Section -->
        <div class="flex justify-center items-center pt-6 pb-8 border-b border-slate-700">
            <div class="flex items-center gap-3">
                <svg class="w-10 h-10" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M364.61 390.213C304.625 450.196 207.37 450.196 147.386 390.213C117.394 360.22 102.398 320.911 102.398 281.6C102.398 242.291 117.394 202.981 147.386 172.989C147.386 230.4 153.6 281.6 230.4 307.2C230.4 256 256 102.4 294.4 76.7999C320 128 334.618 142.997 364.608 172.989C394.601 202.981 409.597 242.291 409.597 281.6C409.597 320.911 394.601 360.22 364.61 390.213Z"
                        fill="#3B82F6" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M201.694 387.105C231.686 417.098 280.312 417.098 310.305 387.105C325.301 372.109 332.8 352.456 332.8 332.8C332.8 313.144 325.301 293.491 310.305 278.495C295.309 263.498 288 256 275.2 230.4C256 243.2 243.201 320 243.201 345.6C201.694 345.6 179.2 332.8 179.2 332.8C179.2 352.456 186.698 372.109 201.694 387.105Z"
                        fill="white" />
                </svg>
                <span class="text-xl font-bold text-white">Dashboard</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-4 px-4">
            <!-- Main Navigation Section -->
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2 mb-3">Main Menu</p>

                <nav-link :href="route('dashboard')" :active="route().current('dashboard')" class="mb-2">
                    <template #icon>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </template>
                    Dashboard
                </nav-link>

                <nav-link :href="route('users.index')" :active="route().current('users.index')" class="mb-2">
                    <template #icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </template>
                    Users
                </nav-link>
            </div>

            <!-- Network Section -->
            <div class="mt-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2 mb-3">Network</p>

                <nav-link :href="route('nas.index')" :active="route().current('nas.index')" class="mb-2">
                    <template #icon>
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                            </path>
                        </svg>
                    </template>
                    Mikrotiks
                </nav-link>
            </div>

            <!-- PPPoE Section -->
            <div class="mt-6">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2 mb-3">PPPoE Management</p>

                <nav-link :href="route('radcheck.index')" :active="route().current('radcheck.index')" class="mb-2">
                    <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </template>
                    Usuario PPPoE
                </nav-link>

                <nav-link :href="route('client.index')" :active="route().current('client.*')" class="mb-2">
                    <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </template>
                    Clientes PPPoE
                </nav-link>

                <nav-link :href="route('rgroup.index')" :active="route().current('rgroup.index')" class="mb-2">
                    <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                    </template>
                    Grupos/Perfiles
                </nav-link>

                <nav-link :href="route('radacct.index')" :active="route().current('radacct.index')" class="mb-2">
                    <template #icon>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </template>
                    Sesiones de usuario PPPoE
                </nav-link>
            </div>

            <!-- Collapsible Section -->
            <div class="mt-6">
                <button @click="showingTwoLevelMenu = !showingTwoLevelMenu"
                    class="w-full flex items-center gap-3 py-2 px-3 text-slate-200 rounded-lg transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">More Options</span>
                    <svg :class="showingTwoLevelMenu ? 'rotate-180' : ''" class="w-4 h-4 ml-auto transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>

                <transition enter-to-class="transition-all duration-300 ease-in-out opacity-100 max-h-96" enter-from-class="max-h-0 opacity-0 overflow-hidden"
                    leave-from-class="opacity-100 max-h-96" leave-to-class="max-h-0 opacity-0 overflow-hidden" class="overflow-hidden">
                    <ul v-show="showingTwoLevelMenu" class="mt-2 ml-3 space-y-2 text-sm font-medium text-slate-300 border-l-2 border-slate-600 pl-4">
                        <li class="py-1">
                            <Link class="hover:text-gray-300 transition" :href="route('dashboard')">Child menu</Link>
                        </li>
                    </ul>
                </transition>
            </div>
        </nav>
    </div>
</template>

<style scoped>
:deep(.nav-link) {
    @apply flex items-center gap-3 py-2.5 px-3 text-slate-200 rounded-lg transition-all;
}

:deep(.nav-link:hover) {
    @apply bg-slate-700 text-white;
}

:deep(.nav-link.active) {
    @apply bg-gray-600 text-white font-medium shadow-md;
}
</style>
