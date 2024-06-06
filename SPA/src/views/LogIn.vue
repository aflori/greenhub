<script setup>
import LogInForm from '@/components/logComponents/logInForm.vue'
import RegisterForm from '@/components/logComponents/registerForm.vue'
import { ref, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import { useLoggingStore } from '@/stores/loggin'

const router = useRouter();

async function redirectIfAuthenticated() {
  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
  const logger = useLoggingStore();
  while(logger.isLogged === undefined) {
    await sleep(100)
  }
  if(logger.isLogged) {
    router.push({"name": "home"})
  }
}
onBeforeMount(redirectIfAuthenticated)

const textToRegister = 'créer un compte'
const textToLogIn = 'Se connecter'

const hasAnAcount = ref(true)
const textFormSwitch = ref(textToRegister)

function changeForm() {
  if (hasAnAcount.value) {
    hasAnAcount.value = false
    textFormSwitch.value = textToLogIn
  } else {
    hasAnAcount.value = true
    textFormSwitch.value = textToRegister
  }
}
</script>

<template>
  <main>
    <LogInForm v-if="hasAnAcount" />
    <RegisterForm v-else />

    <a @click.prevent="changeForm"> {{ textFormSwitch }}</a>
  </main>
</template>

<style scoped>
a:hover {
  cursor: pointer;
}

a {
  text-decoration: underline;
  color: blue;
}
</style>
