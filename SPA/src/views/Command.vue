<script setup>
    import { ref } from 'vue';
    import ListElement from "@/components/BacketComponents/BacketPosition.vue";
    import BacketComposition from "@/components/BacketComponents/BacketComposition.vue";
    import BacketAdressStep from "@/components/BacketComponents/BacketAdressStep.vue";

    const position = ref(1);
    const texts = [
        "panier",
        "livraison",
        "paiement",
        "confirmation"
    ];
</script>

<template>
    <main>
        <BacketComposition @next-step="position++" v-if="position===1" />
        <BacketAdressStep  @next-step="position++" @prev-step="position--" v-else-if="position==2" />
        <ul class="steps steps-vertical lg:steps-horizontal">
            <ListElement :text="text" :stepNumber="index+1" :position="position" v-for="(text,index) in texts"/>
        </ul>
    </main>
</template>
