<script setup>
    import { ref } from "vue";
    import { useProductListStore } from "@/stores/listProduct.js";
    import { useBacketStore } from "@/stores/backet.js";
    import BaketInput from "@/components/molecules/BacketInput.vue";
    function getIdOnGoodType(propsParam) {
        return propsParam.id;
    }

    const props = defineProps({
        id: String
    })
    const productStore = useProductListStore();
    const backetStore = useBacketStore();

    const product = ref({});

    const productId = getIdOnGoodType(props)
    productStore.getSingleProduct(productId)
        .then((productData) => {
        product.value = productData
    });

    const numberOfProduct = ref(1);

    function updateNumberOfProduct(newValue) {
        numberOfProduct.value = newValue;
    }
    function moveToBracket() {
        backetStore.addProduct(product.value, numberOfProduct.value);
        numberOfProduct.value = 1;
    }


</script>

<template>
    <main>
        <!-- the v-if test the product chargement by looking into its main attribute -->
        <div v-if="product['id'] !== undefined" class="card lg:card-side bg-base-100 shadow-xl">
            <figure>
                <img v-if="product.image!==undefined" :src="product.image" alt="Album" class="sm:w-80 sm:h-80 sm:min-w-80 w-40 h-40"/>
                <div v-else class="lg:w-80 lg:h-80"> </div>
            </figure>
            <div class="card-body">
                <h2 class="card-title">{{ product.title }}</h2>
                <p> {{ product.description }}</p>
                <div class="card-actions justify-between">
                    <div> <p> {{ product.price }} </p> </div>
                    <BaketInput :numberOfProduct="numberOfProduct" @numberOfProductChanged="updateNumberOfProduct"/>
                    <button class="btn btn-primary" @click="moveToBracket">Acheter</button>
                </div>
            </div>
        </div>
        <div>
            <h3> Publier un commentaire </h3>
            <textarea class="textarea w-80" placeholder="Bio"></textarea>
            <br>
            <button class="btn"> Publier </button>
        </div>
    </main>
</template>
