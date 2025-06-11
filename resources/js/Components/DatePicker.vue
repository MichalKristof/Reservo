<template>
    <div class="w-full mb-6">
        <label>{{ name }}</label>
        <VueDatePicker
            :model-value="model"
            :enable-minutes="false"
            :format="customFormatter"
            @update:modelValue="updateModelValue"
            :loading="disabled"
            :min-date="computedMinDate"
        />
        <div v-if="message">
            <template v-if="Array.isArray(message)">
                <small v-for="(msg, index) in message" :key="index" class="error">{{ msg }}</small>
            </template>
            <template v-else>
                <small class="error">{{ message }}</small>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';

const customFormatter = (date: Date) => {
    return date.toLocaleDateString('cs-CS').toString();
};

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    message: [String, Array],
    disabled: {
        type: Boolean,
        default: false,
    },
    allowPastDates: {
        type: Boolean,
        default: false,
    },
});

const tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);
tomorrow.setHours(0, 0, 0, 0);

const computedMinDate = computed(() => {
    return props.allowPastDates ? null : tomorrow;
});

const emit = defineEmits(['update:modelValue']);

const updateModelValue = (value: string | Date) => {
    emit('update:modelValue', value);
};

const model = defineModel({
    type: null,
    required: true,
});
</script>
