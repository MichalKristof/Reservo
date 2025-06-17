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
            opening_time: string;
            closing_time: string;
        }
        selectedDate: string;
        tables: array<{
            id: number;
            name: string;
            seats: number;
            reservations: Array<{
                id: number;
                reserved_at: string;
                duration: number;
                number_of_people: number;
                user: {
                    email: string;
                }
            }>;
        }>
    }
}
export {};
