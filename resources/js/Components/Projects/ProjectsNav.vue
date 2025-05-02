<script setup>
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'
const { projectId } = defineProps({
  projectId: Number
})
</script>
<template>
    <div class="w-full flex justify-center">
        <nav class="flex pb-6 space-x-1">
            <Link
                :href="route('projects.edit', projectId)"
                class="px-3 py-2 rounded hover:bg-indigo-200 hover:text-indigo-950"
                :class="{
                    'bg-indigo-600 text-white dark:bg-gray-800':
                        $page.url.endsWith('/edit'),
                }"
            >
                Details
            </Link>
            <Link
                :href="route('projects.connections', projectId)"
                class="px-3 py-2 rounded hover:bg-indigo-200 hover:text-indigo-950"
                :class="{
                    'bg-indigo-600 text-white dark:bg-gray-800':
                        $page.url.endsWith('/connections'),
                }"
            >
                Connections
            </Link>
            <Link
                :href="route('projects.tables', projectId)"
                class="px-3 py-2 rounded hover:bg-indigo-200 hover:text-indigo-950"
                :class="{
                    'bg-indigo-600 text-white dark:bg-gray-800':
                        $page.url.endsWith('/tables'),
                }"
            >
                Tables
            </Link>
            <Link
                :href="route('projects.edit', projectId)"
                class="px-3 py-2 rounded hover:bg-indigo-200 hover:text-indigo-950"
                :class="{
                    'bg-indigo-600 text-white dark:bg-gray-800':
                        $page.url.endsWith('/status'),
                }"
            >
                Status
            </Link>
        </nav>
    </div>
</template>
