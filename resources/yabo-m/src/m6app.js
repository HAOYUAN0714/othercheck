import Vue from 'vue'
import App from './App/m6app'
import store from './store'
import router from './router/m6app'
import ElementUI from 'element-ui'
import VueClipboard from 'vue-clipboard2'
import 'element-ui/lib/theme-chalk/index.css'

import i18n from './utils/i18n'
import fontawesome from '@fortawesome/fontawesome'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import solid from '@fortawesome/fontawesome-free-solid'
import '@/styles/index.scss' // global css
import 'element-ui/lib/theme-chalk/index.css'
import 'vue-material-design-icons/styles.css'
import globalFunc from './config/globalfunc'
import VueCookie from 'vue-cookie'
fontawesome.library.add(solid)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false
Vue.use(ElementUI)
Vue.use(VueClipboard)
Vue.mixin(globalFunc)
Vue.use(VueCookie)

new Vue({
  el: '#app',
  router,
  store,
  i18n,
  render: h => h(App)
})
