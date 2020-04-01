import VueCookie from 'vue-cookie'

if (!(VueCookie.get('lang'))) {
  let lang
  if (navigator.languages && navigator.languages.length) {
    // latest versions of Chrome and Firefox set this correctly
    [lang] = navigator.languages
  } else if (navigator.userLanguage) {
    // IE only
    lang = navigator.userLanguage
  } else {
    // latest versions of Chrome, Firefox, and Safari set this correctly
    lang = navigator.language
  }

  lang = lang.toLowerCase()

  switch (true) {
    case /^en-/.test(lang):
      lang = 'en'
      break
    case /^ja$|^ja-/.test(lang):
      lang = 'ja'
      break
    case /^zh-tw$|^zh-hk$/.test(lang):
      lang = 'zh-cn'
      break
    case /^vi$|^vi$/.test(lang):
      lang = 'vi'
      break
    default:
      lang = 'zh-cn'
      break
  }

  VueCookie.set('lang', lang, { path: '/' })
}

export default VueCookie.get('lang')
