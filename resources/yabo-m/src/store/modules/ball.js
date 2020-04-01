import i18n from '../../utils/i18n'

const state = {
  betSlip: false,
  showAvoid: false,
  betList: [],
  // 有用到自己新增,
  curBetSlip: [],
  getGameCount: {},
  showBetRecord: false,
  slipContent: {
    allSingleAmount: '',
    allSingleShow: i18n.t('S_STAKE'),
    parlayAmount: '',
    parlayShow: i18n.t('S_STAKE')
  },
  slipCombo: []
}

const mutations = {
  APPEND_BET: (state, id) => {
    if (!state.betList.includes(id)) {
      state.betList.push(id)
    } else {
      state.betList = state.betList.filter(val => val !== id)
    }
  },
  TOGGLE_BET: (state, status) => {
    state.betSlip = status
  },
  TOGGLE_BET_RECORD: (state, val) => {
    state.showBetRecord = val
  },
  SET_BET_SLIP: (state, val) => {
    state.curBetSlip = [
      ...state.curBetSlip,
      val
    ]
    // console.log('state.curBetSlip:', state.curBetSlip)
  },
  SET_AMOUNT: (state, val) => {
    const newdata = state.curBetSlip.find(item => item.odds_id === val.odds_id)
    newdata.amount = val.amount
    newdata.amountShow = val.amountShow
  },
  SET_COMBO_AMOUNT: (state, val) => {
    state.slipCombo.forEach((data, index, arr) => {
      if (data.ComboSelection === val.id) {
        data.amount = val.amount
      }
    })
    state.slipCombo = [...state.slipCombo]
    // console.log('MU-state.slipCombo:', state.slipCombo)
  },
  SET_SLIP_CONTENT: (state, { key, val }) => {
    state.slipContent[key] = val
  },
  SET_COMBO: (state, val) => {
    state.slipCombo = val
    // 第一次設定為 ''
    state.slipCombo.forEach((data, index, arr) => {
      data.amount = ''
    })
    // console.log('SET_COMBO', state.slipCombo)
  },
  REMOVE_BET_SLIP: (state, val) => {
    state.curBetSlip = state.curBetSlip.filter(item => item.odds_id !== val)
  },
  REMOVE_BET_SLIP_ALL: (state) => {
    state.curBetSlip = []
    state.slipCombo = []
    // console.log(state.curBetSlip, 'state.curBetSlip')
  },
  REMOVE_COMBO: (state, val) => {
    // console.log('REMOVE_COMBO-val', val)
    // const newcombo = state.slipCombo.find(item => item.odds_id === val)
    state.slipCombo.forEach((data, index, arr) => {
      if (data.ComboSelection === val) {
        data.amount = ''
      }
    })
    state.slipCombo = [...state.slipCombo]
  },
  GET_GAME_COUNT: (state, val) => {
    state.getGameCount = val
    // console.log(state.getGameCount, 'state.getGameCount')
  }
}

