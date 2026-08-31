<script setup>
defineProps({
	type: {
		type: String,
		default: 'submit',
	},
});
</script>

<template>
    <button :type="type" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-medium text-white shadow-sm transition duration-200 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 active:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
        <slot />
    </button>
</template>
