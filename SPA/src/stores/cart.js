import { ref, computed } from 'vue';
import { defineStore } from 'pinia';

/*
for reference, product object has to be on the form of
{
    id: Number,
    title: String,
    price: String, // format "xx.xx €" --- need to remove the 2 last characters
    categories: Array,
    description: String,
    image: String,
}
*/
export const useCartStore = defineStore('cart', {
    state: () => ({
        /*
         * I choose to use an object to use a map (of id) instead of a list
         * an entry must countains
         * {
         *    'product': Product Object,
         *    'quantity': Number,
         *    'unitPrice': Number,
         *    'totalPrice': Number,
         * }
        */
        listProductInCart: {},
        totalPrice: 0
    }),
    getters: {
    },
    actions: {
        addProduct(product, quantity) {
            function productAlreadyInCart(product, cart) {
                return cart[product.id] != undefined
            }

            if(productAlreadyInCart(product, this.listProductInCart)) {
                const cartEntry = this.listProductInCart[product.id];
                cartEntry.quantity += quantity;
                cartEntry.totalPrice = cartEntry.unitPrice * cartEntry.quantity;

                this.totalPrice += quantity * cartEntry.unitPrice;
            }
            else {

                function getUnitPrice(price) {
                    function getPriceInString(price) {
                        return price.slice(0,-2);
                    }

                    const unitPriceString = getPriceInString(product.price); //remove currency symbole
                    return Number(unitPriceString); //number conversion
                }

                const unitPrice = getUnitPrice(product.price);
                const cartEntry = {
                    'product': product,
                    'quantity': quantity,
                    'unitPrice': unitPrice,
                    'totalPrice': unitPrice * quantity,
                }

                //add entry into our backet
                this.listProductInCart[product.id] = cartEntry;
                this.totalPrice += cartEntry.totalPrice;
            }
        },

        modifyQuantityOf(productId, newQuantity) {
            const productData = this.listProductInCart[productId];

            //product does not exist
            if (productData === undefined ) return ;

            //special case where we remove the entry
            if (newQuantity <= 0) {
                delete this.listProductInCart[productId];
                return ;
            }

            const newProductTotalPrice = newQuantity * productData.unitPrice;
            const priceDifference = productData.totalPrice - newProductTotalPrice;

            productData.quantity = newQuantity;
            productData.totalPrice = newProductTotalPrice;
            this.totalPrice = this.totalPrice - priceDifference;
        },

        removeFromBacket(productId) {
            if( this.listProductInCart[productId] === undefined ) return;


            const totalPriceProduct = this.listProductInCart[productId].totalPrice;
            delete this.listProductInCart[productId];

            this.totalPrice -= totalPriceProduct;
        }
    }
});
