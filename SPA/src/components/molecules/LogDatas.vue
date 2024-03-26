<script setup>
    import LogIn from "@/components/logComponents/LogIn.vue";
    import LogOut from "@/components/icons/Sign_out_squre.vue";

    import { useLoggingStore } from "@/stores/loggin.js";

    const myStore = useLoggingStore();
</script>

<template>
    <LogOut class="h-14 w-14 my-auto" v-if="myStore.isLogged" />
    <LogIn v-else />
</template>
