<script setup>
import { useFormStore } from '@/stores/form.js'
import FormField from './CartFormField.vue'

const emit = defineEmits(['next-step', 'prev-step'])

const formStore = useFormStore()
const formName = 'adressDelivery'
const formData = formStore[formName]

function goToNextStepIfDataValid() {
  const errors = formStore.getInvalidOutputList()
  if(errors.length === 0) {
    emit('next-step')
  }
}
</script>

<template>
  <form>
    <FormField :label="label" :form="formName" v-for="(field, label) in formData" :key="label" />
    <button
      type="submit"
      class="btn"
      @click.prevent="goToNextStepIfDataValid"
    >
      valider
    </button>
    <button type="reset" class="btn" @click.prevent="$emit('prev-step')">retour</button>
  </form>
</template>
