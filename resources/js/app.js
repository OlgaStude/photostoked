import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

import axios from 'axios'
import App from './App.vue';
import router from './router'

const app = createApp(App)
app.config.globalProperties.$axios = axios
app.use(router)
app.mount('#app')