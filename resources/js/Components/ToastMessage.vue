<template>
    <div v-if="visible" class="rounded-md border border-gray-300 bg-white p-4 shadow-sm">
        <div class="flex items-start gap-4">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                :class="['size-6', iconColorClass]"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            <div class="flex">
                <p class="mt-0.5 text-sm text-gray-700">{{ $props.message }}</p>
            </div>

            <button
                class="-m-2 rounded-full p-1 text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
                type="button"
                @click="close"
            >
                <span class="sr-only">Dismiss popup</span>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'info', 'warning'].includes(value),
    },
    message: {
        type: String,
        default: null,
    },
    visible: {
        type: Boolean,
    }
})
const visible = ref(props.visible || true);
const emit = defineEmits(['close']);

function close() {
    visible.value = false
    setTimeout(() => emit('close'), 500)
}

const iconColorClass = computed(() => {
    switch (props.type) {
        case 'success':
            return 'text-green-600';
        case 'error':
            return 'text-red-600';
        case 'info':
            return 'text-blue-600';
        case 'warning':
            return 'text-yellow-500';
        default:
            return 'text-blue-500';
    }
});
</script>

<style lang="scss" scoped>

</style>
