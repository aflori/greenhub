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
                value: "",
                label: "prénom",
            },
            lastName: {
                type: "text",
                value: "",
                label: "nom",
            },
            gender: {
                type: "select",
                values: [ {value:'', name:"--choisir--"}, {value:'M', name:"homme"}, {value:'F', name:"femme"} ],
                value: "N",
                label: "sexe"
            },
            adress: {
                type: "text",
                value: "",
                label: "adresse postal",
            },
            city: {
                type: "text",
                value: "",
                label: "ville",
            },
            zipCode: {
                type: "text",
                value: "",
                label: "code postal",
            },
            country: {
                type: "text",
                value: "",
                label: "pays",
            },
            phoneNumber: {
                type: "text",
                value: "",
                label: "numéro de téléphone",
            },
        }
    }),
    getters: {
    },
    actions: {
    }
});
