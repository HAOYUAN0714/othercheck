import axios from 'axios'
import VueCookie from 'vue-cookie'

// 暫時不需要切換
// const mappingCode = {
//   'zh-tw': 'CHS',
//   'zh-cn': 'CHS',
//   'en': 'ENG',
//   'vi': 'VN'
// }

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

export function getEventInfoByPage(val) {
  const {
    SportId,
    Market,
    IsCombo,
    BetTypeIds,
    EventDate
  } = {
    SportId: 1,
    Market: 3,
    IsCombo: false,
    BetTypeIds: [1],
    EventDate: '',
    ...val
  }

  return axios.post('/exsport/api/get-event-info-by-page', {
    SportId,
    Market,
    IsCombo,
    LanguageCode: VueCookie.get('abs-lang'),
    OddsType: +(VueCookie.get('odds_type')) || 2,
    Page: 1,
    EventDate,
    BetTypeIds
  }).then((res) => {
    // console.log('getEventInfoByPage', res.data)
    return res.data
  })
}
export function getBalance() {
  return axios.post('/exsport/api/get-balance').then((res) => {
    return (res.data['AvailableBalance'])
  })
}
// 投注已結算記錄
export function getStatement() {
  const StartDate = '2019-12-10'
  const EndDate = '2019-12-30'
  const LanguageCode = VueCookie.get('abs-lang')
  return axios.post('/exsport/api/get-statement', { StartDate, EndDate, LanguageCode }).then((res) => {
    return (res.data['WagerList'])
  })
}
// 投注未結算記錄
export function getBetList() {
  const BetConfirmationStatus = [2] // 1: 待處理 2: 已確認 3:已拒絕 4:已取消
  const LanguageCode = VueCookie.get('abs-lang')
  return axios.post('/exsport/api/get-bet-list', { BetConfirmationStatus, LanguageCode }).then((res) => {
    return (res.data['WagerList'])
  })
}

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
