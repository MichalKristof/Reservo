<template>
    <Head :title="`| ${$page.component}`"/>
    <div class="p-4">
        <div class="flex flex-col gap-3 mb-6">
            <div class="flex items-center gap-2">
                <label for="restaurant" class="font-semibold text-xl">Opening hours:</label>
                <span class="text-gray-700">{{ config?.opening_time + "-" + config?.closing_time }}</span>
            </div>
        </div>


        <DatePicker
            v-model="date"
            @update:modelValue="onDateChange"
            :allowPastDates="true"
            name="Select Date"
        />

        <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-4 min-h-[300px]">
            <div v-if="!loading" v-for="table in tables" :key="table.id"
                 class="border border-slate-300 p-4 rounded-md shadow-xl">
                <div class="w-full flex justify-between text-lg fw-semibold">
                    <span>{{ table.name }}</span>
                    <span class="text-gray-500">Capacity: {{ table.seats }}</span>
                </div>
                <div class="flex flex-col mt-3">
                    <div class="text-base mb-1 text-gray-500">Occupancy</div>
                    <div class="flex gap-1">
                        <div
                            v-for="hour in hours"
                            :key="hour"
                            class="px-3 py-2.5 rounded-md text-center text-sm"
                            :class="{
                                'bg-slate-200': !isHourReserved(hour, table.reservations),
                                'bg-primary text-white': isHourReserved(hour, table.reservations)
                            }"
                            :title="`${hour}:00`"
                        >
                            {{ hour }}
                        </div>
                    </div>
                    <span class="text-base mb-1 text-gray-500  mt-3">Reservations {{ table.reservations.length }}</span>
                    <ul v-if="table.reservations.length > 0" class="flex flex-col gap-5 mt-3">
                        <li
                            v-for="res in table.reservations"
                            :key="res.id"
                            class="border border-slate-200 p-2 rounded-md shadow-sm bg-white flex items-center justify-between"
                        >
                            <div class="flex flex-col">
                                <p><strong>{{ res.reserved_at }}</strong></p>
                                <p><strong>Duration: </strong>{{ res.duration }} hour(s)</p>
                                <p><strong>Number of people: </strong>{{ res.number_of_people }}</p>
                                <p><strong>Contact person:</strong> {{ res.user_email }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                v-if="loading"
                class="absolute inset-0 flex items-center justify-center bg-transparent z-50"
            >
                <svg
                    class="animate-spin h-10 w-10 text-blue-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    ></path>
                </svg>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref, computed, onMounted, onBeforeUnmount} from 'vue';
import DatePicker from "@/Components/DatePicker.vue";
import {router, usePage} from '@inertiajs/vue3';

const page = usePage();
const date = ref();
const tables = ref(computed(() => page.props.tables));
const config = ref(computed(() => page.props.restaurant ?? {}));
const loading = ref(true);

const parseHour = (timeStr: string): number => parseInt(timeStr.split(':')[0]);

const openingHour = computed(() => parseHour(config.value?.opening_time ?? null));
const closingHour = computed(() => parseHour(config.value?.closing_time ?? null));

const hours = computed(() => {
    const result: number[] = [];
    for (let h = openingHour.value; h < closingHour.value; h++) {
        result.push(h);
    }
    return result;
});

const isHourReserved = (hour: number, reservations: any[] = []) => {
    return reservations.some(res => {
        const start = new Date(res.reserved_at).getHours();
        const end = start + res.duration;
        return hour >= start && hour < end;
    });
};

const onDateChange = (newDate: Date) => {
    date.value = newDate;
    loadData(newDate);
};

const loadData = (selectedDate: Date) => {
    loading.value = true;
    const formattedDate = selectedDate.toISOString().split('T')[0];
    router.get(route('tables.index'), {date: formattedDate}, {
        preserveScroll: true,
        preserveState: false,
    });
};

onMounted(() => {
    date.value = page.props.selectedDate ? new Date(page.props.selectedDate) : null;
    loading.value = false;
});

onBeforeUnmount(() => {
    date.value = null;
    loading.value = false;
});
</script>
