/**
* getAllUrlParams - 抓取所有url變數
*
* @param  {type} url 連結
* @return {object}   變數物件
*/
export default function getAllUrlParams(url) {
  let queryString = url ? url.split('?')[1] : window.location.search.slice(1)

  const obj = {}

  if (queryString) {
    [queryString] = queryString.split('#')

    const arr = queryString.split('&')

    for (let i = 0; i < arr.length; i += 1) {
      const a = arr[i].split('=')

      let paramNum = ''
      let paramName = a[0].replace(/\[\d*\]/, (v) => {
        paramNum = v.slice(1, -1)
        return ''
      })

      const paramValue = typeof (a[1]) === 'undefined' ? true : a[1]

      // 變數名稱皆轉小寫
      paramName = paramName.toLowerCase()

      if (obj[paramName]) {
        if (typeof obj[paramName] === 'string') {
          obj[paramName] = [obj[paramName]]
        }
        if (typeof paramNum === 'undefined') {
          obj[paramName].push(paramValue)
        } else {
          obj[paramName][paramNum] = paramValue
        }
      } else {
        obj[paramName] = paramValue
      }
    }
  }
  return obj
}
