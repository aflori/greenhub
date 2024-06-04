import { defineStore } from 'pinia'
import axios from 'axios'

/*
 * store used to generate and store form datas
 *
 * The form stored are right now:
 * - Adress delivery
 *
 * each form countain a list of field associated with information:
 * {
 *  type: type of input
 *  values (optional): give additional values on the field (for radio button, select or other field like that)
 *  value: the value in the field
 *  name: text that should be shown in label
 * }
 */

export const useFormStore = defineStore('formStore', {
  state: () => ({
    adressDelivery: {
      firstName: {
        type: 'text',
        value: '',
        label: 'prénom',
        validationFormat: /^[\p{L}-]+$/gu,
      },
      lastName: {
        type: 'text',
        value: '',
        label: 'nom',
        validationFormat: /^(de )?[\p{L}-]+$/gu,
      },
      gender: {
        type: 'select',
        values: [
          { value: '', name: '--choisir--' },
          { value: 'M', name: 'homme' },
          { value: 'F', name: 'femme' }
        ],
        value: '',
        label: 'sexe',
        validationFormat: /^[FM]$/,
      },
      adress: {
        type: 'text',
        value: '',
        label: 'adresse',
        validationFormat: /^(\d+)([\p{L} -]+)$/gu,
      },
      city: {
        type: 'text',
        value: '',
        label: 'ville',
        validationFormat: /^[\p{L}]+$/gu,
      },
      zipCode: {
        type: 'text',
        value: '',
        label: 'code postal',
        validationFormat: /^[A-Z0-9 -]{3,}$/, // generic regex to avoid complicated one depending of country
      },
      country: {
        type: 'text',
        value: '',
        label: 'pays',
        validationFormat: /^[\p{L}-]+$/gu,
      },
      phoneNumber: {
        type: 'tel',
        value: '',
        label: 'numéro de téléphone',
        validationFormat: /^[0-9 +-]+$/,
      }
    },
    paiementDatas: {
      cardNumber: {
        type: 'text',
        value: '',
        label: 'numéro de carte',
        validationFormat: /^(\d{4} ?){4}$/,
      },
      securityCode: {
        type: 'text',
        value: '',
        label: 'code de sécurité',
        validationFormat: /^(\d{3})$/,
      },
      validationDate: {
        type: 'month',
        value: '',
        label: "date d'expiration",
        validationFormat: /^(0\d|1[0-2])\/(2[4-9]|[3-9]\d)$/,
      }
    }
  }),
  getters: {
    getFieldList: (state) => {
      return {
        "adresse": getListOfValue(state.adressDelivery),
        "paiement": getListOfValue(state.paiementDatas),
      }
    }
  },
  actions: {
    getInvalidAdressFormField() {
      return getErrors(this.adressDelivery)
    },
    getInvalidPaiementFormField() {
      return getErrors(this.paiementDatas)
    },

    async makeOrder(productStore) {
      const roadDatas = this.adressDelivery.adress.value.match(/^(\d+)\s*(.*)\s*/)
      
      const requestBody = {
        "total_amount": productStore.totalPrice,
        "facturation_adress": {
          "road_number": Number(roadDatas[1]),
          "road_name": roadDatas[2],
          "city": this.adressDelivery.city.value,
          "zip_code": this.adressDelivery.zipCode.value,
        },
        "products": getListOfProducts(productStore.listProductInCart)
      }
      const url = "http://localhost:8000/api/orders"

      axios.post(url, requestBody)

      console.log(requestBody)
    }
  }
})


function getErrors(userInput) {
  const errors = []

  const keys = Object.keys(userInput)
  for(let i=0; i<keys.length; i++) {
    const key = keys[i]
    const input = userInput[key]
    const regex = input.validationFormat
    const value = input.value
    const name = input.label

    if(!value.match(regex)) {
      errors.push(name)
    }
  }

  return errors
}

function getListOfValue(dataFields) {
  const listOfValue = []

  const keys = Object.keys(dataFields)
  for(let i=0; i<keys.length; i++) {
    const key = keys[i]
    const objectI = dataFields[key]
    const value = {
      'label': objectI.label,
      'value': objectI.value
    }
    listOfValue.push(value)
  }
  return listOfValue
}

function getListOfProducts(productList) {
  const arrayOfProduct = []

  const keys = Object.keys(productList)
  for(let i=0; i<keys.length; i++) {
    const key = keys[i]
    const product = productList[key]
    
    arrayOfProduct.push({
      "id": product.product.id,
      "quantity": product.quantity
    })
  }
  return arrayOfProduct
}