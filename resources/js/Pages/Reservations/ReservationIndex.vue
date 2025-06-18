<template>
    <Head :title="` | ${page.component}`"/>
    <h1 class="title">Reservations</h1>
    <div class="flex flex-col justify-between gap-4 py-4">
        <div v-if="reservations.length === 0"
             class="flex flex-col gap-5">
            <div class="border border-slate-300 shadow-md p-3 rounded-md">
                <p class="text-center">No reservations yet</p>
            </div>

            <div class="flex justify-center items-center mt-10">
                <Link :href="route('reservations.create')" as="button" type="button" class="primary-btn"
                      preserve-scroll>
                    Create Reservations
                </Link>
            </div>
        </div>
        <h2 v-if="upcomingReservations.length > 0" class="text-xl font-semibold mb-4">Active Reservations</h2>
        <div v-for="reservation in upcomingReservations" :key="reservation.id"
             class="border border-slate-300 shadow-md p-3 rounded-md">
            <p><strong>#</strong> {{ reservation.table.name }}</p>
            <p><strong>Reserved:</strong> {{ reservation.reserved_at }}</p>
            <p><strong>Duration:</strong> {{ reservation.duration + "Hours" }}</p>
            <p><strong>Number Of People</strong> {{ reservation.number_of_people }}</p>
        </div>

        <div v-if="pastReservations.length > 0" class="mt-10">
            <h2 class="text-xl font-semibold mb-4">Past Reservations</h2>
            <div class="grid grid-cols-2 gap-5">
                <div v-for="reservation in pastReservations" :key="reservation.id"
                     class="border border-slate-50 bg-slate-50 shadow-lg p-3 rounded-md mb-4">
                    <p><strong>{{ reservation.table.name }}</strong></p>
                    <p><strong>Reserved:</strong> {{ reservation.reserved_at }}</p>
                    <p><strong>Duration:</strong> {{ reservation.duration + "Hours" }}</p>
                    <p><strong>Number Of People</strong> {{ reservation.number_of_people }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {usePage} from '@inertiajs/vue3';
import {computed, ref} from "vue";

const page = usePage();

const reservations = ref(page.props.reservations || [] as any);

const now = new Date();

const upcomingReservations = computed(() =>
    reservations.value
        .filter(res => new Date(res.reserved_at) >= new Date(now.toDateString()))
        .sort((a, b) => new Date(a.reserved_at).getTime() - new Date(b.reserved_at).getTime())
);

const pastReservations = computed(() =>
    reservations.value
        .filter(res => new Date(res.reserved_at) < new Date(now.toDateString()))
        .sort((a, b) => new Date(b.reserved_at).getTime() - new Date(a.reserved_at).getTime())
);
</script>

<style scoped>

</style>
