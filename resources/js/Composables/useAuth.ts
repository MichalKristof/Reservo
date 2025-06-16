import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth?.user);
    const isAuthenticated = computed(() => page.props.auth?.isAuthenticated ?? false);
    const isAdmin = computed(() => page.props.auth?.isAdmin ?? false);

    return {
        user,
        isAuthenticated,
        isAdmin,
    };
}
