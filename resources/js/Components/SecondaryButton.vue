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
        class="rounded-md bg-gray-200 px-4 py-2 text-center text-md text-black hover:bg-gray-300 active:bg-gray-500">
        <slot />
    </button>
</template>
