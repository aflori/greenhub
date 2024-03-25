import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';

const apiURL = "http://localhost:8000/api/products"

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
    return {
        id: product.id,
        title: product.name,
        price: product.price.toString() + " €",
        categories: [ product.category ],
        description: product.description,
        image: "",
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
