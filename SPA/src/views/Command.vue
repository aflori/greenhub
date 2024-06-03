<script setup>
import { ref, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'
import ListElement from '@/components/CartComponents/CartPosition.vue'
import CartComposition from '@/components/CartComponents/CartComposition.vue'
import CartAdressStep from '@/components/CartComponents/CartAdressStep.vue'
import CartPaymentStep from '@/components/CartComponents/CartPaymentStep.vue'
import { useLoggingStore } from '@/stores/loggin'

const position = ref(1)
const texts = ['panier', 'livraison', 'paiement', 'confirmation']

const router = useRouter();

async function redirectIfNotAuthenticated() {
  function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
  const logger = useLoggingStore();
  while(logger.isLogged === undefined) {
    await sleep(100)
  }
  if(! logger.isLogged) {
    router.push({"name": "log_in"})
  }
}
onBeforeMount(redirectIfNotAuthenticated)
</script>

<template>
  <main>
    <CartComposition @next-step="position++" v-if="position === 1" />
    <CartAdressStep @next-step="position++" @prev-step="position--" v-else-if="position === 2" />
    <CartPaymentStep @next-step="position++" @prev-step="position--" v-else-if="position === 3" />

    <ul class="steps steps-vertical lg:steps-horizontal">
      <ListElement
        :text="text"
        :stepNumber="index + 1"
        :position="position"
        v-for="(text, index) in texts"
        :key="text"
      />
    </ul>
  </main>
</template>
