import type {Page} from '@inertiajs/core';
import type {PageProps} from './inertia';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route(
            name?: string,
            params?: any,
            absolute?: boolean
        ): string;

        $page: Page<PageProps>;
    }
}

export {};
