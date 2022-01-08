import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import apiURL from './config/config.js'

const app = createApp(App)
app.use(router)
app.use(apiURL)
app.use(VueAxios, axios)
app.mount('#app')