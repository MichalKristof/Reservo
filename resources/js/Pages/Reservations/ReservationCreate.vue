<template>
    <Head :title="` | ${$page.component}`"/>
    <h1 class="title">Create Reservation</h1>

    <div class="flex flex-col justify-between gap-4 py-4 border-b-2">
        <ReservationForm/>
    </div>
    
    <div class="flex flex-col gap-4 mt-4">
        <div class="mt-6 text-red-600">
            {{ errorMessage }}
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
import {ref} from 'vue';
import ReservationForm from './ReservationCreateForm.vue';
import axios from 'axios';
import type {AxiosError} from 'axios';

interface ResponseData {
    data?: {
        availableTables?: any[];
    };
    messages?: string[];
}

const searched = ref(false)
const availableTables = ref([]);
const errorMessage = ref('');


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
