<script setup>
    import LogOut from "@/components/icons/Sign_out_squre.vue";
    import { RouterLink } from 'vue-router';
</script>

<template>
    <router-link :to="{name: 'log_in'}" class="my-auto">
        <LogOut class="h-14 w-14"/>
    </ router-link>
</template>
