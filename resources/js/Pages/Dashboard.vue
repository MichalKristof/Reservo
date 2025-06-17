<template>
    <Head :title="`| ${$page.component}`"/>
    <h1 class="title">Welcome to Reservo {{ $page.props.auth.user.name }}</h1>
    <div class="flex w-full justify-center align-items-center">
        <img src="/public/images/reservo-logo-512x512.png" alt="Reservo Logo" class="w-auto"/>
    </div>

    <div class="flex justify-center items-center gap-5">
        <Link :href="route('reservations.index')" as="button" type="button" class="primary-btn" preserve-scroll>
            Reservations
        </Link>

        <Link :href="route('reservations.create')" as="button" type="button" class="primary-btn" preserve-scroll>
            Create Reservation
        </Link>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>
