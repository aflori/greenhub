<script setup>
    import { onMounted, watch } from 'vue';
    import BasicProductCard from "@/components/molecules/BasicProductCard.vue";
    import FilterCard from "@/components/products/filterField.vue"
    import { useProductListStore } from "@/stores/listProduct.js";

    const listProduct = useProductListStore();

    listProduct.load()
</script>

<template>
    <main class="flex flex-row">
        <FilterCard class="w-1/12"/>
        <div class="flex flex-row flex-wrap justify-evenly w-11/12">
            <BasicProductCard :product="product" v-for="product in listProduct.products" class="my-2"/>
        </div>
    </main>
</template>
