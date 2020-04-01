import axios from 'axios'
import VueCookie from 'vue-cookie'
import moment from 'moment'
import store from '@/store'
import getAllUrlParams from '@/utils/getAllParams'

// 暫時不需要切換
// const mappingCode = {
//   'zh-tw': 'CHS',
//   'zh-cn': 'CHS',
//   'en': 'ENG',
//   'vi': 'VN'
// }

export function getEventInfoByPage(val) {
  console.log(val, '?????')
  const {
    SportId,
    Market,
    IsCombo,
    BetTypeIds,
    EventDate,
    Page
  } = {
    SportId: val.SportId,
    Market: val.Market,
    IsCombo: false,
    BetTypeIds: [1, 2],
    EventDate: '',
    Page: val.page,
    ...val
  }

  return axios.post('/exsport/api/get-event-info-by-page', {
    SportId,
    Market,
    IsCombo,
    LanguageCode: VueCookie.get('abs-lang'),
    OddsType: +(VueCookie.get('odds_type')) || 2,
    Page,
    EventDate,
    BetTypeIds,
    PeriodIds: [1] // 暫時只取全場
  }).then((res) => {
    // console.log('getEventInfoByPage', res.data)
    return res.data
  })
}

// export function getSelectEventInfo(SportId, EventIds, IsCombo = false, IncludeGroupEvents = false) {
//   return axios.post('/exsport/api/get-selected-event-info', {
//     SportId,
//     EventIds: [EventIds],
//     OddsType: +(VueCookie.get('odds_type')) || 2,
//     LanguageCode: VueCookie.get('abs-lang'),
//     IncludeGroupEvents,
//     IsCombo
//   }).then((res) => {
//     if (res.data.StatusCode === 100) {
//       // console.log('get-selected-event-info:', res.data.Sports)
//       return res.data.Sports
//     }
//   })
// }
export function getSelectEventInfo(SportId, EventIds, IsCombo = false, IncludeGroupEvents = false) {
  return axios.post('/exsport/api/get-selected-event-info', {
    SportId,
    EventIds: [EventIds],
    OddsType: +(VueCookie.get('odds_type')) || 2,
    LanguageCode: VueCookie.get('abs-lang'),
    IncludeGroupEvents,
    IsCombo
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-selected-event-info:', res.data.Sports)
      return res.data.Sports
    }
  })
}

export function getBallDataTest() {
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId: 1,
    Market: 2,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo: false,
    Page: 1,
    BetTypeIds: [1, 2, 3, 4]
  }).then((res) => {
    // console.log('this.sportData', res)
  })
}

export function getHomeInfo(IsCombo = false) {
  return axios.post('/exsport/api/get-home-info', {
    IsCombo,
    LanguageCode: VueCookie.get('abs-lang')
  }).then((res) => {
    if (res.status === 200) {
      // console.log('getHomeInfo', res.data)
      return res.data
    } else {
      // console.log('get-home-info error')
      return []
    }
  })
}

export function getEventCount(SportId, IsCombo = false) {
  return axios.post('/exsport/api/get-event-count', {
    SportId,
    IsCombo,
    LanguageCode: VueCookie.get('abs-lang')
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-event-count', res.data)
      return res.data
    } else {
      // console.log('get-event-count error')
      return []
    }
  })
}

