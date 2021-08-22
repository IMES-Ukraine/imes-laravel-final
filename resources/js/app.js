/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueRouter from 'vue-router'
import router from './router'
import VueAxios from 'vue-axios'
import { get, post } from './utils/axios'
import Vuelidate from 'vuelidate'
import './utils/vee-validate'
import store from './store'


// Set router
Vue.router = router;
Vue.use(VueRouter);

// Set Axios
Vue.use(VueAxios, axios);
Vue.prototype.$get = get
Vue.prototype.$post = post

// Set Vuex
Vue.use(Vuex)

// Set Vuelidate
Vue.use(Vuelidate)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('v-app', require('./components/App.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.config.productionTip = false

new Vue({
    el: '#app',
    router,
    store
});
