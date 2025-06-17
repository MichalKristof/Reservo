<template>
    <Head :title="` | ${$page.component}`"/>
    <div class="flex justify-center">
        <img src="/public/images/reservo-logo-192x192.png" alt="Reservo Logo" class="w-auto"/>
    </div>
    <div class="w-2/4 mx-auto">
        <form @submit.prevent="submit" class="form">
            <h1 class="title">Register</h1>
            <Input name="name" v-model="form.name" :message="form.errors.name"/>
            <Input name="email" v-model="form.email" type="email" :message="form.errors.email"/>
            <Input name="password" v-model="form.password" type="password" :message="form.errors.password"/>
            <Input name="confirm password" v-model="form.password_confirmation" type="password"/>
            <div>
                <p class="text-slate-600 mb-2">Already a user ? <a href="#" class="text-link">Login</a></p>
                <button type="submit" class="primary-btn" :disabled="form.processing">
                    Register
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import {useForm} from '@inertiajs/vue3';
import Input from "../../Components/Input.vue";
import {route} from "../../../../vendor/tightenco/ziggy/src/js/index.js";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.post(route('register'), {
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            form.reset('password', 'password_confirmation');
        }
    });
}
</script>

<style lang="scss" scoped>

</style>
