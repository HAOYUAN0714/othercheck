import VueCookie from "vue-cookie";
var timer;
const common = {
    $_changeLang(state, payload) {
        state.common.lang = payload.lang;
        payload.i18n.locale = payload.lang;
        VueCookie.set("abs-lang", payload.lang);
    },
    $_commonLoadingMask(state, flag) {
        if (flag) {
            state.common.loading.push(1337)
        } else {
            state.common.loading.pop();
        }
    },
    $_selectChatRoom(state, event) {
        state.status.chatroom = event;
    },

    $_commonHintMsg(state, obj) {
        state.common.hint.flag = true;
        state.common.hint.type = obj.type;
        state.common.hint.message = obj.message;
        var self = state;
        clearTimeout(timer)
        timer = setTimeout(function () {
            self.common.hint.flag = false
        }, 3000);
    },

}
const tableStatus = {
    $_setIsCombo(state, flag) {
        state.status.isCombo = flag
    },
    $_changeMarket(state, marketId) {
        state.status.Market = marketId
    },

    $_changeSelectedSport(state, sportId) {
        state.status.selectedSport = sportId
    },

    $_changeSelectedBetType(state, betType) {
        state.oddsTable.selectedBetType = betType
    },

    $_setOddsType(state, id) {
        state.status.selectedOddsType = id;
    },
    $_setListCount(state, obj) {
        state.navList = {
            ...state.navList,
        }
        state.navList[obj.key] = {}
        state.navList[obj.key] = obj.list
    },
    $_setMorePlayEvent(state, obj) {
        state.oddsTable.morePlayEvent = {};
        state.oddsTable.morePlayEvent = obj
    },
    $_clearMorePlayEvent(state) {
        state.oddsTable.morePlayEvent = {};
    }
}

const eventData = {
    $_setPlayListIds(state, Ids) {
        state.status.selectedPlayListIds = Ids
    },
    $_setPlayList(state, obj) {
        state.status.selectedPlayList[obj.sportId] = obj.data;
    },
    $_setSelectedPlay(state, obj) {
        state.status.selectedPlay = obj
    },
    $_setLivePlay(state, data) {
        state.status.selectedLivePlay = data
    },
    $_setLiveSports(state, data) {
        state.status.liveSports = data;
    }
}
const wager = {
    $_setWager(state, wager) {
        if (state.wager.betPlaced == true)
            state.wager.betPlaced = false;
        state.wager.wager = wager;
    },
    $_setWagerList(state, wagerList) {
        if (state.wager.betPlaced == true)
            state.wager.betPlaced = false;
        this.commit('$_clearWagerList') //這樣vue才能偵測異動==垃圾vue
        state.wager.wagerList = wagerList;
    },
    $_removeWagerFromList(state, wager) {
        if (state.wager.betPlaced == true)
            state.wager.betPlaced = false;

        let collision = wager.EventGroupId == 0 ? "EventId" : "EventGroupId";
        let existedWager = state.wager.wagerList.find(ele =>
            ele[collision] == wager[collision]
        )
        let index = state.wager.wagerList.indexOf(existedWager)
        if (index > -1)
            state.wager.wagerList.splice(index, 1)
        if (state.wager.wagerList.length == 0) {
            this.commit("$_setPlacingBet", false)
        }
    },
    $_clearWager(state) {
        state.wager.wager = {}
        this.commit("$_setPlacingBet", false)
    },
    $_setBetPlaced(state, flag) {
        state.wager.betPlaced = flag
    },
    $_clearWagerList(state) {
        state.wager.wagerList = [];
    },
    $_setPlacingBet(state, flag) {
        state.wager.placingBet = flag;
    },
    $_setUnsettledBet(state, arr) {
        state.betList.unsettledBetList = arr;
    },
    $_setSettledBet(state, arr) {
        state.betList.settledBetList = arr;
    },
    $_setAnnouncements(state, arr) {
        state.common.announcements = arr;
    },
    $_setComboBetSetting(state, obj) {
        state.wager.comboBetSetting = {};
        state.wager.comboBetSetting = obj;
    },
    $_clearBetSetting(state) {
        state.wager.comboBetSetting = {};
    }
}
export default {
    ...common,
    ...wager,
    ...eventData,
    ...tableStatus
}
