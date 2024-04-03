<script setup>
    import CartLogo from "@/components/icons/Basket.vue";
    import { useCartStore } from "@/stores/cart.js";
    import { computed } from 'vue';
    import { RouterLink } from 'vue-router'

    const cartStore = useCartStore();
</script>

<template>
    <RouterLink :to="{name: 'command'}">
        <CartLogo class="h-14 w-14 stroke-neutral"/>
        <p class="text-neutral max-w-fit mx-auto">{{ cartStore.getTotalPriceRounded }} €</p>
    </RouterLink>
</template>
