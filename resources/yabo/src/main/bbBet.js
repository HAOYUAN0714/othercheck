import Vue from "vue";
import bbBet from '../bbBet.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios);

new Vue({
    el: "#app",
    template: "<Index/>",
    render: h => h(bbBet),
}).$mount('#app')
