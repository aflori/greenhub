<script setup>
import LogOut from '@/components/icons/Sign_out_squre.vue'
import { RouterLink } from 'vue-router'

import { useLoggingStore } from '@/stores/loggin.js'
const logConnectionStore = useLoggingStore()
</script>

<template>
  <RouterLink :to="{ name: 'log_in' }" class="my-auto">
    <LogOut class="h-14 w-14" @click="logConnectionStore.logout" />
    <p v-if="logConnectionStore.name != null" class="text-neutral text-center">
      {{ logConnectionStore.name }}
    </p>
  </RouterLink>
</template>
