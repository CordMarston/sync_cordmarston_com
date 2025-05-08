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
import { ref } from "vue";
import LinkButton from '@/Components/LinkButton.vue';

const { project, connections } = defineProps({
  project: Object,
  connections: Array,
})

const form = useForm({
    name: "",
    host: "",
    username: "",
    password: "",
    port: "",
    project_id: project.id,
});

const showModal = ref(false);
const connectionPassed = ref(null);

const toggleModal = (val) => {
    showModal.value = val;
};

const testConnection = () => {
    connectionPassed.value = null; // reset the connection status
    form.clearErrors(); // optional: clear previous errors
    console.log('Testing connection...');
    axios.post(route('sync.connection.test'), form)
    .then(response => {
        connectionPassed.value = true;
    })
    .catch(error => {
        connectionPassed.value = false;
        if (error.response && error.response.status === 422) {
            form.errors = error.response.data.errors;
        } else {
            console.error('Server error:', error);
        }
    });
}

const addConnection = () => {
    form.clearErrors(); // optional: clear previous errors
    console.log('Adding connection...');
    axios.post(route('connections.store'), form)
    .then(response => {
        // Handle success, e.g., redirect or show a success message
        console.log('Connection added successfully:', response.data);
        toggleModal(false);
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            form.errors = error.response.data.errors;
        } else {
            console.error('Server error:', error);
        }
    });
}

const formatBytes = (bytes, precision = 2) => {
  const units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

  if (bytes === 0 || isNaN(bytes) || bytes === null) {
    return '0 Bytes';
  }

  const base = Math.floor(Math.log(bytes) / Math.log(1024));
  const formatted = (bytes / Math.pow(1024, base)).toFixed(precision);

  return `${formatted} ${units[base]}`;
}

</script>

<template>
    <Head :title="`${project.name} Tables`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Project {{ project.name }} Connections
                </h2>
                <PrimaryButton @click="toggleModal(true)">Add Connection</PrimaryButton>
            </div>
        </template>



        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ProjectsNav :projectId="project.id" />
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2 w-[1px]">ID</th>
                                    <th class="border px-4 py-2">Name</th>
                                    <th class="border px-4 py-2">Databases</th>
                                    <th class="border px-4 py-2">Size</th>
                                    <th class="border px-4 py-2">Host</th>
                                    <th class="border px-4 py-2">Username</th>
                                    <th class="border px-4 py-2 w-[1px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="connection in connections" :key="connection.id">
                                    <td class="border px-4 py-2">{{ connection.id }}</td>
                                    <td class="border px-4 py-2">{{ connection.name }}</td>
                                    <td class="border px-4 py-2">{{ connection.databases_count }}</td>
                                    <td class="border px-4 py-2">{{ formatBytes(connection.databases_sum_size) }}</td>
                                    <td class="border px-4 py-2">{{ connection.host }}</td>
                                    <td class="border px-4 py-2">{{ connection.username }}</td>
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
        <Modal :show="showModal" @close="toggleModal(false)">
            <template #header>
                Add Connection
            </template>
            <form @submit.prevent="submit">
                <div>
                    <div class="mb-4">
                        <InputLabel for="url" value="Name" />
                        <TextInput id="url" v-model="form.name" type="url" class="mt-1 block w-full" />
                        <InputError :message="form.errors.name?.[0]" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <InputLabel for="url" value="Host" />
                        <TextInput id="url" v-model="form.host" type="url" class="mt-1 block w-full" />
                        <InputError :message="form.errors.host?.[0]" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel for="username" value="Username" />
                            <TextInput id="username" v-model="form.username" type="text" class="mt-1 block w-full" />
                            <InputError :message="form.errors.username?.[0]" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" />
                            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                            <InputError :message="form.errors.password?.[0]" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="port" value="Port" />
                            <TextInput id="port" v-model="form.port" type="number" class="mt-1 block w-full" />
                            <InputError :message="form.errors.port?.[0]" class="mt-2" />
                        </div>
                    </div>
                </div>
            </form>
            <template #footer>
                <div class="flex justify-between">
                    <div>
                        <p v-if="connectionPassed" class="text-green-500">Connection successful!</p>
                        <p v-else-if="connectionPassed == false" class="text-red-500">Connection failed!</p>
                    </div>
                    <div>
                        <CustomButton @click="toggleModal(false)">Cancel</CustomButton>
                        <PrimaryButton class="ml-2" @click="addConnection()" v-if="connectionPassed">Add</PrimaryButton>
                        <PrimaryButton class="ml-2" @click="testConnection()" v-else>Test</PrimaryButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
