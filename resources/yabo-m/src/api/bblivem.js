import axios from 'axios'
import VueCookie from 'vue-cookie'

export function getEventInfoByPage(val) {
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

// index.vue
// export function getAllEventCount() {
//   return axios.post('/exsport/api/get-all-event-count', {
//     Token: VueCookie.get('abs-token'),
//     MemberCode: VueCookie.get('abs-mem'),
//     LanguageCode: VueCookie.get('abs-lang')
//   }).then((res) => {
//     return res.data
//   })
// }
export function getAllEventCount() {
  return axios.get('api/get-all-event-count').then((res) => {
    // console.log('res', res)
    if (res.data.code === 200 && res.data.msg === 'Success') {
      // console.log('res', res.data.data)
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// 滾球賽事
// export function getLiveBasicData(SportId) {
//   return axios.get('api/ls/get-live-event-info', {
//     LanguageCode: 'CHS',
//     SportIds: [SportId],
//     Market: 3,
//     OddsType: +(VueCookie.get('odds_type')) || 2,
//     IsCombo: false,
//     PeriodIds: [1], // 暫時只取全場
//     // Page,
//     BetTypeIds: [1, 2, 3, 4],
//     OrderBy: +(VueCookie.get('filterMatch'))
//   }).then((res) => {
//     if (res.data.StatusCode === 100) {
//       console.log('get-event-info-by-page', res.data)
//       return res.data
//     } else {
//       // console.log('get-event-info-by-page error')
//       return []
//     }
//   })
// }

// 滾球賽事
export function getLiveBasicData(SportId) {
  return axios.get('api/get-live-event-info', {
    params: {
      // SportId: SportId,
      Market: 3,
      SportIds: [SportId],
      OddsType: +(VueCookie.get('odds_type')) || 2,
      OrderBy: +(VueCookie.get('filterMatch'))
    }
  }).then((res) => {
    if (res.data.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data.data)
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// 今日賽事
export function getTodayGame(SportId, Page) {
  return axios.get('api/get-event-info-by-page', {
    params: {
      SportId: SportId,
      Market: 2,
      Page: Page,
      OrderBy: +(VueCookie.get('filterMatch'))
    }
  }).then((res) => {
    if (res.data.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data)
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// 早盤賽事
export function getFutureGame(SportId, Page) {
  return axios.get('api/get-event-info-by-page', {
    params: {
      SportId: SportId,
      Market: 1,
      Page: Page
    }
  }).then((res) => {
    if (res.data.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data)
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// video in more
export function getVideo(SportId, Market, Page) {
  console.log('ttt')
  return axios.get('api/get-event-info-by-page', {
    params: {
      SportId: SportId,
      Market: Market,
      Page: Page
      // SportId: 1,
      // Market: 1,
      // EventDate: '2020-03-13',
      // EventId: 6358773,
      // Page: 1
    }
  }).then((res) => {
    console.log(res, 'res video')
    if (res.data.data.StatusCode === 100) {
      // console.log('get-event-info-by-page', res.data)
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// 更多玩法 ---------------
export function getSelectedEventInfo(SportId, EventId) {
  console.log(SportId, [EventId], 'SportId, EventId')
  // const event = [EventId]
  return axios.get('api/get-selected-event-info', {
    params: {
      SportId: SportId,
      EventIds: [EventId]
    }
  }).then((res) => {
    console.log(res, 'res getSelectedEventInfo')
    if (res.data.data.StatusCode === 100) {
      console.log('hi')
      return res.data.data
    } else {
      // console.log('get-event-info-by-page error')
      return []
    }
  })
}

// export function getSportKind(Market, IsCombo, IsLive = false) {
//   return axios.post('/exsport/api/get-sport-mapping', {
//     LanguageCode: VueCookie.get('abs-lang'),
//     IsCombo,
//     Market,
//     IsLive
//   }).then((res) => {
//     if (res.status === 200) {
//       // console.log(res.data, 'res.data')
//       // console.log('get-sport-mapping-------:', res.data.Sports)
//       return res.data.sportMapping
//     } else {
//       // console.log('get-sport-mapping error')
//       return []
//     }
//   })
// }
