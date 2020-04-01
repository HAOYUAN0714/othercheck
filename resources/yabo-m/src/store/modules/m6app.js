const state = {
  showSidebar: false,
  favoriteMatch: '',
  allEventCount: '',
  openChampionList: false,
  navHeader: 'today',
  isCombo: false,
  page: 1,
  pageSize: 1,
  comboPage: 1,
  comboPageSize: 10,
  comboMarket: 3,
  normolMarket: 3,
  totalGameList: [],
  isLoading: false,
  isWidgetLoading: false,
  moreGameInfo: {},
  preferPlayGroup: 1,
  sportCount: {
    // early: {}, // 目前不顯示
    live: {},
    today: {}, // 合併lives + 今日
    feature: {}, // 早盤
    combo: {}, // 串關
    fever: {} // 關注/收藏
  },
  sports: {},
  curSportId: '',
  betInfo: {
    // selectionId: 0,
    // selectionName: '',
    // leagueName: '',
    // homeName: '',
    // awayName: '',
    // handi: 0,
    // odds: 0
  },
  // bet
  curBetSlip: [],
  betList: [],
  slipContent: {
    allSingleAmount: '',
    allSingleShow: '投注额',
    parlayAmount: '',
    parlayShow: '投注额'
  },
  chatRoomUrl: '',
  chatRoomToken: '',
  chatRoomSetting: {
    username: '',
    channelList: [],
    literalLimit: '',
    talkInterval: ''
  },
  chatRoomInbox: {},
  currentChannel: '',
  socketInstance: {},
  isFuncBetOpen: false,
  liveUrl: ''
}

const getters = {
  getBetSlip: state => state.curBetSlip,
  getNavHeader: state => state.navHeader,
  getIsCombo: state => state.isCombo,
  getTotalGameList: state => state.totalGameList,
  getPage: state => state.page,
  getPageSize: state => state.pageSize,
  getComboPage: state => state.comboPage,
  // getComboPageSize: state => state.comboPageSize,
  getComboPageSize: state => state.comboPageSize,
  getcomboMarket: state => state.comboMarket,
  getNormolMarket: state => state.normolMarket,
  getIsLoading: state => state.isLoading,
  getIsWidgetLoading: state => state.isWidgetLoading,
  getSportCount: state => state.sportCount,
  getSports: state => state.sports,
  getBetInfo: state => state.betInfo,
  getcurBetSlip: state => state.curBetSlip,
  getSportTodayCount: state => state.sportCount.today,
  getCurSportId: state => state.curSportId,
  getMoreGameInfo: state => state.moreGameInfo,
  getPreferPlayGroup: state => state.preferPlayGroup,
  getChatRoomUrl: state => state.chatRoomUrl,
  getChatRoomToken: state => state.chatRoomToken,
  getChatRoomUserName: state => state.chatRoomSetting.username,
  getChatRoomSetting: state => state.chatRoomSetting,
  getChatRoomInbox: state => state.chatRoomInbox,
  getSocket: state => state.socketInstance,
  getChannel: state => state.currentChannel,
  getFuncBetOpen: state => state.isFuncBetOpen,
  getLiveUrl: state => state.liveUrl
}

