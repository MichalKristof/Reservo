import axios from 'axios';

let toastFunction: ((params: { type: string; message: string; visible: boolean }) => void) | null = null;

// const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
// if (token) {
//     axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
// } else {
//     console.warn('CSRF token not found in <meta> tag');
// }

export function setAxiosToastHandler(showToast: (params: { type: string; message: string; visible: boolean }) => void) {
    toastFunction = showToast;
}

axios.interceptors.response.use(
    response => response,
    error => {
        const status = error.response?.status;
        const data = error.response?.data;
        const isDev = import.meta.env.MODE === 'development';

        if (status === 422) {
            return Promise.reject(error);
        }

        if (status >= 500 && toastFunction) {
            const message = isDev
                ? data?.message || JSON.stringify(data) || 'Server error. Please check console.'
                : 'Server error. Please try again later.';

            toastFunction({type: 'error', message, visible: true});
        } else if (data?.message && toastFunction) {
            toastFunction({type: 'error', message: data.message, visible: true});
        }

        return Promise.reject(error);
    }
);

