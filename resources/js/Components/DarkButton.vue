<template>
    <button :type="type" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-slate-700 px-3 py-1.5 text-center text-sm font-medium leading-5 text-white transition duration-200 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400/30 active:bg-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700">
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
  