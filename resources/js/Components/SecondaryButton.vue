<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type"
        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-slate-100 px-4 py-2.5 text-center text-sm font-medium text-slate-700 shadow-sm transition duration-200 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-300 active:bg-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 dark:focus:ring-slate-600">
        <slot />
    </button>
</template>
