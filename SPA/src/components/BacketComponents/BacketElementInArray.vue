<script setup>
    import { defineProps, ref, computed } from "vue";
    import AddIcon from "@/components/icons/Add_icon.vue";
    import SubIcon from "@/components/icons/LessIcon.vue";
    import DeleteIcon from "@/components/icons/Trash.vue";
    import { useBacketStore } from "@/stores/backet.js";

    const props = defineProps(["products"]);

    const getTotalPrice = computed(() => {
        let totalPrice = props.products.totalPrice;
        totalPrice *= 100;
        totalPrice = Math.round(totalPrice);
        return totalPrice/100;
    })

    const commandStore = useBacketStore();

    function addProductToBacket(quantity) {
        commandStore.modifyQuantityOf(props.products.product.id, props.products.quantity + quantity)
    }
    function removeProductFromBacket() {
        commandStore.removeFromBacket(props.products.product.id);
    }

</script>

<template>
    <tr class="max-w-full">
        <td class="custom-max-w-29 ">
            <!-- daisyUI component for showing full title or not // section checkbox -->
            <label class="swap">
                <input type="checkbox" />
                <div class="swap-off custom-max-w truncate my-auto"> {{ products.product.title }} </div>
                <div class="swap-on text-center"> {{ products.product.title }} </div>
            </label>
        </td>
        <td> <div class="flex flex-wrap place-content-evenly flex-col-reverse sm:flex-row">
            <SubIcon class="cursor-pointer" @click="addProductToBacket(-1)"/>
            <div class="mx-auto"> <span> {{ products.quantity}}</span> </div>
            <AddIcon class="cursor-pointer" @click="addProductToBacket(+1)"/>
        </div> </td>
        <td class="text-center">
            <div class="mb-3"> {{ products.unitPrice}} € </div>
            <DeleteIcon class="mx-auto cursor-pointer" @click="removeProductFromBacket"/> 
        </td>
        <td>{{ getTotalPrice }} €</td>
    </tr>
</template>

<style scoped>
    .custom-max-w-29 {
        max-width: 29vw;
    }
    .custom-max-w-10 {
        max-width: 10vw;
    }
</style>
