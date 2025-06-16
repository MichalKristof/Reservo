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

        <div class="grid grid-cols-1 gap-4">
            <div v-for="table in tables" :key="table.id" class="border border-slate-300 p-4 rounded-md shadow-xl">
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
                                'bg-green-400': !isHourReserved(hour, table.reservations),
                                'bg-orange-400 text-white': isHourReserved(hour, table.reservations)
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
                                <span><strong>{{ res.reserved_at }}</strong></span>
                                <span>Duration: <strong>{{ res.duration }}h</strong></span>
                                <span>Number of people: <strong>{{ res.number_of_people }}</strong></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted, computed} from 'vue';
import DatePicker from "@/Components/DatePicker.vue";
import {router, usePage} from '@inertiajs/vue3';

const page = usePage();
const date = ref();
const tables = ref({});
const config = ref();

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

const loadData = (selectedDate: string | Date) => {
    router.get(route('tables.show'), {date: selectedDate}, {
        preserveScroll: true,
        preserveState: false,
    });
};
const fetchInertiaProps = () => {
    tables.value = page.props.tables || {};
};

onMounted(() => {
    if (page.props.restaurant) {
        config.value = page.props.restaurant;
    }
    if (page.props.selectedDate) {
        date.value = new Date(page.props.selectedDate);
    }
    fetchInertiaProps();
});
</script>
