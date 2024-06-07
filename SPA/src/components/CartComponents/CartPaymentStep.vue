<script setup>
import { onMounted } from 'vue'
import { useFormStore } from '@/stores/form.js'
// import FormField from './CartFormField.vue'

defineEmits(['prev-step'])

const formStore = useFormStore()
let paymentId = null
let stripe = null;
let element = null;

onMounted(() => {
  formStore
  .startPayment()
  .then((response) => {
    // console.log(response.data)
    const apiResponse = response.data
    const stripePublicKey = apiResponse.stripe_key
    stripe = Stripe(stripePublicKey)
    paymentId = apiResponse.client_id

    const options = {
      clientSecret: paymentId
    }
    element = stripe.elements(options)

    const paymentElement = element.create('payment')
    paymentElement.mount("#stripe-payment-form")
  })
})

async function submiPayment() {

  const body = {};
  try {
    stripe.confirmPayment({
      elements: element,
      redirect: "if_required"
    });

    body.success = true;
  }
  catch {
    body.success = false;
  }

  formStore.sendPaymentConfirmation(body, paymentId);
}
</script>

<template>
  <form>
    <div id="stripe-payment-form"></div>
    <button
      type="submit"
      class="btn"
      @click.prevent="submiPayment"
    >
      valider
    </button>
    <button type="reset" class="btn" @click.prevent="$emit('prev-step')">retour</button>
  </form>
</template>

<style scoped>
.error {
  color: #F00;
}
</style>