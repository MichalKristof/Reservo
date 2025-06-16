import {PageProps as InertiaPageProps} from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps {
        auth: {
            user: {
                id: number;
                name: string;
            } | null;
            isAuthenticated: boolean;
            isAdmin: boolean;
        };
        ziggy: ZiggyConfig & { location: string };
        flash: unknown;
        appName: string;
        restaurant: {
            times: string[];
            durations: number[];
            number_of_people: number[];
        }
    }
}
export {};
