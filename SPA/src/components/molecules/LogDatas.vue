<script setup>
    import LogIn from "@/components/icons/Sign_in_squre.vue";
    import LogOut from "@/components/icons/Sign_out_squre.vue";

    import { useLoggingStore } from "@/stores/loggin.js";

    const myStore = useLoggingStore();
</script>

<template>
    <LogIn class="h-16 w-16 my-auto" v-if="myStore.isLogged" />
    <LogOut class="h-14 w-14 my-auto" v-else />
</template>
