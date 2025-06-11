<template>
    <Head :title="` | ${page.component}`"/>
    <h1 class="title">Reservations</h1>
    <div class="flex flex-col justify-between gap-4 py-4">
        <div v-for="reservation in reservations" :key="reservation.id" class="border p-4 rounded">
            <p><strong>#</strong> {{ reservation.id }}</p>
            <p><strong>User ID:</strong> {{ reservation.user_id }}</p>
            <p><strong>Table ID:</strong> {{ reservation.table_id }}</p>
            <p><strong>Duration:</strong> {{ reservation.duration + "Hours" }}</p>
            <p><strong>Created At:</strong> {{ reservation.created_at }}</p>
            <p><strong>Peoples</strong> {{ reservation.number_of_people }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import {usePage} from '@inertiajs/vue3';
import {ref} from "vue";

const page = usePage();

const reservations = ref(page.props.reservations || [] as any);

</script>

<style scoped>

</style>
