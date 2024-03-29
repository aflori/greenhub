import { ref, reactive } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';

const apiURL = "http://127.0.0.1:8000/api/products"

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
    let image = undefined;
    if (product.image.length > 0) {
        image = product.image[0];
    }
    return {
        id: product.id,
        title: product.name,
        price: product.price.toString() + " €",
        categories: product.categories,
        description: product.description,
        image: image,
    }
}

function changeToWantedFormat(requestResult) {

    function addElement(baseStructure, newElement) {
        baseStructure[newElement.id] = newElement;
    }

    const listProductCorrectlyFormated = {};
    const data = requestResult.data;
    for(let i = 0; i<data.length; i++) {
        addElement(
            listProductCorrectlyFormated,
            getCorretlyFormatedObject(data[i])
        );
    }

    return listProductCorrectlyFormated
}

async function makeRequestAndRecoverJSON(url, filters) {

    const result = await axios.get(url, { params: filters});
    const json = result.data;
    return json;

}
function getProductUrl(id) {
    function removeLastChar(str) {
        return str.substring(0, str.length - 1);
    }

    let baseApi = apiURL;
    baseApi = removeLastChar(baseApi);
    return baseApi + "/" + id.toString();
}

export const useProductListStore = defineStore('productList', () => {

    const products = ref({});
    const filters = reactive({});

    async function load() {
        const productList = await makeRequestAndRecoverJSON(apiURL, filters)

        products.value = changeToWantedFormat(productList);
    };

    function changeFilter(filterName, newValue) {
        filters[filterName] = newValue;
        load();
    }

    function getProducts() {
        function objectIsEmpty(o) {
            return Object.keys(o).length === 0
        }


        if(objectIsEmpty(products.value)) {
            load();
        }

        return products;
    };

    async function getSingleProduct(id) {

        function productIsntAlreadyStored(product) {
            return product === undefined;
        }

        let product = products.value[id];

        if( productIsntAlreadyStored(product) ) {
            const url = getProductUrl(id);
            let product = await makeRequestAndRecoverJSON(url);
            product = product.data
            return getCorretlyFormatedObject(product);
        }

        return products.value[id];
    }

    return { products, load, getProducts, getSingleProduct, filters, changeFilter };
})
