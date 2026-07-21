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
    ? 'flex items-center gap-3 rounded-xl border border-slate-600/70 bg-slate-700/90 px-3 py-2.5 text-sm font-semibold text-white shadow-sm'
    : 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-slate-700/80 hover:text-white'
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
