<template>
    <Head :title="`| ${$page.component}`"/>
    <h1 class="title">Welcome to Reservo</h1>
    <h5 class="subtitle">To book a table, please sign up or log in to your account.</h5>
    <div class="flex w-full justify-center align-items-center">
        <img src="/public/images/reservo-logo-512x512.png" alt="Reservo Logo" class="w-auto"/>
    </div>

    <div class="flex justify-center items-center gap-5">
        <Link :href="route('register')" as="button" type="button" class="primary-btn" preserve-scroll>
            Register
        </Link>

        <Link :href="route('login')" as="button" type="button" class="primary-btn" preserve-scroll>
            Login
        </Link>
    </div>
</template>

<script setup lang="ts">
</script>

