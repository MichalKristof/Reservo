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
defineProps({
    name: {
        type: String,
        required: true
    },
    message: [String, Array],
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
