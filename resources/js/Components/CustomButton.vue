<script setup>
import { computed } from 'vue';
const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'secondary',
    }
});

const classes = computed(() => {
    let defaults = "inline-flex items-center px-4 py-2 disabled:opacity-25 transition ease-in-out duration-150 ";
    if (props.variant == 'success') {
        defaults += "bg-green-50 dark:bg-green-700 border border-green-300 dark:border-green-500 rounded-md font-semibold text-xs text-green-700 dark:text-green-300 uppercase tracking-widest shadow-sm hover:bg-green-100 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-green-800";
    } else if(props.variant == 'danger') {
       defaults += "bg-red-50 dark:bg-red-700 border border-red-300 dark:border-red-500 rounded-md font-semibold text-xs text-red-700 dark:text-red-300 uppercase tracking-widest shadow-sm hover:bg-red-100 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-red-800";
    } else if(props.variant == 'warning') {
        defaults += "bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500 text-white font-semibold rounded-md shadow-sm";
    } else {
        defaults += "bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
    }
    return defaults;
});
</script>

<template>
    <button
        :type="type"
        :class="classes"
    >
        <slot />
    </button>
</template>
