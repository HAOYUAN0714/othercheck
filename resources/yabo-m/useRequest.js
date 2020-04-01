import request from '@/utils/request'
// 取得樣板清單
export function getTemplateIdList(query) {
  return request({
    url: '/promotion/api/direct/template-id-list',
    method: 'get',
    params: query
  })
}
export function getCopyableList(query) {
  return request({
    url: '/promotion/api/direct/copyable-event-list',
    method: 'get',
    params: query
  })
}
export function getEventName(query) {
  return request({
    url: '/promotion/api/direct/event-name-by-id',
    method: 'get',
    params: query
  })
}
export function getHallIdList(query) {
  return request({
    url: '/promotion/api/direct/hall-id-list',
    method: 'get',
    params: query
  })
}
export function getEventByTime(query) {
  return request({
    url: '/promotion/api/direct/event-by-time',
    method: 'get',
    params: query
  })
}
export function getEventInfoById(query) {
  return request({
    url: '/promotion/api/direct/event-by-id',
    method: 'get',
    params: query
  })
}
export function editPrize(query) {
  return request({
    url: '/promotion/api/prize',
    method: 'post',
    data: query
  })
}
export function getPrize(query) {
  return request({
    url: '/promotion/api/direct/logic',
    method: 'get',
    params: query
  })
}
export function editEventInfo(query) {
  return request({
    url: '/promotion/api/modify-event',
    method: 'post',
    data: query
  })
}
export function createEvent(query) {
  return request({
    url: '/promotion/api/create-event',
    method: 'post',
    data: query
  })
}
export function releaseEvent(query) {
  return request({
    url: '/promotion/api/release-event',
    method: 'post',
    data: query
  })
}
export function searchEvent(query) {
  return request({
    url: '/promotion/api/direct/event-search',
    method: 'get',
    params: query
  })
}
export function getOption(query) {
  return request({
    url: '/promotion/api/mix/option-list',
    method: 'get',
    params: query
  })
}
export function getRanking(query) {
  return request({
    url: '/promotion/api/direct/ranking',
    method: 'get',
    params: query
  })
}
export function getRankingView(query) {
  return request({
    url: '/promotion/api/direct/ranking-list',
    method: 'get',
    params: query
  })
}
export function setRankParameter(query) {
  return request({
    url: '/promotion/api/modify-ranking-probability',
    method: 'post',
    data: query
  })
}
export function setRankMember(query) {
  return request({
    url: '/promotion/api/create-ranking-parameter',
    method: 'post',
    data: query
  })
}
export function setRankSwitch(query) {
  return request({
    url: '/promotion/api/modify-ranking-parameter',
    method: 'post',
    data: query
  })
}
export function getSimulateFakeRanking(query) {
  return request({
    url: '/promotion/api/simulate-fake-ranking',
    method: 'get',
    params: query
  })
}
export function setUploadRankingData(query) {
  return request({
    url: '/promotion/api/upload-ranking-data',
    method: 'post',
    data: query
  })
}
export function setExposure(query) {
  return request({
    url: '/promotion/api/modify-exposure-switch',
    method: 'post',
    data: query
  })
}
export function stopEvent(query) {
  return request({
    url: '/promotion/api/stop-event',
    method: 'post',
    data: query
  })
}
// 操作記錄 - 取得操作紀錄
export function searchLog(query) {
  return request({
    url: '/promotion/api/operation/search-log',
    method: 'get',
    params: query
  })
}
// 操作記錄 - 取得活動清單 - 搜尋所有有效活動
export function searchEventForOperation(query) {
  return request({
    url: '/promotion/api/operation/event-list',
    method: 'get',
    params: query
  })
}
// 操作記錄 - 取得活動開放廳主清單
export function searchHallByEvent(query) {
  return request({
    url: '/promotion/api/operation/hall-list-by-event',
    method: 'get',
    params: query
  })
}
// 操作記錄 - 取得操作記錄種類
export function getOperationTypeList(query) {
  return request({
    url: '/promotion/api/operation/type-list',
    method: 'get',
    params: query
  })
}
// 使用時import { getTemplateIdList,  getHallIdList} from '@/api/vodkaGlobal'