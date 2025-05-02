<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";
import CustomButton from "@/Components/CustomButton.vue";
// import axios from 'axios';
import ProjectsNav from "@/Components/Projects/ProjectsNav.vue";
// import { ref } from 'vue';

const { project } = defineProps({
  project: Object
})

// const dev_success = ref(null);
// const live_success = ref(null);

const form = useForm({
  name: project.name || "",
  notes: project.notes || ""
})

function submit() {
    form.put(route('projects.update', project.id));
}

// function testConnection(type) {
//     if(type == 'dev') {
//         dev_success.value = null;
//     } else {
//         live_success.value = null;
//     }
//     // Reset the success state before testing the connection
//     axios.post(route('projects.test', { id: project.id }), {
//         ...form, // spread the form data
//         type     // add the connection type ('dev' or 'live')
//     })
//     .then(response => {
//         if(type == 'dev') {
//             dev_success.value = true;
//         } else {
//             live_success.value = true;
//         }
//         // message.value = response.data.message;
//     })
//     .catch(error => {
//         if(type == 'dev') {
//             dev_success.value = false;
//         } else {
//             live_success.value = false;
//         }
//         // message.value = error.response?.data?.message || 'Unknown error';
//     });
// }
</script>

<template>
    <Head title="Edit Project" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Project {{ project.name }}
                </h2>
            </div>
        </template>



        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ProjectsNav :projectId="project.id" />
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-8">
                            <div>
                                <InputLabel for="name" value="Project Name" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autofocus />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel for="notes" value="Project Notes" />
                                <TextArea id="notes" v-model="form.notes" class="mt-1 block w-full"/>
                                <InputError :message="form.errors.notes" class="mt-2" />
                            </div>
                            <!-- Submit Button -->
                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update Project
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
