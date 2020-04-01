import i18n from '../utils/i18n'

export default {
  methods: {
    getSelectTeamName(betData) {
      if (betData.BetTypeId === 1 || betData.BetTypeId === 3 || betData.BetTypeId === 4) {
        if (betData.BetTypeSelectionId === 1 || betData.BetTypeSelectionId === 5 || betData.BetTypeSelectionId === 8) {
          return betData.HomeTeamName
        }
        if (betData.BetTypeSelectionId === 2 || betData.BetTypeSelectionId === 6 || betData.BetTypeSelectionId === 9) {
          return betData.AwayTeamName
        }
        return this.$t('S_DRAW')
      }
      if (betData.BetTypeId === 11) {
        return betData.OutrightTeamName
      }
      if (betData.BetTypeId === 35) {
        const num = (betData.Specifiers || '').split('=')[1]
        const [scoreX, scoreY] = num.split(':')
        return betData.SelectionName
          .replace('{x-y}', scoreX - scoreY)
          .replace('{y-x}', scoreY - scoreX)
      }
      if (betData.BetTypeId === 81) {
        const score = betData.Specifiers.split('=')[1]
        return `${betData.SelectionName}先得${score}分`
      }

      const specifiers = betData.Specifiers
      if (!specifiers || specifiers === '') {
        return betData.SelectionName
      }

      let selectionName = betData.SelectionName
      const regex = /[^&=?]+=[^&#]*/g
      const target = specifiers.match(regex)

      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          selectionName = selectionName.replace(`{${key}}`, value)
        })
      }
      return selectionName
    },
    formatPlayTypeName(val) {
      if (val.BetTypeId === 81) {
        return `${val.BetTypeName}`.replace('{pointnr}', '几')
      }
      const specifiers = val.Specifiers
      if (!specifiers || specifiers === '') {
        return val.BetTypeName
      }
      let betTypeName = val.BetTypeName
      const regex = /[^&=?]+=[^&#]*/g
      const target = specifiers.match(regex)
      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          betTypeName = betTypeName.replace(`{${key}}`, value)
        })
      }
      return betTypeName
    },
    parseOdds(oddsSet) {
      const odds = String(oddsSet.Odds)
      if (odds.length > 3) {
        return oddsSet.Odds
      }
      if (odds.length <= 3) {
        return this.cutNumLength(odds, odds.length)
      }
    },
    // 數字統整
    cutNumLength(odds, length) {
      const index = String(odds).indexOf('.')
      if (index > -1) {
        // 有小數點
        return parseFloat(+(odds)).toFixed(5 - length)
      }
      // 整數
      return parseFloat(+(odds)).toFixed(3 - length)
    },
    // 當前下注比分
    getCurBetScore(val) {
      if ((!val.WagerHomeTeamScore && val.WagerHomeTeamScore !== 0) || (!val.WagerAwayTeamScore && val.WagerAwayTeamScore !== 0)) {
        return
      }
      return `(${val.WagerHomeTeamScore}-${val.WagerAwayTeamScore})`
    },
    // 終場比分
    getFTScore(val) {
      if ((!val.HomeTeamFTScore && val.HomeTeamFTScore !== 0) || (!val.AwayTeamFTScore && val.AwayTeamFTScore !== 0)) {
        return
      }
      return `(${i18n.t('S_FULL_COURT')}${val.HomeTeamFTScore}-${val.AwayTeamFTScore})`
    },
    toCurrency(num) {
      if (!num) {
        return
      }
      const fixedNum = num.toFixed(3).slice(0, -1)
      const parts = fixedNum.toString().split('.')
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',')
      return (parts.join('.'))
    }
  }
}