const actions = {
  setSlipContent({ commit }, val) {
    commit('SET_SLIP_CONTENT', val)
  },
  setCombo({ commit }, val) {
    // console.log('ac-combodata', val)
    commit('SET_COMBO', val)
  },
  appendBet({ commit }, id) {
    commit('APPEND_BET', id)
  },
  toggleBetSlip({ commit }, val = false) {
    commit('TOGGLE_BET', val)
  },
  toggleBetRecord({ commit }, val) {
    commit('TOGGLE_BET_RECORD', val)
  },
  actionGetGameCount({ commit }, val) {
    commit('GET_GAME_COUNT', val)
  },

  // 下注單
  actionSetBetSlip({ commit }, val) {
    commit('SET_BET_SLIP', val)
  },
  // bet (temp for 單注)
  actionBet({ state, commit }, { bet, playType, games, sportId, combo }) {
    // console.log('action-TEST:', bet, playType, games.RBTime, sportId, combo)
    if (!playType) {
      return
    }
    // MarketlineStatusId === 2 關盤
    if (playType.MarketlineStatusId === 2 || +(bet.Odds) === 0 || playType.IsLocked) {
      return
    }
    if (state.curBetSlip.some(item => item.odds_id === bet.WagerSelectionId)) {
      commit('REMOVE_BET_SLIP', bet.WagerSelectionId)
      return
    }
    if (state.curBetSlip.length > 9) {
      state.showAvoid = true
      return
    }
    // console.log('bet', bet)
    const inputBetTypeSelectionId = playType.BetTypeId !== 11 ? bet.SelectionId : 0
    const inputOutrightTeamId = playType.BetTypeId !== 11 ? 0 : bet.SelectionId
    const infoSelectionName = bet.SelectionName ? bet.SelectionName : ''
    if (combo === true) {
      // console.log('combo--------------------', infoSelectionName)
      const betinfo = {
        info: {
          // playType: playType,
          // bet: bet,
          SelectionName: infoSelectionName,
          HomeTeam: games.HomeTeam || '',
          AwayTeam: games.AwayTeam || '',
          HomeScore: games.HomeScore,
          AwayScore: games.AwayScore,
          league: games.Competition.CompetitionName,
          BetTypeName: playType.BetTypeName,
          PeriodName: playType.PeriodName,
          gameTime: games.RBTime,
          Market: games.Market
        },
        apiInput: {
          WagerType: 2, // 1單注 2過關
          IsComboAcceptAnyOdds: true,
          ReturnNearestHandicap: false,
          BetSetting: [],
          BetInfoStatus: -1,
          WagerSelectionInfos: {
            ReturnNearestHandicap: true,
            BetTypeId: playType.BetTypeId,
            EventId: games.EventId,
            SportId: sportId,
            OddsType: bet.OddsType,
            Odds: bet.Odds,
            WagerSelectionId: bet.WagerSelectionId,
            MarketlineId: playType.MarketlineId,
            PeriodId: playType.PeriodId,
            Specifiers: bet.Specifiers,
            Handicap: bet.Handicap ? bet.Handicap : 0,
            BetTypeSelectionId: inputBetTypeSelectionId, // 非單式0
            OutrightTeamId: inputOutrightTeamId // 非冠軍0
          }
        }
      }
      const input = {
        odds_id: bet.WagerSelectionId,
        openParlay: games.OpenParlay,
        // 檢查EventGroupId是否等於0
        collisionCheck: (games.EventGroupId > 0) ? games.EventGroupId : games.EventId,
        amount: '',
        amountShow: i18n.t('S_STAKE'),
        betinfo,
        betResult: []
      }
      // console.log('in comboball input', input.odds_id)
      // console.log('in comboball betinfo.apiInput.WagerSelectionInfos.WagerSelectionId', betinfo.apiInput.WagerSelectionInfos.WagerSelectionId)
      // console.log('in comboball betinfo.apiInput.WagerSelectionInfos.Odds', betinfo.apiInput.WagerSelectionInfos.Odds)
      const test = state.curBetSlip.filter(val => val.collisionCheck === input.collisionCheck)
      if (test.length > 0) {
        console.log('coooom')
        commit('REMOVE_BET_SLIP', test[0].odds_id)
        // console.log('need del', test[0].odds_id, state.curBetSlip)
      }
      console.log('noooooo')
      // console.log('SET_BET_SLIP', input.collisionCheck)
      commit('SET_BET_SLIP', input)
    } else {
      // console.log('single bet')
      const betinfo = {
        info: {
          // playType: playType,
          // bet: bet,
          SelectionName: infoSelectionName,
          HomeTeam: games.HomeTeam || '',
          AwayTeam: games.AwayTeam || '',
          HomeScore: games.HomeScore,
          AwayScore: games.AwayScore,
          league: games.Competition.CompetitionName,
          BetTypeName: playType.BetTypeName,
          PeriodName: playType.PeriodName,
          gameTime: games.RBTime,
          Market: games.Market
        },
        apiInput: {
          WagerType: 1, // 1單注 2過關
          IsComboAcceptAnyOdds: true,
          ReturnNearestHandicap: false,
          BetSetting: [],
          BetInfoStatus: -1,
          WagerSelectionInfos: {
            ReturnNearestHandicap: true,
            BetTypeId: playType.BetTypeId,
            EventId: games.EventId,
            SportId: sportId,
            OddsType: bet.OddsType,
            Odds: bet.Odds,
            WagerSelectionId: bet.WagerSelectionId,
            MarketlineId: playType.MarketlineId,
            PeriodId: playType.PeriodId,
            Specifiers: bet.Specifiers,
            Handicap: bet.Handicap ? bet.Handicap : 0,
            BetTypeSelectionId: inputBetTypeSelectionId, // 非單式0
            OutrightTeamId: inputOutrightTeamId // 非冠軍0
          }
        }
      }
      console.log(betinfo, 'betinfo')
      const input = {
        odds_id: bet.WagerSelectionId,
        openParlay: games.OpenParlay,
        // 檢查EventGroupId是否等於0
        collisionCheck: (games.EventGroupId > 0) ? games.EventGroupId : games.EventId,
        amount: '',
        amountShow: i18n.t('S_STAKE'),
        betinfo,
        betResult: []
      }
      // console.log('SET_BET_SLIP', input.collisionCheck)
      commit('SET_BET_SLIP', input)
    }
  },
  actionSetAmount({ commit }, val) {
    // console.log('action-id:', val.odds_id)
    commit('SET_AMOUNT', val)
  },
  actionSetCombo_amount({ commit }, val) {
    // console.log('action-id:', val.odds_id)
    commit('SET_COMBO_AMOUNT', val)
  },
  actionRemoveBetSlip({ commit }, val) {
    commit('REMOVE_BET_SLIP', val)
  },
  actionRemoveBetSlipAll({ commit }) {
    commit('REMOVE_BET_SLIP_ALL')
  },
  resetCombo({ commit }, val) {
    commit('REMOVE_COMBO', val)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
