<script setup>
import { useFormStore } from '@/stores/form.js'
import FormField from './CartFormField.vue'

defineEmits(['next-step', 'prev-step'])
const formStore = useFormStore()
const formName = 'paiementDatas'
const formData = formStore[formName]
</script>

<template>
  <form>
    <FormField :label="label" :form="formName" v-for="(field, label) in formData" :key="label" />
    <button
      type="submit"
      class="btn"
      @click.prevent="$emit('next-step')"
      :disabled="!formStore.isPaiementFormValid"
    >
      valider
    </button>
    <button type="reset" class="btn" @click.prevent="$emit('prev-step')">retour</button>
  </form>
</template>
