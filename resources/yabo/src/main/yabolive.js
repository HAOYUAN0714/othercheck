import Vue from "vue";
import i18n from "../config/i18n";
import yabolive from '../yabolive.vue'
import Flatpickr from "flatpickr";
import Element from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import {
    Mandarin
} from "flatpickr/dist/l10n/zh.js";
import VueDraggableResizable from 'vue-draggable-resizable'

// optionally import default styles
import 'vue-draggable-resizable/dist/VueDraggableResizable.css'

Vue.use(Element);
Vue.component('vue-draggable-resizable', VueDraggableResizable)
require('sweetalert2/dist/sweetalert2.min.css');
Flatpickr.localize(Mandarin);
// 使用套件
require("flatpickr/dist/themes/material_orange.css");
//Vue.use(socket)
new Vue({
    i18n,
    el: "#app",
    template: "<Index/>",
    render: h => h(yabolive),
}).$mount('#app')
