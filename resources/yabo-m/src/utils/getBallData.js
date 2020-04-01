import axios from 'axios'
import VueCookie from 'vue-cookie'

export function getBallDataTest() {
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId: 1,
    Market: 2,
    OddsType: +(VueCookie.get('odds_type')),
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
    OddsType: +(VueCookie.get('odds_type')),
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

// 特定天賽事
export function getOneFutureDay(SportId, EventDate, Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  const inputOddsType = IsCombo ? 3 : +(VueCookie.get('odds_type'))
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    EventDate,
    Market: 1,
    OddsType: inputOddsType,
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
  const inputOddsType = IsCombo ? 3 : +(VueCookie.get('odds_type'))
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    Market: 2,
    OddsType: inputOddsType,
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

export function getSelectedEventInfo(EventId, SportId, IsCombo = false) {
  const inputOddsType = IsCombo ? 3 : +(VueCookie.get('odds_type'))
  return axios.post('/exsport/api/get-selected-event-info', {
    EventIds: [EventId],
    LanguageCode: VueCookie.get('abs-lang'),
    SportId,
    OddsType: inputOddsType,
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
export function getLeagueBasicData(League, SportId, Market, date = '1', Page = 1, IsCombo = false) {
  // console.log('JS:SportId,', SportId)
  // console.log(date)
  const inputOddsType = IsCombo ? 3 : +(VueCookie.get('odds_type'))
  const params = date !== '1' && date !== '2' && date !== '3' ? {
    EventDate: date,
    SportId,
    Market,
    Page,
    IsCombo
  } : {
    SportId,
    Market,
    Page,
    IsCombo
  }
  return axios.post('/exsport/api/get-event-info-by-page', {
    LanguageCode: VueCookie.get('abs-lang'),
    OddsType: inputOddsType,
    PeriodIds: [1], // 暫時只取全場
    CompetitionIds: [League],
    BetTypeIds: [1, 2, 3, 4],
    ...params
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
    OddsType: +(VueCookie.get('odds_type')),
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

export function getSportKind(IsCombo = false, IsLive = false) {
  return axios.post('/exsport/api/get-sport-mapping', {
    LanguageCode: VueCookie.get('abs-lang'),
    IsCombo,
    IsLive
  }).then((res) => {
    if (res.status === 200) {
      // console.log('get-sport-mapping:', res.data.Sports)
      return res.data.sportMapping
    } else {
      // console.log('get-sport-mapping error')
      return []
    }
  })
}

export function getAllSportCount(IsCombo = false) {
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
export function getBetInfo(apiInput) {
  return axios.post('/exsport/api/get-bet-info', apiInput).then((res) => {
    if (res.status === 200) {
      // console.log('get-bet-info:', res)
      return res.data
    } else {
      // console.log('get-sport-mapping error')
      return []
    }
  })
}

export function placeBet(apiInput) {
  return axios.post('/exsport/api/place-bet', apiInput).then((res) => {
    if (res.status === 200) {
      // console.log('place-bet:', res)
      return res.data
    } else {
      // console.log('place-bet error')
      return []
    }
  })
}

export function extendSession() {
  return axios.post('/exsport/api/extend-session').then((res) => {
    if (res.status === 200) {
      // console.log('extend-session:', res)
      return res.data
    } else {
      // console.log('place-bet error')
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
    if (res.data.StatusCode === 100) {
      return res.data.AvailableBalance
    }
  })
}

export function getUser() {
  return axios.get('/api/v1/c/player').then((res) => {
    if (res.status === 200) {
      return res.data.ret.user.username
    }
  })
}
