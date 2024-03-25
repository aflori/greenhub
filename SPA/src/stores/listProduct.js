import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';

const apiURL = "https://fakestoreapi.com/products";

function getCorretlyFormatedObject(product) {
    /*
        received:
        {
            id: Number,
            title: String,
            price: Number,
            description: String,
            category: String,
            image: String,
            rating: Object { rate: Number, count: Number }
        }

        wanted:
        {
            id: Number,
            title: String,
            price: String, //have to convert price from number to string with its currency (€)
            categories: Array, //have to make an array containing the received category
            description: String,
            image: String,
        }
    */
    return {
        id: product.id,
        title: product.title,
        price: product.price.toString() + " €",
        categories: [ product.category ],
        description: product.description,
        image: product.image,
    }
}

function changeToWantedFormat(requestResult) {

    function addElement(baseStructure, newElement) {
        baseStructure[newElement.id] = newElement;
    }

    const listProductCorrectlyFormated = {};

    for(let i = 0; i<requestResult.length; i++) {
        addElement(
            listProductCorrectlyFormated,
            getCorretlyFormatedObject(requestResult[i])
        );
    }

    return listProductCorrectlyFormated
}

async function makeRequestAndRecoverJSON(url) {

    const result = await axios.get(url);
    const json = result.data;
    return json;

}
function getProductUrl(id) {
    return apiURL + "/" + id.toString();
}

export const useProductListStore = defineStore('productList', () => {

    const products = ref({});

    async function load() {
        const productList = await makeRequestAndRecoverJSON(apiURL)

        products.value = changeToWantedFormat(productList);
    };

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
            const product = await makeRequestAndRecoverJSON(url);

            return getCorretlyFormatedObject(product);
        }

        return products.value[id];
    }

    return { products, load, getProducts, getSingleProduct };
})