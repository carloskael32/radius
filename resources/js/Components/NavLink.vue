<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() => props.active
    ? 'flex items-center gap-3 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm dark:border-indigo-500/50 dark:bg-indigo-500/10 dark:text-indigo-200'
    : 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot name="icon" />
        <span class="truncate">
            <slot />
        </span>
    </Link>
</template>
