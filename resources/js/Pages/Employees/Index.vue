<template>
    <Head title="Employees" />

    <AuthenticatedLayout>
        <template #header> Employees </template>

        <div class="bg-white grid v-screen place-items-center">
            <div class="mt-3 mb-3 flex">
                <PrimaryButton @click="($event) => openModal(1)">
                    <i class="fa-solid fa-plus-circle"></i>Add
                </PrimaryButton>
            </div>
        </div>
        <div class="bg-white grid v-screen place-items-center overflow-x-auto">
            <table class="table-auto border border-gray-400">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-2">#</th>
                        <th class="px-2 py-2">NAME</th>
                        <th class="px-2 py-2">EMAIL</th>
                        <th class="px-2 py-2">PHONE</th>
                        <th class="px-2 py-2">DEPARTMENT</th>
                        <th class="px-2 py-2"></th>
                        <th class="px-2 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(emp, i) in employees" key="emp.id">
                        <td class="border border-gray-400 px-2 py-2">
                            {{ i + 1 }}
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            {{ emp.name }}
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            {{ emp.email }}
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            {{ emp.phone }}
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            {{ emp.department }}
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            <WarningButton
                                @click="
                                    ($event) =>
                                        openModal(
                                            2,
                                            emp.name,
                                            emp.email,
                                            emp.phone,
                                            emp.department_id,
                                            emp.id
                                        )
                                "
                            >
                                <i class="fa-solid fa-edit"></i>
                            </WarningButton>
                            <Link
                                :href="route('employees.edit', emp.id)"
                                :class="'px-2 py-2 bg-yellow-800 text-white border rounded-md font-semibold text-xs'"
                                ><i class="fa-solid fa-edit"></i>
                            </Link>
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            <DangerButton
                                @click="
                                    ($event) => deleteEmployee(emp.id, emp.name)
                                "
                            >
                                <i class="fa-solid fa-trash"></i>
                            </DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div
                class="bg-white grid v-screen place-items-center overflow-x-auto"
            >
                <vueTailwindPaginationUmd
                    :current="employees.currentPage"
                    :total="employees.total"
                    :per-page="employees.perPage"
                    @page-changed="($event) => onPageClick($event)"
                >
                </vueTailwindPaginationUmd>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
            <div class="p-3">
                <InputLabel for="name" value="Name:"></InputLabel>
                <TextInput
                    id="name"
                    ref="nameInput"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="name"
                ></TextInput>
                <InputError
                    :message="form.errors.name"
                    class="mt-2"
                ></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="email" value="Email:"></InputLabel>
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="email"
                ></TextInput>
                <InputError
                    :message="form.errors.email"
                    class="mt-2"
                ></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="phone" value="Phone:"></InputLabel>
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-3/4"
                    placeholder="phone"
                ></TextInput>
                <InputError
                    :message="form.errors.phone"
                    class="mt-2"
                ></InputError>
            </div>
            <div class="p-3">
                <InputLabel
                    for="department_id"
                    value="Department:"
                ></InputLabel>
                <SelectInput
                    id="department_id"
                    v-model="form.department_id"
                    type="text"
                    :options="departments"
                    class="mt-1 block w-3/4"
                ></SelectInput>
                <InputError
                    :message="form.errors.department_id"
                    class="mt-2"
                ></InputError>
            </div>
            <div class="flex space-x-reverse">
                <div class="p-3">
                    <PrimaryButton @click="save"
                        ><i class="fa-solid fa-save"></i> Save</PrimaryButton
                    >
                </div>
                <div class="p-3 place-self-end">
                    <SecondaryButton @click="closeModal"
                        ><i class="ml-3"></i> Cancel</SecondaryButton
                    >
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import WarningButton from "@/Components/WarningButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { nextTick, ref } from "vue";
import Swal from "sweetalert2";
import vueTailwindPaginationUmd from "@ocrv/vue-tailwind-pagination";

const nameInput = ref(null);
const modal = ref(false);
const title = ref("");
const operation = ref(1);
const id = ref("");

const props = defineProps({
    departments: { type: Object },
    employees: { type: Object },
});

const form = useForm({
    name: "",
    email: "",
    phone: "",
    department_id: "",
});

const formPage = useForm({});
const onPageClick = (event) => {
    formPage.get(route("employees.index", { page: event }));
};
const openModal = (op, name, email, phone, department, employee) => {
    modal.value = true;
    nextTick(() => {
        nameInput.value.focus
    });
    operation.value = op;
    id.value = employee;
    if (op === 1) {
        title.value = "Create employee";
    } else {
        title.value = "Employee";
        form.name = name;
        form.email = email;
        form.phone = phone;
        form.department_id = department;
    }
};
const closeModal = () => {
    modal.value = false;
};
const save = () => {
    if (operation.value === 1) {
        form.post(route("employees.store"), {
            onSuccess: () => {
                ok("Employee created");
            },
        });
    } else {
        form.put(route("employees.update", id.value), {
            onSuccess: () => {
                ok("Employee updated");
            },
        });
    }
};
const ok = (msj) => {
    form.reset();
    closeModal();
    Swal.fire({
        title: msj,
        icon: "success",
    });
};

const deleteEmployee = (id, name) => {
    const alerta = Swal.mixin({
        buttonsStyling: true,
    });
    alerta
        .fire({
            title: "Are you sure delete " + name + " ?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-check"></i>Yes, delete',
            cancelButtonText: '<i class="fa-solid fa-ban"></i>Cancel',
        })
        .then((result) => {
            if (result.isConfirmed) {
                form.delete(route("employees.destroy", id), {
                    onSuccess: () => {
                        ok("Employee deleted");
                    },
                });
            }
        });
};
</script>
