<script setup>

import { useFormStore } from '@/stores/form.js'
import { useCartStore } from '@/stores/cart.js'
const emit = defineEmits(['prev-step'])

const formStore = useFormStore()
const cartData = useCartStore()

function makeOrder() {
    formStore.makeOrder(cartData)
}
</script>

<template>
    <form>
        <div v-for="(values, key) in formStore.getFieldList">
            <strong> {{ key }} </strong>
            <div v-for="value in values">
                <span class="custom_label">{{ value.label }}</span>: <span class="custom_value">{{ value.value }}</span>
            </div>
        </div>
        <div>
            <strong> produits achetés </strong>
            <div v-for="product in cartData.listProductInCart">
                <br>
                <p><span class="custom_label"> nom de l'article </span>: <span class="custom_value"> {{ product.product.title }}</span></p>
                <p><span class="custom_label"> quantité</span>: <span class="custom_value"> {{ product.quantity }}</span></p>
            </div>
        </div>

        <button type="submit" class="btn" @click.prevent="makeOrder">Valider la commande</button>
        <button type="reset" class="btn" @click.prevent="$emit('prev-step')">Corriger des informations</button>
    </form>


</template>

<style scoped>
.custom_label {
    font-weight: 600;
}

.custom_value {
    font-style: oblique, 10deg;
}

strong {
    font-weight: 800;
}
</style>