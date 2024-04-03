<script setup>
    import CartLogo from "@/components/icons/Basket.vue";
    import { useCartStore } from "@/stores/cart.js";
    import { computed } from 'vue';
    import { RouterLink } from 'vue-router'

    const cartStore = useCartStore();
    const getTotalPrice = computed(() => {
        let totalPrice = cartStore.totalPrice;
        totalPrice *= 100;
        totalPrice = Math.round(totalPrice);
        return totalPrice/100;
    })
</script>

<template>
    <RouterLink :to="{name: 'command'}">
        <CartLogo class="h-14 w-14 stroke-neutral"/>
        <p class="text-neutral max-w-fit mx-auto">{{ getTotalPrice }} €</p>
    </RouterLink>
</template>
