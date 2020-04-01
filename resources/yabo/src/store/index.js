import Vue from 'vue';
import Vuex from 'vuex';
import state from './state/state'
import actions from './action';
import mutations from './mutation';


Vue.use(Vuex);
export default new Vuex.Store({
    state,
    mutations,
    actions
});
