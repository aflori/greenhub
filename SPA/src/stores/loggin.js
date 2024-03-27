import { defineStore } from 'pinia';
import axios from 'axios';

const baseUrl = "http://localhost:8000/"

export const useLoggingStore = defineStore('logging', {
    state: () => ({
        isLogged: false,
        name: "",
        hasSession: false,
    }),
    getters: {
    },
    actions: {
        async getSession() {
            const url = baseUrl + "sanctum/csrf-cookie";
            await axios.get(url);
            this.hasSession = true;
        },

        async logIn(email, password) {
            if(! this.hasSession ){
                await this.getSession()
            }

            const url = baseUrl + "login";

            await axios.post(url, {
                'email': email,
                'password': password,
            })

            this.name = email;
            this.isLogged = true;

        }
    }
});
