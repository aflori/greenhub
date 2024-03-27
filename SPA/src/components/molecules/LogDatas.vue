<script setup>
    import LogIn from "@/components/logComponents/LogIn.vue";
    import LogOut from "@/components/logComponents/LogOut.vue";

    import { useLoggingStore } from "@/stores/loggin.js";

    const myStore = useLoggingStore();
</script>

<template>
    <LogOut class="my-auto" v-if="myStore.isLogged" />
    <LogIn class="my-auto" v-else />
</template>
