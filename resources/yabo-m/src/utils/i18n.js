import Vue from 'vue'
import VueI18n from 'vue-i18n'
import VueCookie from 'vue-cookie'
import mappingCode from './mappingLang'

Vue.use(VueI18n)

const regex = /[^&=?]+=[^&#]*/g
const target = location.href.match(regex)
const tran = {
  LanguageCode: 'abs-lang'
}
let lang = ''
if (target) {
  target.forEach(val => {
    const [key, value] = val.split('=')
    if (tran[key]) {
      lang = value
    }
  })
}

function loadLocaleMessages() {
  const locales = require.context('../locales', true, /[A-Za-z0-9-_,\s]+\.json$/i)
  const messages = {}
  locales.keys().forEach((key) => {
    const matched = key.match(/([A-Za-z0-9-_]+)\./i)
    if (matched && matched.length > 1) {
      const locale = matched[1]
      messages[locale] = locales(key)
    }
  })
  return messages
}

export default new VueI18n({
  locale: mappingCode[VueCookie.get('abs-lang')] || mappingCode[lang] || process.env.VUE_APP_I18N_LOCALE || 'zh-cn',
  fallbackLocale: process.env.VUE_APP_I18N_FALLBACK_LOCALE,
  messages: loadLocaleMessages(),
  silentTranslationWarn: false // 洗版的時候先改成true
})
