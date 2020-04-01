const state = {
  navHeader: 'live',
  curSportId: '',
  totalGameList: [],
  isLoading: false,
  isWidgetLoading: false,
  sportCount: {
    early: {}, // 目前不顯示
    today: {}, // 合併早餐 + 今日
    live: {}
  },
  moreGameInfo: {},
  preferPlayGroup: 1
}

const getters = {
  getNavHeader: state => state.navHeader,
  getCurSportId: state => state.curSportId,
  getTotalGameList: state => state.totalGameList,
  getIsLoading: state => state.isLoading,
  getIsWidgetLoading: state => state.isWidgetLoading,
  getSportCount: state => state.sportCount,
  getMoreGameInfo: state => state.moreGameInfo,
  getPreferPlayGroup: state => state.preferPlayGroup
}

const mutations = {
  setNavHeader(state, val) {
    state.navHeader = val
  },
  setCurSportId(state, val) {
    state.curSportId = val
  },
  setTotalGameList(state, val) {
    state.totalGameList = val
  },
  setIsLoading(state, val) {
    state.isLoading = val
  },
  setSportCount(state, val) {
    state.sportCount = val
  },
  setMoreGameInfo(state, val) {
    state.moreGameInfo = val
  },
  setPreferPlayGroup(state, val) {
    state.preferPlayGroup = val
  },
  setIsWidgetLoading(state, val) {
    state.isWidgetLoading = val
  }
}

const actions = {
  actionSetNavHeader({ commit }, val) {
    commit('setNavHeader', val)
  },
  actionSetCurSportId({ commit }, val) {
    commit('setCurSportId', val)
  },
  actionSetTotalGameList({ commit }, val) {
    commit('setTotalGameList', val)
  },
  actionSetIsLoading({ commit }, val) {
    commit('setIsLoading', val)
  },
  actionSetSportCount({ commit }, val) {
    commit('setSportCount', val)
  },
  actionSetMoreGameInfo({ commit }, val) {
    commit('setMoreGameInfo', val)
  },
  actionSetPreferPlayGroup({ commit }, val) {
    commit('setPreferPlayGroup', val)
  },
  actionSetIsWidgetLoading({ commit }, val) {
    commit('setIsWidgetLoading', val)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
