<script setup>
import { onMounted } from 'vue'
// import { useFormStore } from '@/stores/form.js'
import FormField from './CartFormField.vue'

defineEmits(['prev-step'])
// const formStore = useFormStore()

// onMounted( () => {
//   const paymentElement = elements.
// })
</script>

<template>
  <form>
    <div id="stripe-payment-form"></div>
    <button
      type="submit"
      class="btn"
      @click.prevent
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