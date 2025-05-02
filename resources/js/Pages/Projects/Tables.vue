<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CustomButton from "@/Components/CustomButton.vue";
import axios from 'axios';
import ProjectsNav from "@/Components/Projects/ProjectsNav.vue";

const { project } = defineProps({
  project: Object
})


function loadTables() {
    console.log('Loading tables...');
    axios.post(route('sync.schema', { id: project.id }), {
        ...project
    })
    .then(response => {
       
    })
    .catch(error => {
        
    });
}
</script>

<template>
    <Head :title="`${project.name} Tables`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Project {{ project.name }} Tables
                </h2>
            </div>
        </template>



        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ProjectsNav :projectId="project.id" />
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <CustomButton @click="loadTables">Load Tables</CustomButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
