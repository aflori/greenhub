<script setup>
    import { useBacketStore } from "@/stores/backet.js";
    import { ref } from 'vue';
    import CartLine from "./CartElementInArray.vue";

    // const emits = defineEmits([ "nextStep" ]);]);

    const cartData = useBacketStore();

</script>

<template>
    <div class="overflow-x-auto">
          <table class="table">
            <!-- head -->
            <thead>
              <tr class="text-center">
                <th>produit</th>
                <th>quantité</th>
                <th>prix unitaire</th>
                <th>prix total</th>
              </tr>
            </thead>

            <CartLine :products="product" v-for="product in cartData.listProductInBacket"/>

        </table>
        <hr>
        <div>
            <strong> total: {{ cartData.totalPrice }} €</strong>
        </div>
        <button class="btn" @click="$emit('next-step')"> Confirmer la sélection </button>
    </div>
</template>

<style scoped>
    hr {
        background-color: black;
        height: 2px;

    }
</style>
