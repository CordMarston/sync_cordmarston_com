<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import CustomButton from "@/Components/CustomButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from 'axios';
import Modal from "@/Components/Modal.vue";
import ProjectsNav from "@/Components/Projects/ProjectsNav.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const { project } = defineProps({
  project: Object
})

const form = useForm({
    name: "",
    url: "",
    username: "",
    password: "",
    port: "",
    dev_url: "",
    dev_username: "",
    dev_password: "",
    dev_port: "",
});

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
        <Modal :show="true">
            <template #header>
                Add Connection
            </template>
            <form @submit.prevent="submit" class="space-y-8">
                <div>
                    <div class="mb-4">
                        <InputLabel for="url" value="URL" />
                        <TextInput id="url" v-model="form.url" type="url" class="mt-1 block w-full" />
                        <InputError :message="form.errors.url" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel for="username" value="Live Username" />
                            <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.username" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Live Password" />
                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="port" value="Live Port" />
                            <TextInput id="port" v-model="form.port" type="number" class="mt-1 block w-full" />
                            <InputError :message="form.errors.port" class="mt-2" />
                        </div>
                    </div>
                </div>
            </form>
            <template #footer>
                <div class="flex justify-end">
                    <CustomButton @click="show = false">Cancel</CustomButton>
                    <PrimaryButton class="ml-2">Add</PrimaryButton>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