const mutations = {
  setLiveUrl(state, val) {
    state.liveUrl = val
  },
  setNavHeader(state, val) {
    if (val === 'combo') {
      state.navHeader = val
      state.isCombo = true
    } else {
      state.navHeader = val
      state.isCombo = false
    }
  },
  setFuncBetOpen(state, val) {
    state.isFuncBetOpen = val
    // console.log(state.isFuncBetOpen, 'state.isFuncBetOpen')
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
  setPage(state, val) {
    if (state.isCombo === true) {
      // console.log('vuex--1')
      state.comboPage = val + state.comboPage
      if (state.comboPage > state.comboPageSize) {
        // console.log('vuex--3')
        state.comboMarket = state.comboMarket - 1
        if (state.comboMarket < 1) {
          // console.log('vuex--4')
          state.comboMarket = 3
        }
        state.comboPage = 1
      }
      if (state.comboMarket < 1) {
        state.comboMarket = 3
      }
      if (state.comboPage === 0) {
        // console.log('vuex--5')
        state.comboPage = 1
      }
    } else {
      // console.log('vuex--2')
      state.page = val + state.page
      if (state.page === 0) {
        // console.log('vuex--6')
        state.page = 1
      }
    }
  },
  setPageSize(state, val) {
    state.pageSize = val
    // console.log(val, 'val')
    // console.log('vuex--7')
    if (state.page > state.pageSize) {
      // console.log('vuex--8')
      state.page = 1
    }
    if (state.isCombo === true) {
      // console.log('vuex--9')
      if (state.comboMarket < 1) {
        // console.log('vuex--10')
        state.comboMarket = 3
      }
    }
  },
  setComboMarket(state, val) {
    state.comboMarket = val
  },
  SetComboPageSize(state, val) {
    state.comboPageSize = val
  },
  setSportCount(state, val) {
    state.sportCount = val
  },
  setSports(state, val) {
    state.sports = val
  },
  setMoreGameInfo(state, val) {
    state.moreGameInfo = val
  },
  setBetInfo(state, val) {
    // console.log(val, 'valvalval---')
    state.betInfo = val
    // console.log(state.betInfo, 'state.betInfo')
  },
  setPreferPlayGroup(state, val) {
    state.preferPlayGroup = val
  },
  setIsWidgetLoading(state, val) {
    state.isWidgetLoading = val
  },
  // bet
  setBetSlip: (state, val) => {
    // console.log(val, 'val*****')
    state.curBetSlip = [
      ...state.curBetSlip,
      val
    ]
    // console.log(state.curBetSlip, 'state.curBetSlip')
  },
  removeBetSlip: (state, val) => {
    // console.log(val, 'test')
    state.curBetSlip = state.curBetSlip.filter(item => item.odds_id !== val)
  },
  setAmount: (state, val) => {
    const newdata = state.curBetSlip.find(item => item.odds_id === val.odds_id)
    newdata.amount = val.amount
    newdata.amountShow = val.amountShow
  },
  removeBetSlipAll: (state) => {
    state.curBetSlip = []
  },
  setSlipContent: (state, {
    key,
    val
  }) => {
    state.slipContent[key] = val
  },
  toggleBet: (state, status) => {
    state.betSlip = status
  },
  appendBet: (state, id) => {
    if (!state.betList.includes(id)) {
      state.betList.push(id)
    } else {
      state.betList = state.betList.filter(val => val !== id)
    }
  },
  setAllEventCount: (state, val) => {
    state.allEventCount = val
  },
  setChatRoomUrl: (state, val) => {
    state.chatRoomUrl = val
  },
  setChannel: (state, val) => {
    state.currentChannel = val
  },
  setChatRoomToken: (state, val) => {
    state.chatRoomToken = val
  },
  setChatRoomSetting: (state, val) => {
    state.chatRoomSetting = val
  },
  setChatRoomInbox: (state, {
    nickname,
    content,
    timestamp,
    ch
  }) => {
    state.chatRoomInbox = {
      ...state.chatRoomInbox,
      [ch]: [
        ...state.chatRoomInbox[ch] || [],
        {
          nickname,
          content,
          timestamp
        }
      ]
    }
  },
  setSocketInstance: (state, val) => {
    state.socketInstance = val
  },
  openChampionList: (state, val) => {
    state.openChampionList = val
  },
  getFavoriteMatch: (state, val) => {
    state.favoriteMatch = val
  },
  setSidebar: (state, val) => {
    state.showSidebar = val
  }
}

const actions = {
  actionSetLiveUrl({
    commit
  }, val) {
    // console.log(val, 'inaction')
    commit('setLiveUrl', val)
  },
  actionSetAllEventCount({
    commit
  }, val) {
    commit('setAllEventCount', val)
  },
  actionSetNavHeader({
    commit
  }, val) {
    // console.log(val, 'val')
    commit('setNavHeader', val)
  },
  actionSetCurSportId({
    commit
  }, val) {
    commit('setCurSportId', val)
  },
  actionSetTotalGameList({
    commit
  }, val) {
    commit('setTotalGameList', val)
  },
  actionSetPage({
    commit
  }, val) {
    commit('setPage', val)
  },
  actionSetPageSize({
    commit
  }, val) {
    commit('setPageSize', val)
  },

  actionSetComboPageSize({
    commit
  }, val) {
    commit('SetComboPageSize', val)
  },
  actionSetIsLoading({
    commit
  }, val) {
    commit('setIsLoading', val)
  },
  actionSetComboMarket({
    commit
  }, val) {
    commit('setComboMarket', val)
  },
  actionSetSportCount({
    commit
  }, val) {
    commit('setSportCount', val)
  },
  actionSetSports({
    commit
  }, val) {
    commit('setSports', val)
  },
  actionSetMoreGameInfo({
    commit
  }, val) {
    commit('setMoreGameInfo', val)
  },
  actionSetBetInfo({
    commit
  }, val) {
    // console.log(val, 'valvalval')
    commit('setBetInfo', val)
  },
  actionSetPreferPlayGroup({
    commit
  }, val) {
    commit('setPreferPlayGroup', val)
  },
  actionSetIsWidgetLoading({
    commit
  }, val) {
    commit('setIsWidgetLoading', val)
  },
  // bet
  // 下注單
  actionSetBetSlip({
    commit
  }, val) {
    commit('setBetSlip', val)
  },
  // bet (temp for 單注)
  actionBet({
    state,
    commit
  }, {
    bet,
    playType,
    games,
    sportId,
    leagueName,
    homeName,
    awayName,
    EventId,
    betId,
    market
  }) {
    // console.log(bet, 'bet', playType, 'playType', games, 'games', sportId, 'sportId')
    // console.log(games, 'games', playType, 'playType')
    // console.log('action-TEST:', { state, commit }, { bet, playType, games, sportId })
    // if (!playType) {
    //   return
    // }
    // MarketlineStatusId === 2 關盤
    if (playType.MarketlineStatusId === 2 || +(bet.Odds) === 0) {
      return
    }
    // console.log(playType.MarketlineStatusId, 'playType')
    if (state.curBetSlip.some(item => item.odds_id === bet.WagerSelectionId)) {
      commit('removeBetSlip', bet.WagerSelectionId)
      return
    }
    if (state.curBetSlip.length > 9) {
      state.showAvoid = true
      return
    }
    // console.log('bet.SelectionName', bet.SelectionName)
    // playType.BetTypeId === 11 冠軍賽
    const inputBetTypeSelectionId = playType.BetTypeId !== 11 ? bet.SelectionId : 0
    const inputOutrightTeamId = playType.BetTypeId !== 11 ? 0 : bet.SelectionId
    const infoSelectionName = bet.SelectionName ? bet.SelectionName : ''
    const betinfo = {
      info: {
        // playType: playType,
        // bet: bet,
        SelectionName: infoSelectionName,
        HomeTeam: homeName,
        AwayTeam: awayName,
        league: leagueName,
        BetTypeName: playType.BetTypeName,
        betId: betId
      },
      apiInput: {
        WagerType: 1, // 1單注 2過關
        ReturnNearestHandicap: false,
        BetSetting: [],
        BetInfoStatus: -1,
        WagerSelectionInfos: {
          BetTypeId: playType.BetTypeId,
          EventId: EventId,
          SportId: sportId,
          OddsType: bet.OddsType,
          Odds: bet.Odds,
          WagerSelectionId: bet.WagerSelectionId,
          Market: market,
          MarketlineId: playType.MarketlineId,
          MarketlineStatusId: playType.MarketlineStatusId,
          PeriodId: playType.PeriodId,
          Specifiers: bet.Specifiers,
          Handicap: bet.Handicap ? bet.Handicap : 0,
          BetTypeSelectionId: inputBetTypeSelectionId, // 非單式0
          OutrightTeamId: inputOutrightTeamId // 非冠軍0
        }
      }
    }
    // console.log(betinfo.apiInput, 'apiInput')
    const input = {
      odds_id: bet.WagerSelectionId,
      openParlay: games.OpenParlay,
      collisionCheck: games.EventGroupId,
      amount: '',
      amountShow: '投注额',
      betinfo,
      betResult: []
    }
    // console.log(input, 'input')
    // console.log('SET_BET_SLIP', input)
    commit('setBetSlip', input)
  },
  actionSetAmount({
    commit
  }, val) {
    // console.log('action-id:', val.odds_id)
    commit('setAmount', val)
  },
  actionRemoveBetSlip({
    commit
  }, val) {
    commit('removeBetSlip', val)
  },
  actionRemoveBetSlipAll({
    commit
  }) {
    commit('removeBetSlipAll')
  },
  setSlipContent({
    commit
  }, val) {
    commit('setSlipContent', val)
  },
  toggleBetSlip({
    commit
  }, val = false) {
    commit('toggleBet', val)
  },
  appendBet({
    commit
  }, id) {
    commit('appendBet', id)
  },
  actionsetChatRoomUrl({
    commit
  }, val) {
    commit('setChatRoomUrl', val)
  },
  actionsetChannel({
    commit
  }, val) {
    commit('setChannel', val)
  },
  actionsetChatRoomToken({
    commit
  }, val) {
    commit('setChatRoomToken', val)
  },
  actionsetChatRoomSetting({
    commit
  }, val) {
    commit('setChatRoomSetting', val)
  },
  actionsetChatRoomInbox({
    commit
  }, val) {
    commit('setChatRoomInbox', val)
  },
  actionsetSocketInstance({
    commit
  }, val) {
    commit('setSocketInstance', val)
  },
  openChampionList({
    commit
  }, val) {
    commit('openChampionList', val)
  },
  actionGetFavoriteMatch({
    commit
  }, val) {
    commit('getFavoriteMatch', val)
  },
  actonSetSidebar({
    commit
  }, val) {
    commit('setSidebar', val)
  },
  actonSetFuncBetOpen({
    commit
  }, val) {
    commit('setFuncBetOpen', val)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
