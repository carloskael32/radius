<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
</script>

<template>
    <header class="flex items-center justify-between border-b border-gray-300 bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-3 shadow-sm">
        <!-- Mobile Menu Button -->
        <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu"
            class="text-white focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-lg p-1 transition lg:hidden hover:bg-gray-600">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <!-- Spacer for centering on desktop -->
        <div class="hidden lg:block flex-1"></div>

        <!-- User Profile Section -->
        <div class="flex items-center gap-4">
            <!-- User Avatar with Dropdown -->
            <dropdown>
                <template #trigger>
                    <button class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <!-- User Avatar Circle -->
                        <div class="relative flex h-9 w-9 items-center justify-center rounded-full bg-white text-gray-700 font-semibold text-sm">
                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>

                        <!-- User Info -->
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-medium text-gray-100 truncate">
                                {{ $page.props.auth.user.name }}
                            </p>
                            <p class="text-xs text-gray-300 truncate">
                                {{ $page.props.auth.user.email || 'Administrator' }}
                            </p>
                        </div>

                        <!-- Dropdown Arrow -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 hidden sm:block" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </template>

                <template #content>
                    <dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V18a1 1 0 102 0V5.414l6.293 6.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Profile
                    </dropdown-link>

                    <div class="border-t border-gray-200"></div>

                    <dropdown-link class="text-gray-700 hover:bg-gray-50" :href="route('logout')" method="post" as="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                        Log out
                    </dropdown-link>
                </template>
            </dropdown>
        </div>
    </header>
</template>
