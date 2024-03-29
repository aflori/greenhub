<script setup>
    import LogoSearch from "@/components/icons/Search_alt_light.vue";
    import { useProductListStore } from "@/stores/listProduct.js";

    const productStore = useProductListStore();

    function add_qu_filter() {
        productStore.changeFilter("name", "qu")
    }
</script>

<template>
    <div>
        <LogoSearch class="black" />
        <button class="btn" @click="add_qu_filter" > ajouter un filtre par nom </button>
    </div>
</template>

<style scoped>
.black {
    color: black;
}
</style>
