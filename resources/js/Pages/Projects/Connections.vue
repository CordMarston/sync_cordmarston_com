<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CustomButton from "@/Components/CustomButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from 'axios';
import Modal from "@/Components/Modal.vue";
import ProjectsNav from "@/Components/Projects/ProjectsNav.vue";

const { project } = defineProps({
  project: Object
})


</script>

<template>
    <Head :title="`${project.name} Tables`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Project {{ project.name }} Connections
                </h2>
                <PrimaryButton>Add Connection</PrimaryButton>
            </div>
        </template>



        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ProjectsNav :projectId="project.id" />
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <CustomButton>Load Tables</CustomButton>
                    </div>
                </div>
            </div>
        </div>
        <Modal show="true">
            <template #header>
                Add Connection
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
