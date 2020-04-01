<template>
  <div class="func">
    <div class="all">
      <div class="allNav clearfix">
        <div
          v-for="(item, index) in switchList"
          :key="`switch-${index}`"
          :class="[
            'switch',
            {
              switchColor: curNav === item.type
            }
          ]"
          @click="selNowyet(item.type)"
        >
          {{ item.text }}
        </div>
      </div>
      <div class="allSportsNow clearfix">
        <div
          v-for="(number, sportId) in curSportCount"
          :key="`ball-${sportId}`"
          class="balls"
          @click="getGameList({ SportId: sportId, Market: navTable[curNav] })"
        >
          <div class="allicon">
            <div :class="['icon', `sport${sportId}`]" />
            <div v-if="curNav === 'live'" class="sport-count">
              {{ number }}
            </div>
          </div>
          <div class="ballname">
            {{ sportList[sportId] ? sportList[sportId].sportName : '' }}
          </div>
        </div>
      </div>
    </div>
    <div class="allLeave" @click="allLeave" />
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import { getEventInfoByPage } from '@/api/betball'

export default {
  props: {
    sportCount: {
      type: Object,
      required: true
    },
    sportList: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      curNav: '',
      switchList: [
        {
          text: this.$t('S_LIVE_2'),
          type: 'live'
        },
        {
          text: this.$t('S_NOT_START'),
          type: 'today'
        }
      ],
      periodTable: {
        1: this.$t('S_FULL_COURT'),
        2: this.$t('S_FIRST_HALF'),
        3: this.$t('S_SECOND_HALF')
      },
      navTable: {
        live: 3,
        today: 2
      },
      hasSentAPI: false
    }
  },
  computed: {
    ...mapGetters({
      navHeader: 'betball/getNavHeader',
      preferPlayGroup: 'betball/getPreferPlayGroup'
    }),
    curSportCount() {
      return this.sportCount[this.curNav] || {}
    }
  },
  created() {
    this.curNav = this.navHeader
  },
  methods: {
    ...mapActions({
      actionSetNavHeader: 'betball/actionSetNavHeader',
      actionSetCurSportId: 'betball/actionSetCurSportId',
      actionSetTotalGameList: 'betball/actionSetTotalGameList',
      actionSetIsLoading: 'betball/actionSetIsLoading'
    }),
    allLeave() {
      this.$emit('closeFuncAll', false)
    },
    selNowyet(val) {
      this.curNav = val
    },
    getGameList({ SportId, Market }) {
      console.log(this.preferPlayGroup)
      // 避免 curSportId & navHeader 同時觸發更新
      if (this.hasSentAPI) {
        return
      }

      this.hasSentAPI = true
      this.allLeave()
      const gameList = {}

      if (!SportId) {
        this.actionSetTotalGameList(gameList)
        return
      }

      this.hasSentAPI = true
      this.actionSetIsLoading(true)
      this.actionSetNavHeader(this.curNav)
      this.actionSetCurSportId(SportId)
      getEventInfoByPage({
        SportId: +SportId,
        Market,
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

              console.log(gameList)
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
              console.log('ttt', gameList)
            })
        }
      }).catch((val) => {
        console.log(val)
      }).finally(() => {
        console.log(gameList)
        this.actionSetTotalGameList(gameList)
        this.actionSetIsLoading(false)
        this.hasSentAPI = false
      })
    }
  }
}
</script>
<style lang="scss" scoped>
.func{
  position: absolute;
  top: 95px;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 1;

  .all{
    background: #FFF;
    border-radius: 0 0 15px 15px;
  }

  .allNav{
    height: 50px;
    padding: 10px;
    border-bottom: 1px solid #eee;

    .switch{
      margin: 0 10px;
      color: #7d7e80;
      line-height: 30px;
      font-size: 15px;
      float: left;
    }

    .switchColor{
        color: #0149ff;
        border-bottom: 2px solid #0149ff;
    }
  }

  .allSportsNow{
    padding: 10px 15px;

    .balls{
      width: 50px;
      margin-right: 10px;
      float: left;

      .allicon{
        position: relative;
        margin-bottom: 5px;

        .icon{
          width:auto;
          height: 40px;
          padding: 0 8px;
          background-size: auto 40px;
          background-repeat: no-repeat;
          background-position: 8px 0;
          background-image: url('~@/icons/png/leftBar/i-soccer.png'); // default

          &.sport1 {
            background-image: url('~@/icons/png/leftBar/i-soccer.png');
          }

          &.sport2 {
            background-image: url('~@/icons/png/leftBar/i-brasketball.png');
          }

          &.sport15 {
            background-image: url('~@/icons/png/leftBar/i-brasketball.png');
          }

          &.sport25 {
            background-image: url('~@/icons/png/leftBar/i-brasketball.png');
          }

          &.sport34 {
            background-image: url('~@/icons/png/leftBar/i-icehockey.png');
          }

          &.sport40 {
            background-image: url('~@/icons/png/leftBar/i-volleyball.png');
          }
        }

        .sport-count{
          position: absolute;
          left: 31%;
          top:68%;
          font-size: 1px;
          color: #0149ff;
          border: 1px solid #0149ff;
          background: #FFF;
          border-radius: 5px;
          width:25px;
          text-align: center;
        }
      }

      .ballname{
          padding-left: 5px;
          font-size: 12px;
          color: #0149ff;
          text-align: center;
      }
    }
  }

  .allLeave{
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(48, 47, 47, 0.7);
  }
}

</style>