export function getLiveBasicData(SportId, Page = 1, IsCombo = false) {
  return axios.post('/exsport/api/get-live-event-info', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportIds: [SportId],
    Market: 3,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    PeriodIds: [1], // 暫時只取全場
    // Page,
    BetTypeIds: [1, 2, 3, 4],
    OrderBy: +(VueCookie.get('filterMatch'))
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      console.log('get-event-info-by-page', res.data)
      return res.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

export function getLiveCombo(SportId, Page = 1, IsCombo = true) {
  console.log('ffff123')
  return axios.post('/exsport/api/get-live-event-info', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportIds: [SportId],
    Market: 3,
    // OddsType: +(VueCookie.get('odds_type')) || 2,
    OddsType: 3,
    IsCombo,
    PeriodIds: [1], // 暫時只取全場
    // Page,
    BetTypeIds: [1, 2, 3, 4]
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data)
      return res.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// sport-mapping
export function getSportKind(Market, IsCombo, IsLive = false) {
  return axios.post('/exsport/api/get-sport-mapping', {
    LanguageCode: VueCookie.get('abs-lang'),
    IsCombo,
    Market,
    IsLive
  }).then((res) => {
    if (res.status === 200) {
      // console.log(res.data, 'res.data')
      // console.log('get-sport-mapping-------:', res.data.Sports)
      return res.data.sportMapping
    } else {
      // console.log('get-sport-mapping error')
      return []
    }
  })
}

// 今日賽事
export function getTodayGame(SportId, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId, // 体育项目
    Market: 2, // “早盘”、“今日”或者“滚球”的盘口
    OddsType: +(VueCookie.get('odds_type')) || 2, // Int 赔率类型 1 = 马来盘 2 = 香港盘 3 = 欧洲盘 4 = 印尼盘
    IsCombo, // 请求是连串过关赛事或者非连串过关赛事
    PeriodIds: [1], // 暫時只取全場 // 筛选时段的ID清单 1 = FT 全场 2 = 1H 上半场 3 = 2H 下半场
    Page, // 返回的页数
    BetTypeIds: [1, 2, 3, 4], // 返回的投注类型
    OrderBy: +(VueCookie.get('filterMatch'))
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data
    } else {
      // console.log('get-today error')
      return []
    }
  })
}
// 早盘賽事
export function getEarlyGame(SportId, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId, // 体育项目
    Market: 1, // “早盘”、“今日”或者“滚球”的盘口
    OddsType: +(VueCookie.get('odds_type')) || 2, // Int 赔率类型 1 = 马来盘 2 = 香港盘 3 = 欧洲盘 4 = 印尼盘
    IsCombo, // 请求是连串过关赛事或者非连串过关赛事
    PeriodIds: [1], // 暫時只取全場 // 筛选时段的ID清单 1 = FT 全场 2 = 1H 上半场 3 = 2H 下半场
    Page, // 返回的页数
    BetTypeIds: [1, 2, 3, 4], // 返回的投注类型
    OrderBy: +(VueCookie.get('filterMatch'))
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data
    } else {
      // console.log('get-today error')
      return []
    }
  })
}
// 串关赛事
export function getComboGame(SportId, Page = 1, IsCombo = true) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId, // 体育项目
    Market: 1, // “早盘”、“今日”或者“滚球”的盘口
    // OddsType: +(VueCookie.get('odds_type')) || 2, // Int 赔率类型 1 = 马来盘 2 = 香港盘 3 = 欧洲盘 4 = 印尼盘
    OddsType: 3,
    IsCombo, // 请求是连串过关赛事或者非连串过关赛事
    PeriodIds: [1], // 暫時只取全場 // 筛选时段的ID清单 1 = FT 全场 2 = 1H 上半场 3 = 2H 下半场
    Page, // 返回的页数
    BetTypeIds: [1, 2, 3, 4], // 返回的投注类型
    OrderBy: +(VueCookie.get('filterMatch'))
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data
    } else {
      // console.log('get-today error')
      return []
    }
  })
}

// 特定天賽事
export function getOneFutureDay(SportId, EventDate, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    EventDate,
    Market: 1,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    PeriodIds: [1], // 暫時只取全場
    Page,
    BetTypeIds: [1, 2, 3, 4]
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('getOneFutureDay', res.data)
      return res.data
    } else {
      // console.log('get-today error')
      return []
    }
  })
}

// 今日賽事for即將開賽(temp)
export function getToday(SportId, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    Market: 2,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    PeriodIds: [1], // 暫時只取全場
    Page,
    BetTypeIds: [1, 2, 3, 4]
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data
    } else {
      // console.log('get-today error')
      return []
    }
  })
}

