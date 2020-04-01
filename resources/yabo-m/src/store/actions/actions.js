import axios from 'axios'
import VueAxios from 'vue-axios'
import Vue from 'vue'
import state from '../index'
Vue.use(VueAxios, axios)

const actions = {
  actionCommonLoading(store, flag) {
    (store.state.common.loading = flag)
  }
}
export default actions
