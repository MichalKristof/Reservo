import {PageProps as InertiaPageProps} from '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps {
        auth: {
            user: {
                id: number;
                name: string;
            } | null;
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
