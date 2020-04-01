import VueCookie from "vue-cookie";
import axios from 'axios'
import VueAxios from 'vue-axios'
import Vue from 'vue'
Vue.use(VueAxios, axios)

const common = {
    $_changeLang(store, payload) {
        store.state.common.lang = payload.lang;
        payload.i18n.locale = payload.lang;
        VueCookie.set("abs-lang", payload.lang);
    },
    apiGetBalance(store) {
        return new Promise((resolve, reject) => {

            axios.post('/exsport/api/get-balance', {
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
            }).then(res => {
                store.state.common.availableBalance = res.data.AvailableBalance
                store.state.common.outstandingBalance = res.data.OutstandingBalance
                return resolve(res)
            }).catch(err => {
                return reject(err)
            })

        })

    },
    $_getQrCode(store, payload) {
        axios.post('/api/v1/c/slide/qrcode', {
            color: payload.color,
            background_color: "#ffffff",
            width: 105,
            path: "mobileBet",
            cid: VueCookie.get("cid")
        }).then(res => {
            if (res.status == 200) {
                store.state.common.qrCode = res.data.ret.qrcode;
            }
        })
    },
    $_getUserInfo(store) {
        axios.get('/api/v1/c/player').then(res => {
            if (res.status == 200) {
                store.state.common.user = res.data.ret.user.username
            }
        })
    }
}

const api = {
    //取得運動列表
    $_getAllSportCount(store, payload) {
        axios.post('/exsport/api/get-all-sport-count', {
            LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG',
            ...payload
        }).then(res => {}).catch(err => {})
    },
    //取得跑馬燈
    $_getAnnouncement(store) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-announcement', {}).then(res => {
                return resolve(res);
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    //取得跑馬燈
    apiGetAnnouncement(store) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-announcement', {}).then(res => {
                return resolve(res);
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    //取得滾球賽事
    $_getLiveEventInfo(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-live-event-info', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res);
            }).catch(err => {
                return reject(err)
            })
        })
    },
    //取得滾球賽事
    apiGetLiveEventInfo(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-live-event-info', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res);
            }).catch(err => {
                return reject(err)
            })
        })
    },
    $_getEventInfo(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-event-info', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    apiGetEventInfoByPage(store, payload) {
        if (payload.loadingMask)
            store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-event-info-by-page', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    $_getEventInfoAllMarket(store, payload) {
        if (payload.loadingMask)
            store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-event-info-all-market', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    apiGetEventInfoAllMarket(store, payload) {
        if (payload.loadingMask)
            store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-event-info-all-market', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    $_getListCount(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-bettype-count', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },

    apiGetSelectedEventInfo(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-selected-event-info', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    apiGetSelectedEventInfo(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-selected-event-info', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },

    apiGetCompletedResults(store, payload) {
        store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-completed-results', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    //取得所有聯盟計數
    $_getAllCompetitionCount(store, payload) {
        store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-all-competition-count', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    apiPlaceBet(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/place-bet', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit("$_commonLoadingMask", false);
                // let data = res.data.Sports[0];
                // store.state.oddsTable[data.SportId] = data;
                return resolve(res)
            }).catch(err => {
                store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
        //目前沒有token無從得知
    },
    $_extendSession(store) {
        axios.post('/exsport/api/extend-session', {
            Token: VueCookie.get('abs-token'),
        }).then(res => {

        }).catch(err => {

        })
    },
    apiGetBetInfo(store, payload) {
        return new Promise((resolve, reject) => {
            store.state.wager.gettinBetInfo = true;
            axios.post('/exsport/api/get-bet-info', {
                ...payload,
                //    MemberCode: VueCookie.get('abs-mem'),
                //    Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'en' ? 'ENG' : 'CHS'
            }).then(res => {
                store.state.wager.gettinBetInfo = false;
                return resolve(res)
            }).catch(err => {
                store.state.wager.gettinBetInfo = false;
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    $_getPendingWagers(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-pending-wager-status', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'en' ? 'ENG' : 'CHS'
            }).then(res => {
                store.commit("$_commonLoadingMask", false);
                // let data = res.data.Sports[0];
                // store.state.oddsTable[data.SportId] = data;
                return resolve(res)
            }).catch(err => {
                store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
        //目前沒有token無從得知
    }
}
const wager = {
    //關於著單
    apiGetBetList(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-bet-list', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit("$_setUnsettledBet", res.data.WagerList);
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },

    apiGetSettled(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-statement', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    }
}
const event = {
    $_addFavoriteEvent(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/add-favourite-event', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    $_getFavoriteEvent(store, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-favourite-event', {
                ...payload,
                MemberCode: VueCookie.get('abs-mem'),
                Token: VueCookie.get('abs-token'),
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit('$_setFavoriteEvents', res)
                return resolve(res)
            }).catch(err => {
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
}
const champion = {
    $_getChampions(store, payload) {
        if (payload.loadingMask)
            store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-outright-events', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    $_getChampionCompetitionList(store, payload) {
        store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-outright-event-list', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
    apiGetFilteredChampions(store, payload) {
        if (payload.loadingMask)
            store.commit("$_commonLoadingMask", true);
        return new Promise((resolve, reject) => {
            axios.post('/exsport/api/get-league-outright-event', {
                ...payload,
                LanguageCode: store.state.common.lang == 'CHS' ? 'CHS' : 'ENG'
            }).then(res => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                return resolve(res)
            }).catch(err => {
                if (payload.loadingMask)
                    store.commit("$_commonLoadingMask", false);
                store.commit('$_commonHintMsg', {
                    type: 'error',
                    message: "TEXT_API_ERROR"
                })
                return reject(err)
            })
        })
    },
}
export default {
    ...common,
    ...api,
    ...wager,
    ...event,
    ...champion
}
