<script setup>
import LogoData from '@/components/molecules/LogoData.vue'
import MenuLink from '@/components/molecules/MenuLink.vue'
import HeaderCart from '@/components/molecules/HeaderCart.vue'
import LogDatas from '@/components/molecules/LogDatas.vue'

// list of redirection element in menue
const menuLinks = [
  {
    urlName: 'product_list',
    text: 'Nos produits'
  }
]
</script>

<template>
  <header class="bg-accent">
    <div id="logos" class="flex flex-row justify-evenly container">
      <LogoData />
      <!-- <Logo_greenhub class="h-10"/> -->
      <LogDatas />
      <HeaderCart />
    </div>
  </header>
  <div id="menu" class="flex flex-row justify-evenly container">
    <MenuLink v-bind="link" v-for="link in menuLinks" :key="link" />
  </div>
</template>
