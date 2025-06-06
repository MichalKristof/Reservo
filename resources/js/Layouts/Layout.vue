<template>
    <div class="relative">
        <header>
            <nav>
                <div class="space-x-6">
                    <Link v-if="!$page.props.auth.user" :href="route('home')" preserve-scroll class="nav-link"
                          :class="{'bg-slate-100': $page.component === 'Home'}">Home
                    </Link>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" preserve-scroll class="nav-link"
                          :class="{'bg-slate-100': $page.component === 'Dashboard'}">Dashboard
                    </Link>
                </div>
                <div class="space-x-6">
                    <div v-if="$page.props.auth.user" class="flex items-center gap-2">
                        <span class="text-white">Welcome, {{ $page.props.auth.user.name }}</span>
                        <Link :href="route('logout')" method="post" as="button" type="button" preserve-scroll
                              class="nav-link">
                            Logout
                        </Link>
                    </div>

                    <div v-else class="flex items-center gap-2">
                        <Link :href="route('register')" as="button" type="button" preserve-scroll class="nav-link"
                              :class="{'bg-slate-100': $page.component === 'Auth/Register'}">
                            Register
                        </Link>
                        <Link :href="route('login')" as="button" type="button" preserve-scroll class="nav-link"
                              :class="{'bg-slate-100': $page.component === 'Auth/Login'}">
                            Login
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <main class="p-4">
            <slot/>
        </main>
    </div>

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
import {route} from "../../../vendor/tightenco/ziggy/src/js/index.js";
import ToastMessage from '../Components/ToastMessage.vue'
import {usePage} from "@inertiajs/vue3";
import {watch} from "vue";
import {useToast} from "../Composables/useToast";

const page = usePage()
const {showToast, toasts} = useToast()


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
            })
        }
    },
    {immediate: true}
)


</script>
