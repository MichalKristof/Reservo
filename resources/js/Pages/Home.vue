<template>
    <Head :title="`| ${$page.component}`"/>
    <section class="grid md:grid-cols-2 gap-8 py-16 items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Easy Reservations</h2>
            <p class="text-slate-700 text-lg mb-4">
                Book your table in seconds. No phone calls, no hassle — just a smooth online experience. Make your
                reservation
                with Reservo and enjoy your dining experience without the wait. Register now to get started!
            </p>
            <Link :href="route('register')" as="button" type="button" class="primary-btn" preserve-scroll>
                Register
            </Link>
        </div>
        <div class="flex justify-center">
            <img src="/public/images/reservo_logo.jpeg" alt="Reservo Logo" class="w-auto"/>
        </div>
    </section>
</template>

<script setup lang="ts">
</script>

