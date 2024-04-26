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
        label: 'prénom'
      },
      lastName: {
        type: 'text',
        value: '',
        label: 'nom'
      },
      gender: {
        type: 'select',
        values: [
          { value: '', name: '--choisir--' },
          { value: 'M', name: 'homme' },
          { value: 'F', name: 'femme' }
        ],
        value: '',
        label: 'sexe'
      },
      adress: {
        type: 'text',
        value: '',
        label: 'adresse'
      },
      city: {
        type: 'text',
        value: '',
        label: 'ville'
      },
      zipCode: {
        type: 'text',
        value: '',
        label: 'code postal'
      },
      country: {
        type: 'text',
        value: '',
        label: 'pays'
      },
      phoneNumber: {
        type: 'tel',
        value: '',
        label: 'numéro de téléphone'
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
    isAdressFormValid: (state) => {
      const validationRules = {
        // flag u allow myself to check all letters (not only ASCCI, per exemple 'é' included)
        // flag g is for considering character of other language (such as korean)
        firstName: /^[\p{L}-]+$/gu,
        lastName: /^(de )?[\p{L}-]+$/gu,
        gender: /^[FM]$/,
        adress: /^(\d+)([\p{L} -]+)$/gu,
        city: /^[\p{L}]+$/gu,
        zipCode: /^[A-Z0-9 -]{3,}$/, // generic regex to avoid complicated one depending of country
        country: /^[\p{L}-]+$/gu,
        phoneNumber: /^[0-9 +-]+$/
      }

      return validate(state.adressDelivery, validationRules)
    }
  },
  actions: {}
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
