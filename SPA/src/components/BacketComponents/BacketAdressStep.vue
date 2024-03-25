<script setup>
    import { ref,  defineEmits } from 'vue';
    import { useFormStore } from "@/stores/form.js";
    import FormField from "./BacketFormField.vue";

    const canSubmitForm = ref(false);
    const emit = defineEmits([ 'next-step' ]);
    const formStore = useFormStore();
    const formData = formStore.adressDelivery;

    function emitIfFormIsValid() {
        if(canSubmitForm.value) {
            emit("next-step")
        }
    }
</script>

<template>
    <form>
        <FormField :keyO="keyO" :form="'adressDelivery'" v-for="(field, keyO) in formData" />
        <button type="submit" class="btn" @click.prevent="emitIfFormIsValid()">
            valider
        </button>
        <button type="reset" class="btn" @click="$emit('prev-step')">
            retour
        </button>
    </form>
</template>
