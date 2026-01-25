<template>
    <button :type="type" class="rounded-full border border-transparent bg-gray-600 px-3 py-1 text-center text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-gray-700 focus:outline-none focus:ring active:bg-gray-600">
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
  