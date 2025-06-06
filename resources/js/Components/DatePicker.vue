<template>
    <div class="w-full mb-6">
        <label>{{ name }}</label>
        <VueDatePicker
            :model-value="model"
            :enable-minutes="false"
            :minutes-increment="true"
            :format="customFormatter"
            @update:modelValue="updateModelValue"
            :loading="disabled"
            :min-date="nextDay"
        />
        <small v-if="message" class="error">{{ message }}</small>
    </div>
</template>

<script setup lang="ts">

const customFormatter = (date: Date) => {
    return date.toLocaleDateString('cs-CS');
};

defineProps({
    name: {
        type: String,
        required: true
    },
    format: {
        type: String,
        default: 'YYYY-MM-DD',
    },
    message: String,
    disabled: {
        type: Boolean,
        default: false,
    },
});

const currentDate = new Date();
const nextDay = new Date(currentDate);
nextDay.setDate(currentDate.getDate() + 1);

const emit = defineEmits(['update:modelValue']);

const updateModelValue = (value: Date) => {
    emit('update:modelValue', value);
};

const model = defineModel({
    type: null,
    required: true,
});
</script>
