<script setup>
    import { useBacketStore } from "@/stores/backet.js";
    import { ref, defineEmits } from 'vue';
    import BacketLine from "./BacketElementInArray.vue";

    // const emits = defineEmits([ "nextStep" ]);]);

    const backetData = useBacketStore();

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

            <BacketLine :products="product" v-for="product in backetData.listProductInBacket"/>

        </table>
        <hr>
        <div>
            <strong> total: {{ backetData.totalPrice }} €</strong>
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
