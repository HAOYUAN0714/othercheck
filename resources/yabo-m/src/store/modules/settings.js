import defaultSettings from '@/settings'

const { showSettings, fixedHeader, sidebarLogo } = defaultSettings

const state = {
  showSettings: showSettings,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  pageLoading: {
    lang: 'vn',
    loading: 0
  }
}

const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    if (state.hasOwnProperty(key)) {
      state[key] = value
    }
  }
}

const actions = {
  changeSetting({ commit }, data) {
    commit('CHANGE_SETTING', data)
  },
  actionCommonLoading(store, flag) {
    (store.state.pageLoading.loading = flag)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