// 更多玩法
// 更多玩法 今日 / 早盤
export function getSelectedEventInfo(EventId, SportId, IsCombo = false) {
  return axios.post('/exsport/api/get-selected-event-info', {
    EventIds: [EventId],
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    IncludeGroupEvents: false
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data.Sports
    } else {
      return []
    }
  })
}
// 更多玩法 串關
export function getSelectedComboEventInfo(EventId, SportId, IsCombo = false) {
  return axios.post('/exsport/api/get-selected-event-info', {
    EventIds: [EventId],
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    OddsType: 3, // 將更多玩法都改為預設歐洲盤
    IsCombo,
    IncludeGroupEvents: false
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-selected-event-info', res.data.Sports)
      return res.data.Sports
    } else {
      // console.log('get-selected-event-info error')
      return []
    }
  })
}

// 以聯盟搜尋賽事
export function getLeagueBasicData(League, SportId, Market, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    Market,
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    PeriodIds: [1], // 暫時只取全場
    Page,
    CompetitionIds: [League],
    BetTypeIds: [1, 2, 3, 4]
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data)
      return res.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// Market 1早餐 2今日 3滾球
// 取得聯盟計數（暫時版）參數：球種, 時間, 是否過關
export function getLeagueCount(SportId, Market, IsCombo = false) {
  return axios.post('/exsport/api/get-all-competition-count', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    Market,
    IsCombo,
    IncludeCloseEvent: false
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-all-competition-count', res.data.CompetitionCount)
      return res.data.CompetitionCount
    } else {
      // console.log('get-all-competition-count error')
      return []
    }
  })
}

export function getLeagueCountNew(SportId, Market, EventDate = '', IsCombo = false) {
  return axios.post('/exsport/api/get-event-list', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    Key: Market,
    EventDate,
    IsCombo,
    IncludeCloseEvent: false
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-all-competition-count-new', res.data)
      return res.data
    } else {
      // console.log('get-all-competition-count-new error')
      return []
    }
  })
}

export function getOutRightLeagueCount(SportId, IsCombo = false) {
  return axios.post('/exsport/api/get-outright-event-list', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    IsCombo
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-all-outright-count', res.data)
      return res.data
    } else {
      // console.log('get-all-outright-count error')
      return []
    }
  })
}

export function getOutRightLeagueEvent(SportId, CompetitionId, IsCombo = false) {
  return axios.post('/exsport/api/get-league-outright-event', {
    LanguageCode: VueCookie.get('abs-lang'),
    CompetitionId,
    SportId,
    IsCombo
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-all-outright-event', res.data)
      return res.data
    } else {
      // console.log('get-all-outright-event error')
      return []
    }
  })
}

export function getHot(IsCombo = false) {
  return axios.post('/exsport/api/get-popular-event', {
    LanguageCode: VueCookie.get('abs-lang'),
    OddsType: +(VueCookie.get('odds_type')) || 2,
    IsCombo,
    BetTypeIds: [-1]
    // IncludeCloseEvent: false
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      // console.log('get-popular-event', res.data.Sports)
      return res.data.Sports
    } else {
      // console.log('get-popular-event error')
      return []
    }
  })
}

export function getAllSportCount(IsCombo) {
  return axios.post('/exsport/api/get-all-sport-count', {
    LanguageCode: VueCookie.get('abs-lang'),
    IsCombo
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-all-sport-count:', res.data.SportCount)
      return res.data.SportCount
    } else {
      // console.log('get-sport-mapping error')
      return []
    }
  })
}

// 請求注單
export function getBetInfoApi(apiInput) {
  return axios.post('/exsport/api/get-bet-info', apiInput).then((res) => {
    if (res.status === 200) {
      console.log('get-bet-info:', res)
      return res.data
    } else {
      console.log('get-sport-mapping error')
      return []
    }
  })
}

export function placeBet(apiInput) {
  return axios.post('/exsport/api/place-bet', apiInput).then((res) => {
    if (res.status === 200) {
      console.log('place-bet:', res)
      return res.data
    } else {
      console.log('place-bet error')
      return []
    }
  })
}

