<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";

const form = useForm({
    name: "",
    live_url: "",
    live_username: "",
    live_password: "",
    live_port: "",
    dev_url: "",
    dev_username: "",
    dev_password: "",
    dev_port: "",
    notes: ""
});

function submit() {
    form.post(route("projects.store"));
}
</script>

<template>
    <Head title="New Project" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    New Project
                </h2>
            </div>
        </template>

        

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Project Details -->
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
                                    Create Project
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
