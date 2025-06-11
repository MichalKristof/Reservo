<template>
    <Head :title="` | ${$page.component}`"/>
    <h1 v-if="!createdReservation" class="title">Create Reservation</h1>
    <h1 v-else class="title">Reservation Created Successfully</h1>

    <div v-if="!createdReservation" class="flex flex-col justify-between gap-4 py-4">
        <ReservationForm @reservationSuccess="reservationSuccess"/>
    </div>

    <div v-else class="flex flex-col justify-between gap-4 py-4">
        <p class="text-green-600">Reservation ID: {{ createdReservation.id }}</p>
        <p class="text-green-600">Reservation User: {{ createdReservation.user_id }}</p>
        <p class="text-green-600">Reservation Table: {{ createdReservation.table_id }}</p>
        <p class="text-green-600">Reservation Duration: {{ createdReservation.duration }}</p>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import ReservationForm from './ReservationCreateForm.vue';

const createdReservation = ref(null as any);

const reservationSuccess = (reservation: any) => {
    createdReservation.value = reservation;
};
</script>
