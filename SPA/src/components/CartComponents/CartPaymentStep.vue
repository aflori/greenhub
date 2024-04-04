<script setup>
    import { ref } from 'vue';
    import { useFormStore } from "@/stores/form.js";
    import FormField from "./CartFormField.vue";

    const emit = defineEmits([ 'next-step', 'prev-step' ]);
    const formStore = useFormStore();
    const formName = 'paiementDatas'
    const formData = formStore[formName];
</script>

<template>
    <form>
        <FormField :label="label" :form="formName" v-for="(field, label) in formData" />
        <button type="submit" class="btn" @click.prevent="$emit('next-step')" :disabled="!formStore.isValidForm(formName)">
            valider
        </button>
        <button type="reset" class="btn" @click.prevent="$emit('prev-step')">
            retour
        </button>
    </form>
</template>

