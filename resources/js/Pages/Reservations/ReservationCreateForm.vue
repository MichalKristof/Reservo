<template>
    <div class="relative">
        <div :class="{'blur-sm pointer-events-none': loading}">
            <component
                v-for="(stepItem, index) in formData"
                :key="index"
                :is="stepItem.component"
                v-model="form[stepItem.key]"
                :name="stepItem.name"
                :type="stepItem.type"
                :options="stepItem.options"
                :message="form.errors[stepItem.key]"
                :disabled="loading"
                class="mb-4"
            />

            <div v-if="!loading" class="flex justify-around gap-4">
                <button
                    class="primary-btn flex items-center justify-center"
                    @click="submitForm"
                    :disabled="loading"
                >
                    Reserve table
                </button>
            </div>
        </div>

        <div
            v-if="loading"
            class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 z-50"
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

</template>

<script setup lang="ts">
import {computed, ref, watch} from 'vue'
import {useForm, usePage} from '@inertiajs/vue3';
import DatePicker from "@/Components/DatePicker.vue";
import SelectBox from "@/Components/SelectBox.vue";
import axios from "axios";
import {debounce} from 'lodash';

type FormKeys = 'reserved_at' | 'time' | 'duration' | 'number_of_people';

type Option = {
    value: string | number;
    label: string;
};

const emit = defineEmits(['reservationSuccess']);

const page = usePage()
const loading = ref(false);

const form = useForm({
    reserved_at: null,
    time: null,
    duration: null,
    number_of_people: null,
});

const availableTimes = ref<Option[]>([]);
const availableDurations = ref<Option[]>([]);
const availablePeopleOptions = ref<Option[]>([]);

const formData = computed<
    Array<{
        key: FormKeys;
        component: any;
        name: string;
        type?: string;
        options?: any;
        required: boolean;
    }>
>(() => [
    {
        key: 'reserved_at',
        component: DatePicker,
        name: 'Reservation Date',
        type: 'date',
        options: null,
        required: true,
    },
    {
        key: 'time',
        component: SelectBox,
        name: 'Reservation Time',
        options: availableTimes.value,
        required: true,
    },
    {
        key: 'duration',
        component: SelectBox,
        name: 'Duration',
        options: availableDurations.value,
        type: 'number',
        required: true,
    },
    {
        key: 'number_of_people',
        component: SelectBox,
        name: 'Number of People',
        options: availablePeopleOptions.value,
        type: 'number',
        required: true,
    },
]);
const submitForm = () => {
    form.post(route('reservations.store'), {
        onSuccess: (page) => {
            emit('reservationSuccess', page.props.reservation);
            form.reset();
            availableTimes.value = [];
            availableDurations.value = [];
            availablePeopleOptions.value = [];
        },
        onError: (errors) => {
            console.log(errors);
        }
    });
}

const fetchAvailableTimes = debounce(async (newDate) => {
    if (!newDate) return;
    loading.value = true;

    try {
        const res = await axios.post(route('reservations.availableTimes'), {
            reserved_at: newDate,
        });

        if (!res.data.times) return;

        availableTimes.value = res.data.times.map(t => ({
            value: t,
            label: t,
        }));

        form.time = null;
        form.duration = null;
        form.number_of_people = null;
    } catch (error) {
        availableTimes.value = [];
    } finally {
        loading.value = false;
    }
}, 300);

const fetchAvailableDurations = debounce(async (time: string) => {
    if (!form.reserved_at || !time) return;
    loading.value = true;

    try {
        const res = await axios.post(route('reservations.availableDurations'), {
            reserved_at: form.reserved_at,
            time,
        });

        const durations = res.data.durations;

        if (Array.isArray(durations)) {
            availableDurations.value = durations.map(d => ({
                value: d,
                label: `${d} ${d === 1 ? 'Hour' : 'Hours'}`,
            }));
            form.duration = null;
            form.number_of_people = null;
        }
    } catch {
        availableDurations.value = [];
    } finally {
        loading.value = false;
    }
}, 300);


const fetchAvailablePeople = debounce(async (duration: number) => {
    if (!form.reserved_at || !form.time || !duration) return;
    loading.value = true;

    try {
        const res = await axios.post(route('reservations.availablePeople'), {
            reserved_at: form.reserved_at,
            time: form.time,
            duration,
        });

        const peopleCounts = res.data.available_people_counts;

        if (Array.isArray(peopleCounts)) {
            availablePeopleOptions.value = peopleCounts.map(p => ({
                value: p,
                label: `${p} ${p === 1 ? 'Person' : 'People'}`,
            }));
        }
    } catch {
        availablePeopleOptions.value = [];
    } finally {
        loading.value = false;
    }
}, 300);

watch(() => form.reserved_at, (newDate) => {
    if (!newDate) return;
    fetchAvailableTimes(newDate);
});

watch(() => form.time, (time) => {
    if (!time) return;
    fetchAvailableDurations(time);
});

watch(() => form.duration, (duration) => {
    if (!duration) return;
    fetchAvailablePeople(duration);
});
</script>
