<template>
    <button :type="type" class="rounded-lg border border-transparent bg-yellow-300 px-4 py-2 text-center text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-yellow-200 focus:outline-none focus:ring active:bg-yellow-600">
      <slot/>
    </button>
  </template>
  
  <script setup>
  defineProps({
      type: {
          type: String,
          default: 'submit',
      },
  });
  </script>
  