export function extendSession() {
  return axios.post('/exsport/api/extend-session').then((res) => {
    if (res.status === 200) {
      console.log('extend-session:', res)
      return res.data
    } else {
      console.log('place-bet error')
      return []
    }
  })
}

export function getUnsettledBetList() {
  return axios.post('/exsport/api/get-bet-list', {
    BetConfirmationStatus: [1, 2],
    LanguageCode: VueCookie.get('abs-lang')
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data.WagerList
    }
    return []
  })
}

export function getSettledBetList(StartDate, EndDate) {
  return axios.post('/exsport/api/get-statement', {
    LanguageCode: VueCookie.get('abs-lang'),
    StartDate,
    EndDate,
    DateType: 2
  }).then((res) => {
    if (res.data.StatusCode === 100) {
      return res.data.WagerList
    }
    return []
  })
}

export function getExtendSession() {
  return axios.post('/exsport/api/extend-session').then((res) => {
    if (res.data.StatusCode !== 100) {
      VueCookie.delete('abs-mem')
      VueCookie.delete('abs-token')
    }
  })
}

export function getBalance() {
  return axios.post('/exsport/api/get-balance').then((res) => {
    return (res.data['AvailableBalance'])
  })
}

export function getAllEventCount() {
  return axios.post('/exsport/api/get-all-event-count', {
    Token: VueCookie.get('abs-token'),
    MemberCode: VueCookie.get('abs-mem'),
    LanguageCode: VueCookie.get('abs-lang')
  }).then((res) => {
    return res.data
  })
}
// 投注已結算記錄
export function getStatement() {
  const StartDate = moment().add(-30, 'days').format('YYYY-MM-DD')
  const EndDate = moment().format('YYYY-MM-DD')
  // const LanguageCode = VueCookie.get('abs-lang')
  return axios.post('/exsport/api/get-statement', {
    StartDate,
    EndDate
  }).then((res) => {
    return (res.data['WagerList'])
  })
}
// 投注未結算記錄
export function getBetList() {
  const BetConfirmationStatus = [1, 2, 3, 4] // 1: 待處理 2: 已確認 3:已拒絕 4:已取消
  // const LanguageCode = VueCookie.get('abs-lang')
  return axios.post('/exsport/api/get-bet-list', {
    BetConfirmationStatus
  }).then((res) => {
    return (res.data['WagerList'])
  })
}

export function getChatRoomUrl() {
  return new Promise((resolve, reject) => {
    axios.get('/exsport/api/get-chat-socket').then((res) => {
      store.dispatch('m6app/actionsetChatRoomUrl', res.data.socket_url)
      return resolve()
    })
  })
}

export function getChatRoomToken() {
  return new Promise((resolve, reject) => {
    axios.get('/api/v1/c/link/chatroom').then((res) => {
      const token = getAllUrlParams(res.data.ret.uri).jwt_token
      store.dispatch('m6app/actionsetChatRoomToken', token)
      return resolve()
    })
  })
}

export function getFavoriteMatch() {
  return axios.post('/exsport/api/get-favourite-event', {
    Token: VueCookie.get('abs-token'),
    MemberCode: VueCookie.get('abs-mem'),
    LanguageCode: VueCookie.get('abs-lang'),
    OddsType: VueCookie.get('odds_type'),
    OrderBy: VueCookie.get('filterMatch')
  })
}

export function addFavoriteMatch(SportId, EventIds) {
  return axios.post('/exsport/api/add-favourite-event', {
    Token: VueCookie.get('abs-token'),
    MemberCode: VueCookie.get('abs-mem'),
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    EventIds: [EventIds]
  }).then((res) => {
    return res.data
  })
}

export function removeFavoriteMatch(SportId, EventIds) {
  return axios.post('/exsport/api/remove-favourite-event', {
    Token: VueCookie.get('abs-token'),
    MemberCode: VueCookie.get('abs-mem'),
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    EventIds: [EventIds]
  }).then((res) => {
    return res.data
  })
}
