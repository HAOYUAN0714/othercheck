<template>
  <div class="sport-date">
    <div class="back">
      <div class="front">
        <div
          v-for="(date, index) in sportData"
          :key="`date-${index}`"
          :class="[
            'days',
            {
              chgColor: selperiod === index
            }
          ]"
          @click="selDate(index)"
        >
          {{ date }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment-timezone'
import { mapActions, mapGetters } from 'vuex'
import { getEventInfoByPage } from '@/api/betball'

export default {
  data() {
    return {
      selperiod: 0,
      sportData: [
        this.$t('S_TODAY'),
        this.$t('S_ALL_DATE')
        // '其他早盤'
      ],
      periodTable: {
        1: this.$t('S_FULL_COURT'),
        2: this.$t('S_FIRST_HALF'),
        3: this.$t('S_SECOND_HALF')
      }
    }
  },
  computed: {
    ...mapGetters({
      curSportId: 'betball/getCurSportId',
      preferPlayGroup: 'betball/getPreferPlayGroup'
    })
  },
  created() {
    const totalList = [1, 2, 3, 4, 5]
    const mappedList = totalList.map((item) => {
      return moment.tz('Asia/Taipei').subtract(12, 'hours').add(item, 'day').format('MM/DD')
    })

    this.sportData = [...this.sportData, ...mappedList]
  },
  methods: {
    ...mapActions({
      actionSetIsLoading: 'betball/actionSetIsLoading',
      actionSetTotalGameList: 'betball/actionSetTotalGameList'
    }),
    selDate(val) {
      this.selperiod = val
      let date
      switch (val) {
        case 0:
          date = ''
          break
        case 1:
          date = '000' // 隨便代字串會會回傳全部日期
          break
        default:
          date = moment.tz('Asia/Taipei').subtract(12, 'hours').add(val - 1, 'day').format('YYYY-MM-DD')
          break
      }

      this.getGameList({
        SportId: this.curSportId,
        Market: this.selperiod ? 1 : 2,
        EventDate: date
      })
    },
    getGameList({ SportId, Market, EventDate }) {
      console.log(this.preferPlayGroup)
      // 避免 curSportId & navHeader 同時觸發更新
      // if (this.hasSentAPI) {
      //   return
      // }

      // this.hasSentAPI = true
      const gameList = {}
      this.actionSetIsLoading(true)
      getEventInfoByPage({
        SportId: +SportId,
        Market,
        EventDate,
        BetTypeIds: [this.preferPlayGroup]
      }).then((res) => {
        if (res.Sports[0]) {
          res.Sports[0].Events
            .filter(item => item.MarketLines.length > 0)
            .forEach((item, index) => {
              const homePlayGroupTable = {
                1: 1,
                2: 3,
                4: 8
              }
              const awayPlayGroupTable = {
                1: 2,
                2: 4,
                4: 9
              }
              console.log('ttt', homePlayGroupTable[this.preferPlayGroup])

              const homeInfo = item.MarketLines
                .filter(item => item.PeriodId === 1)
                .sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0]
                .WagerSelections
                .filter(item => item.SelectionId === homePlayGroupTable[this.preferPlayGroup])[0]
              const awayInfo = item.MarketLines
                .filter(item => item.PeriodId === 1)
                .sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0]
                .WagerSelections
                .filter(item => item.SelectionId === awayPlayGroupTable[this.preferPlayGroup])[0]

              if (gameList[item.Competition.CompetitionId]) {
                gameList[item.Competition.CompetitionId] = {
                  ...gameList[item.Competition.CompetitionId],
                  matchList: [
                    ...gameList[item.Competition.CompetitionId].matchList,
                    {
                      homeName: item.HomeTeam,
                      awayName: item.AwayTeam,
                      homeScore: item.HomeScore,
                      awayScore: item.AwayScore,
                      matchId: item.EventId,
                      period: this.periodTable[item.PeriodId],
                      time: item.EventDate,
                      EventDate: item.EventDate,
                      RBTime: item.RBTime,
                      RbTimeStatus: item.RbTimeStatus,
                      moreNum: item.TotalMarketLineCount,
                      homeMainHandicapCap: homeInfo.Handicap,
                      awayMainHandicapCap: awayInfo.Handicap,
                      homeMainHandicapOdds: homeInfo.Odds,
                      awayMainHandicapOdds: awayInfo.Odds
                    }
                  ]
                }
                return
              }

              gameList[item.Competition.CompetitionId] = {
                sportId: SportId,
                leagueName: item.Competition.CompetitionName,
                matchList: [
                  {
                    homeName: item.HomeTeam,
                    awayName: item.AwayTeam,
                    homeScore: item.HomeScore,
                    awayScore: item.AwayScore,
                    matchId: item.EventId,
                    period: this.periodTable[item.PeriodId],
                    time: item.EventDate,
                    EventDate: item.EventDate,
                    RBTime: item.RBTime,
                    RbTimeStatus: item.RbTimeStatus,
                    moreNum: item.TotalMarketLineCount,
                    homeMainHandicapCap: homeInfo.Handicap,
                    awayMainHandicapCap: awayInfo.Handicap,
                    homeMainHandicapOdds: homeInfo.Odds,
                    awayMainHandicapOdds: awayInfo.Odds
                  }
                ]
              }
            })
        }
      }).catch((val) => {
        console.log(val)
      }).finally(() => {
        console.log(gameList)
        this.actionSetTotalGameList(gameList)
        this.actionSetIsLoading(false)
        // this.hasSentAPI = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.sport-date {
  background-color: #FFF;
  border-bottom: 1px solid #EEE;
  overflow-x: scroll;
  white-space: nowrap;
  line-height: 44px;

  .back {
    background-color: #f5f9fb;
    height: 50px;
  }

  .front {
    background-color: #FFF;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
  }

  .days{
    font-size: 14px;
    white-space:nowrap;
    color: #a1a2a9;
    padding: 0 10px;
    display: inline-block;
    background-color: #FFF;
  }

  .chgColor{
    color: #0149ff;
  }
}
</style>
