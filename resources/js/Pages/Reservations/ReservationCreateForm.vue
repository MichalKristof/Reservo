<template>
    <component
        v-for="(stepItem, index) in formData"
        :key="index"
        :is="stepItem.component"
        v-model="form[stepItem.key].value"
        :name="stepItem.name"
        :type="stepItem.type"
        :options="stepItem.options"
        :message="form.errors[stepItem.key]"
        :disabled="form.processing"
        class="mb-4"
    />

    <div class="flex justify-around gap-4">
        <button class="primary-btn" @click="submitForm">Search</button>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, reactive, watch} from 'vue'
import {useForm, usePage} from '@inertiajs/vue3';
import DatePicker from "@/Components/DatePicker.vue";
import SelectBox from "@/Components/SelectBox.vue";
import axios from "axios";

type FormKeys = 'reserved_at' | 'time' | 'duration' | 'number_of_people';

type FormField = {
    type: string | null;
    value: any;
    required: boolean;
};

const form = useForm<Record<FormKeys, FormField>>({
    reserved_at: {type: null, value: null, required: true},
    time: {type: null, value: null, required: true},
    duration: {type: null, value: null, required: true},
    number_of_people: {type: null, value: null, required: true},
});

const availableTimes = ref([]);
const availableDurations = ref([]);
const availablePeopleOptions = ref([]);

const formData = ref<Array<{
    key: FormKeys;
    component: any;
    name: string;
    type?: string;
    options?: any;
    required: boolean;
}>>([
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
    console.log('Submitting form with data:', form);
}

watch(() => form.reserved_at.value, async (newDate) => {
    if (!newDate) return;
    console.log(newDate);
    const res = await axios.post(route('api.reservations.available-times'), {
        reserved_at: newDate
    });
    availableTimes.value = res.data.map(t => ({value: t, label: t}));
    form.time.value = null;
    form.duration.value = null;
});

watch(() => form.time.value, async (time) => {
    if (!form.reserved_at.value || !time) return;
    const res = await axios.post(route('api.reservations.available-durations'), {
        reserved_at: form.reserved_at.value,
        time
    });
    availableDurations.value = res.data.map(d => ({
        value: d,
        label: `${d} ${d === 1 ? 'Hour' : 'Hours'}`
    }));
    form.duration.value = null;
});

watch(() => form.duration.value, async (duration) => {
    if (!form.reserved_at.value || !form.time.value || !duration) return;
    const res = await axios.post(route('api.reservations.available-people'), {
        reserved_at: form.reserved_at.value,
        time: form.time.value,
        duration
    });
    availablePeopleOptions.value = res.data.map(p => ({
        value: p,
        label: `${p} ${p === 1 ? 'Person' : 'People'}`
    }));
});
</script>
