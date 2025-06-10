import axios from 'axios';

let toastFunction: ((params: { type: string; message: string; visible: boolean }) => void) | null = null;

export function setAxiosToastHandler(showToast: (params: { type: string; message: string; visible: boolean }) => void) {
    toastFunction = showToast;
}

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            const status = error.response.status;
            const data = error.response.data;
            const isDev = import.meta.env.MODE === 'development';

            if (status === 422 && data.errors && toastFunction) {
                const errorMessages = Object.values(data.errors).flat() as string[];

                errorMessages.forEach(errorMsg => {
                    if (toastFunction) {
                        toastFunction({type: 'error', message: errorMsg, visible: true});
                    }
                });
            } else if (status >= 500 && toastFunction) {
                if (isDev) {
                    toastFunction({
                        type: 'error',
                        message: data.message || JSON.stringify(data) || 'Server error. Please check console.',
                        visible: true,
                    });
                } else {
                    toastFunction({
                        type: 'error',
                        message: 'Server error. Please try again later.',
                        visible: true,
                    });
                }
            } else if (data.message && toastFunction) {
                toastFunction({type: 'error', message: data.message, visible: true});
            }


        }

        return Promise.reject(error);
    }
);
