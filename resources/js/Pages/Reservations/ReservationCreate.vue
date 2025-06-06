<template>
    <Head :title="` | ${$page.component}`"/>
    <h1 class="title">Create Reservation</h1>

    <div class="flex flex-row justify-between gap-4 border-b-3">
        <DatePicker
            name="Reservation Date"
            v-model="form.reserved_at"
            type="date"
            :message="form.errors.reserved_at"
            :disabled="form.processing"
            @update:modelValue="fetchAvailableTables"
        />


        <SelectBox
            name="Reservation Time"
            v-model="form.time"
            :options="[
                { value: '17:00', label: '17:00' },
                { value: '18:00', label: '18:00' },
                { value: '19:00', label: '19:00' },
                { value: '20:00', label: '20:00' },
                { value: '21:00', label: '21:00' },
            ]"
            :message="form.errors.time"
            :disabled="form.processing"
            @change="fetchAvailableTables"
        />

        <SelectBox
            name="Duration"
            v-model="form.duration"
            type="number"
            :options="[
                { value: 1, label: '1 Hour' },
                { value: 2, label: '2 Hours' },
                { value: 3, label: '3 Hours' },
                { value: 4, label: '4 Hours' },
            ]"
            :message="form.errors.duration"
            :disabled="form.processing"
            @change="fetchAvailableTables"
        />

        <SelectBox
            name="Number of People"
            v-model="form.number_of_people"
            type="number"
            :options="[
                { value: 1, label: '1 Person' },
                { value: 2, label: '2 People' },
                { value: 3, label: '3 People' },
                { value: 4, label: '4 People' },
                { value: 5, label: '5 People' },
                { value: 6, label: '6 People' },
            ]"
            :message="form.errors.number_of_people"
            :disabled="form.processing"
            @change="fetchAvailableTables"
        />
    </div>

    <div class="flex flex-col gap-4 mt-4">
        <div v-if="availableTables.length > 0 && !form.processing" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div
                v-for="table in availableTables"
                :key="table.id"
                class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300"
            >
                <h3 class="text-xl font-semibold mb-2">{{ table.name }}</h3>
                <p class="text-gray-600">Seats: <span class="font-medium">{{ table.seats }}</span></p>
                <p class="text-green-600 font-semibold mt-4">Available</p>
            </div>
        </div>

        <div v-if="form.processing" class="flex justify-center mt-6">
            <svg class="animate-spin h-8 w-8 text-[#003366]" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2.93 6.364A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3.93-1.574z"
                ></path>
            </svg>
        </div>


        <div v-else-if="searched && availableTables.length === 0" class="mt-6 text-red-600">
            No available tables for the selected time and number of people.
        </div>

        <div v-else-if="errorMessage" class="mt-6 text-red-600">
            No available tables for the selected time and number of people.
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import DatePicker from "../../Components/DatePicker.vue";
import SelectBox from "../../Components/SelectBox.vue";
import {route} from '../../../../vendor/tightenco/ziggy/src/js/index.js';
import axios from 'axios';

const form = useForm({
    reserved_at: null,
    time: null,
    duration: null,
    number_of_people: null,
})

const searched = ref(false)
const availableTables = ref([]);
const errorMessage = ref('');

const fetchAvailableTables = async () => {
    form.processing = true;
    form.errors = {};
    errorMessage.value = '';

    try {
        const response = await axios.post(route('reservations.availableTables'), {
            reserved_at: form.reserved_at,
            time: form.time,
            duration: form.duration,
            number_of_people: form.number_of_people,
        });

        searched.value = true;
        availableTables.value = response.data.availableTables || [];
    } catch (error) {
        if (error.response && error.response.data) {
            errorMessage.value = error.response.data.message || 'An error occurred while fetching available tables.';
        }
        form.errors = error.response.data.errors;
        availableTables.value = [];
        form.processing = false;
    } finally {
        searched.value = true;
        form.processing = false;
    }

    form.processing = false;
};
</script>
