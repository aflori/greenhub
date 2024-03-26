import { defineStore } from 'pinia';
import axios from 'axios';

const baseUrl = "http://127.0.0.1:8000/"

export const useLoggingStore = defineStore('logging', {
    state: () => ({
        isLogged: false,
        hasSession: false,
    }),
    getters: {

    },
    actions: {
        getSession() {
            const url = baseUrl + "sanctum/csrf-cookie"
            axios.get(url).then( response => {
            });
        },

        logIn(email, password) {
            if(! this.hasSession) {
                this.getSession()
            }
        }
    }
});
