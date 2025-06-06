<template>
    <div class="w-full mb-6">
        <label>{{ name }}</label>
        <select
            v-model="model"
            :name="name"
            class="w-full border border-gray-300 rounded px-3 py-2"
            :disabled="disabled"
            :class="{'ring-red-500': message}"
        >
            <option v-if="placeholder" disabled value="">{{ placeholder }}</option>
            <option
                v-for="(option, index) in options"
                :key="index"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <small class="error" v-if="message">{{ message }}</small>
    </div>
</template>

<script setup lang="ts">
defineProps({
    name: {
        type: String,
        required: true
    },
    message: String,
    options: {
        type: Array as () => { value: string | number; label: string | number }[],
        required: true
    },
    placeholder: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const model = defineModel({
    type: null,
    required: true,
});
</script>

<style lang="scss" scoped>

</style>
