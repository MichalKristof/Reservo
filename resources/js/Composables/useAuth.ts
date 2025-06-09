import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';

export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth.user);
    const isAuthenticated = computed(() => !!user.value);
    const userName = computed(() => user.value?.name ?? '');

    return {
        user,
        isAuthenticated,
        userName,
    };
}
