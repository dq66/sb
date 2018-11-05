/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import router from "./router";
import "./reset.css"

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import axios from "axios";
import VueAxios from "vue-axios";

import VuetifyToast from "vuetify-toast-snackbar";

import mavonEditor from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'

Vue.use(Vuetify)

Vue.use(mavonEditor)

Vue.use(VueAxios, axios);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VuetifyToast, {
    x: "right",
    y: "top",
    color: "info",
    icon: "info",
    timeout: 3000,
    dismissable: true,
    autoHeight: false,
    multiLine: false,
    vertical: false,
    shorts: {
        custom: {
            color: "purple"
        }
    },
    property: "$Message"
});
//绑定到全局
Vue.prototype.$Api = require('./api/index.js');

Vue.router = router;

Vue.use(require("@websanova/vue-auth"), {
    auth: require("@websanova/vue-auth/drivers/auth/bearer.js"),
    http: require("@websanova/vue-auth/drivers/http/axios.1.x.js"),
    router: require("@websanova/vue-auth/drivers/router/vue-router.2.x.js"),
});
//axios配置全局
// 基础url前缀
// axios.defaults.baseURL = "http://192.168.0.167:1234/api/";
axios.defaults.baseURL = "http://127.0.0.1:8000/api/";
// 请求头信息
axios.defaults.headers = {
    "X-Requested-With": "XMLHttpRequest"
};
//设置超时时间
axios.defaults.timeout = 10000;

new Vue({
    el: '#app',
    router,
    created: function () {
        document.body.removeChild(document.getElementById("loader-wrapper"));
    }
});
