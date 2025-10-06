import { createApp } from 'vue/dist/vue.esm-bundler.js';
import axios from 'axios';
import VueAxios from 'vue-axios';
import TaskList from './components/TaskList.vue';
import 'bootstrap/dist/css/bootstrap.min.css';

const app = createApp({});

app.use(VueAxios, axios);
app.component('task-list', TaskList)
app.mount('#app');