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
                value: "",
                label: "sexe"
            },
            adress: {
                type: "text",
                value: "",
                label: "adresse",
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
        isAdressFormValid: (state) => {
            const validations = {
                // flag u allow myself to check all letters (not only ASCCI, per exemple 'é' included)
                // flag g is for considering character of other language (such as korean)
                firstName:   /^[\p{L}-]+$/ug,
                lastName:    /^(de )?[\p{L}-]+$/ug,
                gender:      /^[FM]$/,
                adress:      /^(\d+)([\p{L} -]+)$/ug,
                city:        /^[\p{L}]+$/ug,
                zipCode:     /^[A-Z0-9 \-]{3,}$/, // generic regex to avoid complicated one depending of country
                country:     /^[\p{L}-]+$/ug,
                phoneNumber: /^[0-9 \+\-]+$/,
            }

            return validate(state.adressDelivery, validations);
        }
    },
    actions: {
    }
});

function validate(input, validationRules) {
    for (const fieldName in validationRules) {

        //I want the value and not the ref proxy, so i use the .value
        const valueTested = input[fieldName].value;
        const regExpr = validationRules[fieldName];

        if (!valueTested.match(regExpr)) {
            return false;
        }
    }

    return true;

}
