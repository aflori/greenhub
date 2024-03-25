<script setup>
    import Logo_greenhub from "@/components/icons/Logo_greenhub.vue";
    import LogoData from "@/components/molecules/LogoData.vue";
    import MenuLink from "@/components/molecules/MenuLink.vue";
    import HeaderBasket from "@/components/molecules/HeaderBasket.vue";

    // list of redirection element in menue
    const menuLinks = [
        {
            urlName: "product_list",
            text: "Nos produits"
        }
    ];

</script>

<template>
    <header class="bg-accent">
        <div id="logos" class="flex flex-row justify-evenly container">
            <LogoData />
            <!-- <Logo_greenhub class="h-10"/> -->
            <!-- <Logo_greenhub class="h-10"/> -->
            <HeaderBasket />
        </div>
    </header>
    <div id="menu" class="flex flex-row justify-evenly container">
        <MenuLink v-bind="link" v-for="link in menuLinks"/>
    </div>
</template>
