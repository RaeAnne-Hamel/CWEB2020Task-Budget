//import libraries
import Vue from 'vue'
import {BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
//import 'bootswatch/dist/sketchy/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import axios from 'axios';
import VueAxios from 'vue-axios';

//add libraries to vue context
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueAxios, axios);

//import components from src folder
import App from './App.vue';
import router from './router'; //declared and exported in the index.js file

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
