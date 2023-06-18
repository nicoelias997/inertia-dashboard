<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header> Reports </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <Bar :data="chartData" :options="chartOptions"></Bar> 
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import SelectInput from '@/Components/SelectInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

    


const props = defineProps({
    departments: { type: Object },
    employees: { type: Object },

});

</script>
