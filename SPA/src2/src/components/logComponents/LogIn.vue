<script setup>
import LogIn from '@/components/icons/Sign_in_squre.vue'
import { RouterLink } from 'vue-router'
</script>

<template>
  <RouterLink :to="{ name: 'log_in' }" class="my-auto">
    <LogIn class="h-16 w-16" />
  </RouterLink>
</template>
