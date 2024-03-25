<script setup>
    import { defineProps, defineEmits } from "vue";
    const props = defineProps({
        numberOfProduct: Number
    });

    const emits = defineEmits(['numberOfProductChanged']);

    function updateValue(event) {
        const tag = event.target
        const newValue = tag.value;

        if (newValue < 1) {
            tag.value = props.numberOfProduct;
        }
        else {
            emits('numberOfProductChanged', Number(newValue));
        }


    }
</script>

<template>
    <label class="form-control w-full max-w-sm flex flex-row">
        <span class="label-text"></span>
        <input type="number" min="1" :value="numberOfProduct" @input="updateValue" class="input input-bordered w-full max-w-xs" />
    </label>
</template>