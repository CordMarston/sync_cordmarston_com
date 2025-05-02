<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    id: {
        type: String,
        required: true, // Optional: Set to false if not always required
    },
    placeholder: {
        type: String,
        default: "Project Notes", // You can set a default placeholder if needed
    },
    rows: {
        type: Number,
        default: 5, // You can set a default number of rows
    },
});

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute("autofocus")) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
        v-model="model"
        ref="textarea"
        :id="id"
        :placeholder="placeholder"
        :rows="rows"
    />
</template>
