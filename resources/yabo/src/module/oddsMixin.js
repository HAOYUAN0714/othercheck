export default {
    methods: {
        //賠率顯示統一
        $_parseOdds(oddsSet) {
            let odds = String(oddsSet.Odds)
            if (odds.length > 3) {
                return oddsSet.Odds;
            }
            if (odds.length <= 3) {
                return this.$_cutNumLength(odds, odds.length)
            }
        },
        //數字統整
        $_cutNumLength(odds, length) {
            const index = String(odds).indexOf('.')
            if (index > -1) {
                //有小數點
                return parseFloat(Number(odds))
                    .toFixed(5 - length)
            } else {
                //整數
                return parseFloat(Number(odds))
                    .toFixed(3 - length)
            }
        },
    }
}
