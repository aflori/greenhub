<script setup>
    import { useLoggingStore } from "@/stores/loggin.js"
    import { useRouter } from 'vue-router'

    const router = useRouter()


    const logData = useLoggingStore();
    if (logData.isLogged) {
        router.push({name: "home"})
    }
</script>

<template>
    <main>
    </main>
</template>
