import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

const baseUrl = "http://localhost:8000/"

export const useLoggingStore = defineStore('logging', () => {
    const isLogged = ref(undefined);
    const name = ref(null);

    function setNameIfLogged() {
        const url = baseUrl + "api/user";
        axios.get(url)
            .then((datas) => {
                name.value = datas.data.pseudoname;
                isLogged.value = true;
            })
            .catch( (_error) => {
                name.value = null;
                isLogged.value = false
            });
    }

    setNameIfLogged()

    async function logIn(email, password) {

        const url = baseUrl + "login";

        await axios.post(url, {
            'email': email,
            'password': password,
        })

        isLogged.value = true;
        setNameIfLogged();
    }
    function logout(){
        const url = baseUrl + "logout";
        axios.post(url).then((_) =>{
            isLogged.value = false;
            name.value = null;
        })
    }

    async function register(datas) {
        const url = baseUrl + "register";
        await axios.post(url, {
            first_name: datas.firstName,
            last_name: datas.lastName,
            pseudoname: datas.pseudoname,
            email: datas.email,
            password: datas.password,
        });
        isLogged = true;
        setNameIfLogged();
    }
    return {isLogged, name, logIn, logout, register};
})
