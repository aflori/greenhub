import { defineStore } from 'pinia';

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
                type: "text",
                value: null,
                name: "prénom",
            },
            lastName: {
                type: "text",
                value: null,
                name: "nom",
            },
            gender: {
                type: "radio",
                values: [ ['N', "--choisir--"], ['M', "homme"], [ 'F', "femme" ] ],
                value: null,
                name: "sexe"
            },
            adress: {
                type: "text",
                value: null,
                name: "adresse postal",
            },
            city: {
                type: "text",
                value: null,
                name: "ville",
            },
            zipCode: {
                type: "text",
                value: null,
                name: "code postal",
            },
            country: {
                type: "text",
                value: null,
                name: "pays",
            },
            phoneNumber: {
                type: "text",
                value: null,
                name: "numéro de téléphone",
            },
        }
    }),
    getters: {
    },
    actions: {
    }
});
