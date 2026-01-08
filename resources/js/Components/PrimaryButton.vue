<script setup>
defineProps({
	type: {
		type: String,
		default: 'submit',
	},
});
</script>

<template>
    <button :type="type" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-md text-white hover:bg-indigo-500 active:bg-indigo-700">
        <slot />
    </button>
</template>
