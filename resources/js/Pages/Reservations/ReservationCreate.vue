<template>
    <Head :title="` | ${$page.component}`"/>
    <h1 v-if="!createdReservation" class="title">Create Reservation</h1>
    <h1 v-else class="title">Thank you for reservation</h1>

    <div v-if="!createdReservation" class="flex flex-col justify-between gap-4 py-4">
        <ReservationForm @reservationSuccess="reservationSuccess"/>
    </div>

    <div v-else class="flex flex-col gap-6 py-6">
        <div class="bg-white rounded-xl shadow-xl p-6 border border-slate-200">
            <p class="text-lg text-slate-700 mb-4">
                We have successfully received your reservation. You can find the summary below:
            </p>

            <ul class="space-y-2 text-slate-800">
                <li><strong>Reservation ID: </strong>{{ createdReservation.id }}</li>
                <li><strong>Duration: </strong>{{ createdReservation.duration }} hour(s)</li>
                <li><strong>Number of People: </strong>{{ createdReservation.number_of_people }}</li>
            </ul>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <Link :href="route('reservations.index')" class="primary-btn sm:w-auto">
                Go to my Reservations
            </Link>
            <Link :href="route('reservations.create')" class="primary-btn sm:w-auto">
                Make Another Reservation
            </Link>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onBeforeUnmount, onMounted, ref} from 'vue';
import ReservationForm from './ReservationCreateForm.vue';

const createdReservation = ref(null as any);

const reservationSuccess = (reservation: any) => {
    createdReservation.value = reservation;
};

onMounted(() => {
    createdReservation.value = null;
});
</script>
