<template>
    <div class="relative">
        <header>
            <nav>
                <Link :href="route('home')" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="/public/images/reservo_logo.jpeg" alt="Reservo Logo" class="h-8 rounded-md"/>

                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Reservo</span>
                </Link>
                <div v-if="isAuthenticated" class="space-x-6">
                    <Link :href="route('dashboard')" preserve-scroll class="nav-link"
                          :class="{'active': $page.component === 'Dashboard'}">Dashboard
                    </Link>
                    <Link :href="route('reservations.index')" preserve-scroll class="nav-link"
                          :class="{'active': $page.component === 'Reservations/ReservationIndex'}">Reservation
                    </Link>
                    <Link :href="route('reservations.create')" preserve-scroll class="nav-link"
                          :class="{'active': $page.component === 'Reservations/ReservationCreate'}">Create
                        Reservation
                    </Link>
                    <Link v-if="isAdmin" :href="route('tables.index')" preserve-scroll class="nav-link"
                          :class="{'active': $page.component === 'Table/TableIndex'}">Tables
                    </Link>
                </div>
                <div class="space-x-6">
                    <div v-if="!isAuthenticated" class="flex items-center gap-2">
                        <Link :href="route('register')" as="button" type="button" preserve-scroll class="nav-link"
                              :class="{'active': $page.component === 'Auth/Register'}">
                            Register
                        </Link>
                        <Link :href="route('login')" as="button" type="button" preserve-scroll class="nav-link"
                              :class="{'active': $page.component === 'Auth/Login'}">
                            Login
                        </Link>
                    </div>
                    <div v-else class="flex items-center gap-2">
                        <div class="flex flex-row items-center gap-1">
                            <img src="/public/images/person.svg" alt="person" class="w-4 h-4"/>
                            <span class="text-base text-slate-600">{{ user?.name }}</span>
                        </div>

                        <Link :href="route('logout')" method="post" as="button" type="button" preserve-scroll
                              class="nav-link">
                            Logout
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <main class="p-4">
            <slot/>
        </main>
    </div>

    <footer class="fixed bottom-0 center-x w-full">
        <div class="bg-primary text-white text-center py-10">
            <p class="text-sm">© 2025 Reservo. All rights reserved.</p>
            <p class="text-xs">Made with ❤️ by <a href="https://www.linkedin.com/in/michal-kri%C5%A1tof-74a229204/"
                                                  class="text-blue-400 hover:underline" target="_blank">Michal
                Krištof</a>
            </p>
        </div>
    </footer>

    <TransitionGroup
        tag="div"
        class="absolute top-25 right-5 flex flex-col gap-2 max-w-[25vw]"
        enter-active-class="transition-all duration-500 ease-out"
        leave-active-class="transition-all duration-500 ease-in"
        enter-from-class="translate-x-full opacity-0"
        enter-to-class="translate-x-0 opacity-100"
        leave-from-class="translate-x-0 opacity-100"
        leave-to-class="translate-x-full opacity-0"
    >
        <ToastMessage
            v-for="toast in toasts"
            :key="toast.id"
            :type="toast.type"
            :message="toast.message"
            @close="() => closeToast(toast)"
        />
    </TransitionGroup>
</template>

<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
import {watch} from "vue";
import {useToast} from "@/Composables/useToast";
import {useAuth} from "@/Composables/useAuth";
import ToastMessage from "@/Components/ToastMessage.vue";
import {setAxiosToastHandler} from '@/axios';

const page = usePage()
const {isAdmin, isAuthenticated, user} = useAuth();
const {showToast, toasts} = useToast()

setAxiosToastHandler(({type, message, visible}) => {
    showToast({
        type: type as 'success' | 'error' | 'info' | 'warning',
        message,
        visible
    });
});

function closeToast(toast: any) {
    toast.visible = false
    setTimeout(() => {
        toasts.value = toasts.value.filter(t => t.id !== toast.id)
    }, 500)
}

interface FlashMessage {
    type?: 'success' | 'error' | 'info' | 'warning'
    message?: string
}

watch(
    () => page.props.flash as FlashMessage,
    (flash) => {
        if (flash && flash.message) {
            showToast({
                type: flash.type || 'success',
                message: flash.message,
                visible: true,
            });
        }
    },
    {immediate: true}
);


</script>
