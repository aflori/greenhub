<script setup>
import { useFormStore } from '@/stores/form.js'
import FormField from './CartFormField.vue'
import { ref } from 'vue' 

const emit = defineEmits(['next-step', 'prev-step'])

const formStore = useFormStore()
const formName = 'adressDelivery'
const formData = formStore[formName]
const errors = ref([])


function goToNextStepIfDataValid() {
  const validationsErrors = formStore.getInvalidAdressFormField()
  
  if(validationsErrors.length === 0) {
    emit('next-step')
    return
  }

  errors.value = validationsErrors
}
</script>

<template>
  <form>
    <p v-if="errors.length > 0" class="error">
      Les champs du formulaire sont invalides.
      Veuillez corriger les champs <span v-for="e in errors"> {{ e }}, </span> 
    </p>
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

<style scoped>
.error {
  color: #F00;
}
</style>