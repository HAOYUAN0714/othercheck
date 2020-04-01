import Vue from 'vue';
import VueI18n from 'vue-i18n';
import VueCookie from 'vue-cookie'
import store from '@/store'
import en from './lang/en'
import tw from './lang/tw'
import cn from './lang/cn'
import vn from './lang/vn'
Vue.use(VueI18n);
// 從cookie取語系, 若沒有的話預設繁體中文
var lang = VueCookie.get('abs-lang') || 'CHS';
store.lang = lang;
const i18n = new VueI18n({
    locale: lang,
    messages: {
        ENG: {
            ...en
        },
        TW: {
            ...tw
        },
        CHS: {
            ...cn
        },
        VN: {
            ...vn
        }
    },
    mutations: {
        SET_LOCALE(state, lang) {
            state.common.lang = lang
        }
    }
});

export default i18n;
