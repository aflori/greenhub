import { defineStore } from 'pinia';
import axios from 'axios';
import { ref } from 'vue';

const baseUrl = "http://localhost:8000/"

export const useLoggingStore = defineStore('logging', () => {
    const isLogged = ref(undefined);
    const name = ref("");

    function setName() {
        const url = baseUrl + "api/user";
        axios.get(url)
            .then((datas) => {
                console.log(datas);
            })
            .catch( (error) => {
                name.value = undefined;
                console.log(error);
            });
    }
    function initLoggedData() {
        const testIfIsLogUrl = baseUrl + "api/user";
        axios.get(testIfIsLogUrl).then((_)=>{
            isLogged.value = true;
            setName();
        }).catch((_)=> {
            isLogged.value = false;
        })
    }

    initLoggedData()

    async function logIn(email, password) {

        const url = baseUrl + "login";

        await axios.post(url, {
            'email': email,
            'password': password,
        })

        isLogged.value = true;
        setName();
    }
    function logout(){
        const url = baseUrl + "logout";
        axios.post(url).then((_) =>{
            isLogged.value = false;
        })
    }
    return {isLogged, name, logIn, logout};
})
