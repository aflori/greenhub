import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true
axios.defaults.headers.common['Accept'] = 'application/json'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
