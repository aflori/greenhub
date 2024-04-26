<script setup>
import { ref } from 'vue'
import { useProductListStore } from '@/stores/listProduct.js'
import { useCartStore } from '@/stores/cart.js'
import BaketInput from '@/components/molecules/BacketInput.vue'
function getIdOnGoodType(propsParam) {
  return propsParam.id
}

const props = defineProps({
  id: String
})
const productStore = useProductListStore()
const cartStore = useCartStore()

const product = ref({})

const productId = getIdOnGoodType(props)
productStore.getSingleProduct(productId).then((productData) => {
  product.value = productData
})

const numberOfProduct = ref(1)

function updateNumberOfProduct(newValue) {
  numberOfProduct.value = newValue
}
function moveToCart() {
  cartStore.addProduct(product.value, numberOfProduct.value)
  numberOfProduct.value = 1
}

const commentContent = ref('')
function publishComment() {
  productStore.publishComment(props.id, commentContent.value)
}
</script>

<template>
  <main>
    <!-- the v-if test the product chargement by looking into its main attribute -->
    <div v-if="product['id'] !== undefined" class="card lg:card-side bg-base-100 shadow-xl">
      <figure>
        <img
          v-if="product.image !== undefined"
          :src="product.image"
          alt="Album"
          class="sm:w-80 sm:h-80 sm:min-w-80 w-40 h-40"
        />
        <div v-else class="lg:w-80 lg:h-80"></div>
      </figure>
      <div class="card-body">
        <h2 class="card-title">{{ product.title }}</h2>
        <p>{{ product.description }}</p>
        <div class="card-actions justify-between">
          <div>
            <p>{{ product.price }}</p>
          </div>
          <BaketInput
            :numberOfProduct="numberOfProduct"
            @numberOfProductChanged="updateNumberOfProduct"
          />
          <button class="btn btn-primary" @click="moveToCart">Acheter</button>
        </div>
      </div>
    </div>
    <div>
      <h3>Publier un commentaire</h3>
      <form>
        <textarea
          class="textarea textarea-bordered w-80"
          placeholder="Bio"
          v-model="commentContent"
        ></textarea>
        <br />
        <button class="btn" @click.prevent="publishComment">Publier</button>
      </form>
    </div>
  </main>
</template>
