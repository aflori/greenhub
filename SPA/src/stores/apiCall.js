import { defineStore } from 'pinia';


export const useLoggingStore = defineStore('logging', {
    state: () => ({
        isLogged: false,
    }),
    getters: {

    },
    actions: {
        getSession() {
            axios.get("/sanctum/csrf-cookie").then( response => {
                // something
            });
        }
    }
});
