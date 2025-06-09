<template>
    <component
        :is="formData[step].component"
        v-model="form[formData[step].key].value"
        :name="formData[step].name"
        :type="formData[step].type"
        :options="formData[step].options"
        :message="form.errors[formData[step].key]"
        :disabled="form.processing"
    />

    <div class="flex justify-around gap-4">
        <button v-if="step !== 0" class="primary-btn" @click="prevStep">Previous</button>
        <button v-if="step !== totalSteps" class="primary-btn" @click="nextStep">Next</button>
        <button v-if="step === totalSteps" class="primary-btn" @click="submitForm">Search</button>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, reactive} from 'vue'
import {useForm, usePage} from '@inertiajs/vue3';
import DatePicker from "@/Components/DatePicker.vue";
import SelectBox from "@/Components/SelectBox.vue";

const page = usePage();
const step = ref(0);
const totalSteps = 3;

const form = useForm({
    reserved_at: {type: null, value: null, required: true},
    time: {type: null, value: null, required: true},
    duration: {type: null, value: null, required: true},
    number_of_people: {type: null, value: null, required: true},
})

const timeOptions = computed(() =>
    page.props.restaurant.times.map(time => ({value: time, label: time}))
);

const durationOptions = computed(() =>
    page.props.restaurant.durations.map(duration => ({
        value: duration,
        label: `${duration} ${duration === 1 ? 'Hour' : 'Hours'}`
    }))
);

const numberOfPeopleOptions = computed(() =>
    page.props.restaurant.number_of_people.map(people => ({
        value: people,
        label: `${people} ${people === 1 ? 'Person' : 'People'}`
    }))
);

const formData = reactive([
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
        options: timeOptions,
        required: true,
    },
    {
        key: 'duration',
        component: SelectBox,
        name: 'Duration',
        options: durationOptions,
        type: 'number',
        required: true,
    },
    {
        key: 'number_of_people',
        component: SelectBox,
        name: 'Number of People',
        options: numberOfPeopleOptions,
        type: 'number',
        required: true,
    }
]);

const validateStep = (): boolean => {
    const current = formData[step.value];
    const field = form[current.key];

    if (current.required && !field.value) {
        form.errors[current.key] = `${current.name} is required.`;
        return false;
    }

    form.errors = {};
    return true;
};

const nextStep = () => {
    if (validateStep()) {
        if (step.value < totalSteps) {
            step.value++;
        }
    }
};

const prevStep = () => {
    if (step.value > 1) {
        step.value--;
    }
};
</script>
