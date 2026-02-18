/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// import excel from 'vue-excel-export'
import { BootstrapVue } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap-vue/dist/bootstrap-vue-icons.css'
import CKEditor from "@ckeditor/ckeditor5-vue2";

import router from './router';
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'
import VueApexCharts from 'vue-apexcharts'

import Vue from 'vue'

Vue.use(BootstrapVue);
Vue.use(VueApexCharts);
Vue.use(CKEditor);

// Vue.use(excel);
Vue.mixin(require('./trans'));
window.Vue = require('vue').default;
Vue.use(LaravelPermissionToVueJS);
// import route from 'zi';
window.getRout = function(name, param = '') {
    if (param)
        return route(name, param);
    return route(name);
};

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/DashboardComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/DashboardComponent.vue').default);
Vue.component('main-component', require('./components/App.vue').default);
Vue.component('apexchart', VueApexCharts)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
});
