<template>
    <Head title="Create Department" />

    <AuthenticatedLayout>
        <template #header> Create Department </template>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form
                    @submit.prevent="
                        ($event) => form.post(route('departments.store'))
                    "
                    class="mt-6 space-y-6 max-w-xl"
                >
                    <InputLabel for="name" value="Department"></InputLabel>
                    <TextInput
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        class="mt-1 block w-full"
                    ></TextInput>
                    <InputError
                        :message="form.errors.name"
                        class="mt-2"
                    ></InputError>
                    <PrimaryButton
                        ><i class="fa-solid fa-save"></i> Save</PrimaryButton
                    >
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    name: "",
});
</script>
