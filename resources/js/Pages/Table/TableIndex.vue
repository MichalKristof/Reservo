<template>
    <h2 class="text-2xl font-semibold mb-6">Available Tables</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div v-for="table in $page.props.tables" :key="table.id" class="bg-white p-4 rounded shadow hover:shadow-lg transition">
            <h3 class="font-bold text-lg mb-2">{{ table.name }}</h3>
            <p>Seats: <span class="font-semibold">{{ table.seats }}</span></p>
            <p>Status: <span :class="table.status === 'active' ? 'text-green-600' : 'text-red-600'">{{ table.status }}</span></p>
        </div>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>
