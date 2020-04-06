import Vue from "vue";
import bbBet from '../bbBet.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import i18n from "../config/i18n";
Vue.use(VueAxios, axios);

new Vue({
    i18n,
    el: "#app",
    template: "<Index/>",
    render: h => h(bbBet),
}).$mount('#app')
