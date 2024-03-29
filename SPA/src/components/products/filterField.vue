<script setup>
    import LogoSearch from "@/components/icons/Search_alt_light.vue";
    import { useProductListStore } from "@/stores/listProduct.js";
    import { ref } from 'vue';
    const productStore = useProductListStore();

    const nameFilter = ref("");
    function add_qu_filter() {
        if(nameFilter.value === "") {
            productStore.changeFilter("name", undefined)
            return ;
        }

        productStore.changeFilter("name", nameFilter.value);
    }
</script>

<template>
    <div>
        <!-- <LogoSearch class="black" /> -->
        <input type="text" placeholder="Type here" class="input input-bordered input-xs w-full" v-model="nameFilter"/>
        <button class="btn" @click="add_qu_filter" > ajouter un filtre par nom </button>
    </div>
</template>

<style scoped>
.black {
    color: black;
}
</style>
