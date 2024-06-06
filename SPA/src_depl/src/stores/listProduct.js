import { ref, reactive, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

const apiURL = 'https://api.aurelipool.space/api/products'

function getCorretlyFormatedObject(product) {
  /*
        received:
        {
            brand: "cupiditate"
            categories: ["consequatur"]
            comments: [
                "Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.",
                "Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.",
            ]
            description: "Est sit eos amet quis. Eum et recusandae accusantium corrupti facere aut dolorem. Officia dolorem optio minus minus iure. Animi corporis nisi nam soluta. Sit consequatur occaecati est error est accusantium. Sint modi ea quia quis molestiae ut tenetur beatae. Totam repellat nulla ex nihil qui. Facere est quia facilis distinctio consequatur ab atque. Rerum officia eos maiores accusamus cupiditate aliquid vitae. Aut magni sint quo. Quo inventore libero necessitatibus id atque illo dolores."
            discount: null
            environmental_impact: int ( less than 10, 3)
            id: String (UUID, "9ba5c5f5-1022-4712-8a15-97669195939e")
            labels: [ "Tuthemlberg and co." ]
            measure: "5.5"
            measuring_unit: "kg"
            name: "maxime"
            origin: "ea"
            price: "34907.93"
            stock: 1
            vat_rate: "0.45"
        }

        wanted:
        {
            id: String,
            title: String, // is name in API
            price: String,
            categories: Array, //have to make an array containing the received category
            description: String,
            image: String,
        }
    */
  let image = undefined
  if (product.image.length > 0) {
    image = product.image[0]
  }
  return {
    id: product.id,
    title: product.name,
    price: product.price.toString() + ' €',
    categories: product.categories,
    description: product.description,
    image: image
  }
}

async function makeRequestAndRecoverJSON(url, filters) {
  const result = await axios.get(url, { params: filters })
  const json = result.data
  return json
}
function getProductUrl(id) {
  function removeLastChar(str) {
    return str.substring(0, str.length - 1)
  }

  let baseApi = apiURL
  baseApi = removeLastChar(baseApi)
  return baseApi + '/' + id.toString()
}

export const useProductListStore = defineStore('productList', () => {
  //const products = ref(null);
  const filters = reactive({})
  const raw_data = ref(null)

  const products = computed(() => {
    if (raw_data.value === null) {
      return []
    }
    return raw_data.value.data.map(getCorretlyFormatedObject)
  })

  async function load() {
    raw_data.value = await makeRequestAndRecoverJSON(apiURL, filters)
  }

  function changeFilter(filterName, newValue) {
    filters[filterName] = newValue
    load()
  }

  async function getSingleProduct(id) {
    const url = getProductUrl(id)
    let product = await makeRequestAndRecoverJSON(url)
    product = product.data
    return getCorretlyFormatedObject(product)
  }

  function loadOnlyOnce() {
    if (products.value == {}) {
      load()
    }
  }
  function publishComment(productId, comment) {
    const url = getProductUrl(productId) + '/comment'

    axios
      .post(url, { comment })
      .then((response) => {
        console.log('success!')
        console.log(response.data)
      })
      .catch((response) => {
        console.log('error')
        console.log(response.response)
      })
  }
  // load()
  return {
    products,
    load,
    getSingleProduct,
    filters,
    changeFilter,
    loadOnlyOnce,
    raw_data,
    publishComment
  }
})
