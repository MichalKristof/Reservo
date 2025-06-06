<template>
    <div class="mb-6">
        <label>{{ name }}</label>
        <input :class="{'ring-red-500' : message}" v-model="model" :type="type" name="name" :autocomplete="autocomplete"
        />
        <small class="error" v-if="message">{{ message }}</small>
    </div>
</template>

<script setup lang="ts">
import {computed} from "vue";

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    modelValue: {
        type: String,
        default: ''
    },
    message: String,
});

const model = defineModel({
    type: null,
    required: true,
});

const autocomplete = computed(() => {
    if (props.type === 'password') return 'new-password';
    if (props.type === 'email') return 'email';
    return 'on';
});
</script>

<style lang="scss" scoped>

</style>
