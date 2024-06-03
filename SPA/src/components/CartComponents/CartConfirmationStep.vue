<script setup>

import { useFormStore } from '@/stores/form.js'
import { useCartStore } from '@/stores/cart.js'

const formStore = useFormStore()
const cartData = useCartStore()
</script>

<template>
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
        <!-- <span> {{ cartData.listProductInCart }}</span> -->
    </div>
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