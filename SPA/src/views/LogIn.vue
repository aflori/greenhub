<script setup>
    import LogInForm from '@/components/logComponents/logInForm.vue'
    import RegisterForm from '@/components/logComponents/registerForm.vue'
    import { ref } from 'vue'

    const hasAnAcount = ref(true);

</script>

<template>
    <main>
        <LogInForm v-if="hasAnAcount" />
        <RegisterForm v-else />
   </main>
</template>

