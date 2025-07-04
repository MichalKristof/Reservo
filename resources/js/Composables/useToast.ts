import {ref} from 'vue';

type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface ToastMessage {
    id: number;
    type: ToastType;
    message: string;
    visible: boolean;
}

const toasts = ref<ToastMessage[]>([]);

function showToast({type = 'success', message, visible}: Omit<ToastMessage, 'id'>): void {
    const id = Date.now();
    toasts.value.push({id, type, message, visible});

    setTimeout(() => {
        const toast = toasts.value.find(t => t.id === id);
        if (toast) toast.visible = false;

        setTimeout(() => {
            toasts.value = toasts.value.filter(t => t.id !== id);
        }, 500);
    }, 4000);
}

export function useToast() {
    return {
        toasts,
        showToast,
    };
}
