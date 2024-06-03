import { defineStore } from 'pinia'

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
        label: 'numéro de carte'
      },
      securityCode: {
        type: 'text',
        value: '',
        label: 'code de sécurité'
      },
      validationDate: {
        type: 'month',
        value: '',
        label: "date d'expiration"
      }
    }
  }),
  getters: {
    isPaiementFormValid: (state) => {
      const validationRules = {
        cardNumber: /^(\d{4} ?){4}$/,
        securityCode: /^(\d{3})$/,
        validationDate: /^(0\d|1[0-2])\/(2[4-9]|[3-9]\d)$/
      }
      return validate(state.paiementDatas, validationRules)
    },
  },
  actions: {
    getInvalidAdressFormField() {
      return getErrors(this.adressDelivery)
    }
  }
})

function validate(input, validationRules) {
  for (const fieldName in validationRules) {
    //I want the value and not the ref proxy, so i use the .value
    const valueTested = input[fieldName].value
    const regExpr = validationRules[fieldName]

    if (!valueTested.match(regExpr)) {
      return false
    }
  }

  return true
}
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