<template>
    <Head :title="` | ${page.component}`"/>
    <h1 class="title">Reservations</h1>
    <div class="flex flex-col justify-between gap-4 py-4">
        <div v-for="reservation in reservations" :key="reservation.id"
             class="border border-slate-300 shadow-md p-3 rounded-md">
            <p><strong>#</strong> {{ reservation.table.name }}</p>
            <p><strong>Reserved:</strong> {{ reservation.reserved_at }}</p>
            <p><strong>Duration:</strong> {{ reservation.duration + "Hours" }}</p>
            <p><strong>Number Of People</strong> {{ reservation.number_of_people }}</p>
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
