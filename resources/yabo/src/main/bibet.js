import Vue from "vue";
import router from '../config/router'
import store from '../store/index';
import Vuex from "vuex";
import VueCookie from "vue-cookie";
import Element from "element-ui";
import VueClosable from 'vue-closable'
import i18n from "../config/i18n";
import "element-ui/lib/theme-chalk/index.css";
import 'vue-material-design-icons/styles.css';
import '@/scss/common/common.scss'
import globalFunc from '@/module/globalFunc'
import App from '../App.vue'
import Flatpickr from "flatpickr";
import {
    Mandarin
} from "flatpickr/dist/l10n/zh.js";

import '@/scss/common/bibet.scss'
import bibetCss from '../config/cssVars/bibetCss';
Flatpickr.localize(Mandarin);
//import socket from './config/socket'
import token from '../config/token'
// 使用套件
require("flatpickr/dist/themes/material_orange.css");
Vue.mixin(globalFunc)
Vue.use(Vuex);
Vue.use(VueCookie);
Vue.use(VueClosable)
Vue.use(Element);
Vue.use(router);
//Vue.use(socket)
new Vue({
    i18n,
    el: "#app",
    router,
    store,
    template: "<Index/>",
    render: h => h(App),
}).$mount('#app')
