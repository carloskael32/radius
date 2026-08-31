<template>
    <button :type="type" class="inline-flex items-center justify-center rounded-xl border border-yellow-300 bg-yellow-400 px-4 py-2.5 text-center text-sm font-medium leading-5 text-yellow-950 shadow-sm transition duration-200 hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400/40 active:bg-yellow-500 dark:border-yellow-500 dark:bg-yellow-500 dark:text-yellow-950 dark:hover:bg-yellow-400">
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
  