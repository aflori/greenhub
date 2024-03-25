<script setup>
    import BasketLogo from "@/components/icons/Basket.vue";
    import { useBacketStore } from "@/stores/backet.js";
    import { computed } from 'vue';
    import { RouterLink } from 'vue-router'

    const backetStore = useBacketStore();
    const getTotalPrice = computed(() => {
        let totalPrice = backetStore.totalPrice;
        totalPrice *= 100;
        totalPrice = Math.round(totalPrice);
        return totalPrice/100;
    })
</script>

<template>
    <RouterLink :to="{name: 'command'}">
        <BasketLogo class="h-14 w-14 stroke-neutral"/>
        <p class="text-neutral max-w-fit mx-auto">{{ getTotalPrice }} €</p>
    </RouterLink>
</template>