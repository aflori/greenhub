<script setup>
    import Logo_greenhub from "../icons/Logo_greenhub.vue"
    import { RouterLink } from 'vue-router'
</script>

<template>
    <RouterLink :to="{name: 'home'}" class="grid grid-cols-2 my-2">
        <Logo_greenhub class="h-16" />
        <span class="font-serif my-auto font-extrabold text-2xl text-info max-sm:invisible max-sm:text-base max-sm:font-extralight"> Greenhub </span>
    </RouterLink>
</template>