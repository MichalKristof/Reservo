<template>
    <div class="w-full mb-6">
        <label>{{ name }}</label>
        <input :class="{'ring-red-500' : message}" v-model="model" :type="type" :name="name"
               :autocomplete="autocomplete"
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
    message: [String, Array],
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
