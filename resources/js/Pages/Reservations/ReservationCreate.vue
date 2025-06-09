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
        />


        <SelectBox
            name="Reservation Time"
            v-model="form.time"
            :options="timeOptions"
            :message="form.errors.time"
            :disabled="form.processing"
        />

        <SelectBox
            name="Duration"
            v-model="form.duration"
            type="number"
            :options="durationOptions"
            :message="form.errors.duration"
            :disabled="form.processing"
        />

        <SelectBox
            name="Number of People"
            v-model="form.number_of_people"
            type="number"
            :options="numberOfPeopleOptions"
            :message="form.errors.number_of_people"
            :disabled="form.processing"
        />
        <div class="w-full mb-6 flex items-end justify-end">
            <button
                class="primary-btn"
                :disabled="form.processing"
                @click="fetchAvailableTables">Search
            </button>
        </div>
    </div>

    <div class="flex flex-col gap-4 mt-4">
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

        <div v-else-if="errorMessage" class="mt-6 text-red-600">
            {{ errorMessage }}
        </div>

        <div v-else-if="availableTables.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
    </div>
</template>

<script setup lang="ts">
import {ref, computed} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import DatePicker from "../../Components/DatePicker.vue";
import SelectBox from "../../Components/SelectBox.vue";
import axios from 'axios';
import type {AxiosError} from 'axios';

interface ResponseData {
    data?: {
        availableTables?: any[];
    };
    messages?: string[];
}

const form = useForm({
    reserved_at: null,
    time: null,
    duration: null,
    number_of_people: null,
})

const searched = ref(false)
const availableTables = ref([]);
const errorMessage = ref('');

const page = usePage();

const timeOptions = computed(() =>
    page.props.restaurant.times.map(time => ({value: time, label: time}))
);

const durationOptions = computed(() =>
    page.props.restaurant.durations.map(duration => ({
        value: duration,
        label: `${duration} ${duration === 1 ? 'Hour' : 'Hours'}`
    }))
);

const numberOfPeopleOptions = computed(() =>
    page.props.restaurant.number_of_people.map(people => ({
        value: people,
        label: `${people} ${people === 1 ? 'Person' : 'People'}`
    }))
);

const fetchAvailableTables = async () => {
    form.processing = true;
    form.errors = {};
    errorMessage.value = '';
    availableTables.value = [];

    try {
        const response = await axios.post(route('reservations.availableTables'), {
            reserved_at: form.reserved_at,
            time: form.time,
            duration: form.duration,
            number_of_people: form.number_of_people,
        });

        const res = response.data as ResponseData;

        availableTables.value = res.data?.availableTables || [];
        errorMessage.value = res.messages?.join(' ') || '';

    } catch (error) {
        const axiosError = error as AxiosError;

        if (axiosError.response?.status === 422) {
            form.errors = axiosError.response.data.errors;
        } else {
            errorMessage.value = 'An unexpected error occurred.';
        }
        availableTables.value = [];
    } finally {
        searched.value = true;
        form.processing = false;
    }
};

</script>
