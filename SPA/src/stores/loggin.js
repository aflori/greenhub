import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

const baseUrl = "http://localhost:8000/"

export const useLoggingStore = defineStore('logging', () => {
    const isLogged = ref(false);
    const name = ref("");
    const hasSession = ref(false);

    async function getSession() {
        const url = baseUrl + "sanctum/csrf-cookie";
        await axios.get(url);
        hasSession.value = true;
    }

    async function logIn(email, password) {
        if(! hasSession.value ){
            await getSession()
        }

        const url = baseUrl + "login";

        await axios.post(url, {
            'email': email,
            'password': password,
        })

        name.value = email;
        isLogged.value = true;
    }
    function logout(){
        const url = baseUrl + "logout";
        axios.post(url).then((_) =>{
            isLogged.value = false;
        })
    }
    return {isLogged, name, getSession, logIn, logout};
})
