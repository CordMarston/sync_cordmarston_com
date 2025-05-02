<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import LinkButton from '@/Components/LinkButton.vue';

defineProps({
  projects: {
    type: Array,
    required: true,
  },
})

</script>

<template>
    <Head title="Projects" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Projects</h2>
                <LinkButton href="projects/create">New Project</LinkButton>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2">ID</th>
                                    <th class="border px-4 py-2">Name</th>
                                    <th class="border px-4 py-2 w-[1px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="project in projects" :key="project.id">
                                    <td class="border px-4 py-2">{{ project.id }}</td>
                                    <td class="border px-4 py-2">{{ project.name }}</td>
                                    <td class="border px-4 py-2 flex space-x-2">
                                        <LinkButton :href="'projects/' + project.id + '/edit'">Manage</LinkButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
