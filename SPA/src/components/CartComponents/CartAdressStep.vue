<script setup>
    import { ref } from 'vue';
    import { useFormStore } from "@/stores/form.js";
    import FormField from "./CartFormField.vue";

    const emit = defineEmits([ 'next-step' ]);
    const formStore = useFormStore();
    const formData = formStore.adressDelivery;
</script>

<template>
    <form>
        <FormField :label="label" :form="'adressDelivery'" v-for="(field, label) in formData" />
        <button type="submit" class="btn" @click.prevent="$emit('next-step')" :disabled="!formStore.isAdressFormValid">
            valider
        </button>
        <button type="reset" class="btn" @click.prevent="$emit('prev-step')">
            retour
        </button>
    </form>
</template>
