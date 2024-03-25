<script setup>
    import Logo_greenhub from "../icons/Logo_greenhub.vue"
    import { RouterLink } from 'vue-router'

    const props = defineProps({
        urlName: String,
        text: String
    });
    const url = {
        name: props.urlName
    }
    // console.log(props.urlName);
    // console.log(route);
</script>

<template>
    <RouterLink :to="{name: props.urlName}">
        {{ text }}
    </RouterLink>
</template>