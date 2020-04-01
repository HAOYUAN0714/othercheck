import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getters'
import app from './modules/app'
import settings from './modules/settings'
// import user from './modules/user'
import ball from './modules/ball'
import common from './common/common'
import betball from './modules/betball'
import m6app from './modules/m6app'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    app,
    settings,
    // user,
    ball,
    common,
    betball, // 貝博新版手機版專用
    m6app // 米樂手機板
  },
  getters
})

export default store
