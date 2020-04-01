<template>
  <div class="container">
    <sportheader />
    <dateSelectNav v-if="navHeader === 'today'" />
    <div class="content-wrap">
      <div v-for="(sport, leagueId) in totalGameList" :key="leagueId" class="sport-content">
        <div class="sportevent">
          <div class="label" @click="showLeague(leagueId)">
            <div class="icon" />
            <div class="league-name">{{ sport.leagueName }}</div>
            <img src="../../../icons/png/up.png" :class="['arrow', { down: leagueOpenList[leagueId] }]">
          </div>

          <div :style="{ height: !leagueOpenList[leagueId] && sport.matchList ? `${sport.matchList.length * 75}px` : `0px` }" class="odds-Content clearfix">
            <div
              v-for="(matchInfo, subIndex) in sport.matchList"
              :key="`matchInfo-${subIndex}`"
              class="match-wrap clearfix"
            >
              <div class="odds-Time">
                <div>{{ matchInfo.isLive ? RbTimeStatusTable[matchInfo.RbTimeStatus] : moment(matchInfo.time).format('HH:mm') }}</div>
                <div class="game-time-info">{{ navHeader === 'live' ? matchInfo.RBTime : moment(matchInfo.EventDate).format('DD/MM') }}</div>
              </div>
              <div class="odds-Teams">
                <div class="teams">
                  <div class="oddsL">
                    <div class="mar10">
                      <span>{{ matchInfo.homeName }}</span>
                    </div>
                    <div class="mar10">
                      <span>{{ matchInfo.awayName }}</span>
                    </div>
                  </div>
                  <div class="oddsR">
                    <div class="fL">
                      <div class="score">{{ matchInfo.homeScore }}</div>
                      <div class="score">{{ matchInfo.awayScore }}</div>
                    </div>
                    <div class="fR" @click="showSportDetails(sport.sportId, matchInfo.matchId)">
                      <span>{{ matchInfo.moreNum }}></span>
                    </div>
                  </div>
                </div>
                <div class="race">
                  <div class="homeodds">
                    <div class="htxt colgray">{{ matchInfo.homeMainHandicapCap }}</div>
                    <div class="htxt">{{ matchInfo.homeMainHandicapOdds }}</div>
                  </div>
                  <div class="awayodds">
                    <div class="atxt colgray">{{ matchInfo.awayMainHandicapCap }}</div>
                    <div class="atxt">{{ matchInfo.awayMainHandicapOdds }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="Object.keys(totalGameList).length === 0" class="empty-reminder-box">
        <div class="empty-reminder" />
        {{ $t('S_NO_DATA') }}
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import { mapGetters, mapActions } from 'vuex'
import sportheader from '@/layout/betball/components/header'
import dateSelectNav from '@/layout/betball/components/dateSelectNav'
import { getEventInfoByPage, getSelectEventInfo } from '@/api/betball'

export default {
  components: {
    sportheader,
    dateSelectNav
  },
  data() {
    return {
      moment,
      leagueOpenList: {},
      periodTable: {
        1: '全場',
        2: '上半場',
        3: '下半場'
      },
      RbTimeStatusTable: {
        0: '不適用',
        1: '開始',
        2: '進行中',
        3: '暫停'
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
      curSportId: 'betball/getCurSportId',
      navHeader: 'betball/getNavHeader',
      totalGameList: 'betball/getTotalGameList',
      sportCount: 'betball/getSportCount',
      preferPlayGroup: 'betball/getPreferPlayGroup'
    })
  },
  watch: {
    curSportId(val) {
      this.getGameList({ SportId: +val, Market: this.navTable[this.navHeader] })
    },
    navHeader(val) {
      this.getGameList({ SportId: +Object.keys(this.sportCount[this.navHeader])[0], Market: this.navTable[val] })
    },
    preferPlayGroup(val) {
      this.getGameList({ SportId: +val, Market: this.navTable[this.navHeader] })
    }
  },
  created() {
  },
  methods: {
    ...mapActions({
      actionSetTotalGameList: 'betball/actionSetTotalGameList',
      actionSetIsLoading: 'betball/actionSetIsLoading',
      actionSetMoreGameInfo: 'betball/actionSetMoreGameInfo'
    }),
    showLeague(id) {
      this.leagueOpenList = {
        ...this.leagueOpenList,
        [id]: !this.leagueOpenList[id]
      }
    },
    showSportDetails(sport, matchId) {
      this.actionSetIsLoading(true)
      getSelectEventInfo(+sport, matchId).then((res) => {
        const item = res[0].Events[0]
        const playGroupList = item.MarketLines.reduce((prev, curr) => {
          const keyName = `${curr.BetTypeId}_${curr.PeriodId}`
          if (prev[keyName]) {
            return {
              ...prev,
              [keyName]: {
                ...prev[keyName],
                WagerSelections: [
                  ...prev[keyName].WagerSelections,
                  ...curr.WagerSelections
                ]
              }
            }
          }

          return {
            ...prev,
            [keyName]: { ...curr }
          }
        }, {})
        const gameInfo = {
          leagueName: res[0].Events[0].Competition.CompetitionName,
          awayName: item.AwayTeam,
          homeName: item.HomeTeam,
          awayScore: item.AwayScore,
          homeScore: item.HomeScore,
          time: item.EventDate,
          RbTimeStatus: item.RbTimeStatus,
          RBTime: item.RBTime,
          EventDate: item.EventDate,
          playGroupList,
          matchId,
          BREventId: item.BREventId
        }

        this.actionSetMoreGameInfo(gameInfo)
      }).finally(() => {
        this.actionSetIsLoading(false)
        this.$router.push({ name: 'sportDetails', path: '/sportDetails' })
      })
    },
    getGameList({ SportId, Market }) {
      console.log(this.preferPlayGroup)
      // 避免 curSportId & navHeader 同時觸發更新
      if (this.hasSentAPI) {
        return
      }

      const gameList = {}

      if (!SportId) {
        this.actionSetTotalGameList(gameList)
        return
      }

      this.hasSentAPI = true
      this.actionSetIsLoading(true)
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

              // console.log(gameList)
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
              // console.log('ttt', gameList)
            })
        }
      }).catch((val) => {
        // console.log(val)
      }).finally(() => {
        // console.log(gameList)
        this.actionSetTotalGameList(gameList)
        this.actionSetIsLoading(false)
        this.hasSentAPI = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  background: #f5f9fb;
  background-size: 100% 100%;
}
.content-wrap {
  min-height: calc(100vh - 100px);
  overflow-y: scroll;
}

.sport {
  &-content {
    .sportevent {
      margin: 10px 5px 0 5px;
      border-radius: 10px;
      background-color: #ffffff;
    }
    .label {
      height: 45px;
      padding: 6px 12px;
      border-bottom: 1px solid #eee;
      overflow: hidden;
      font-size: 14px;

      .icon {
        background: url('~@/assets/betball/basketball.png') no-repeat center/cover;
        height: 28px;
        float: left;
        width: 28px;
      }

      // .logoSize{
      //     height: 20px;
      //     width: auto;
      //     padding: 6px 5px;
      //     float: left;
      // }

      .league-name {
        color: #323233;
        line-height: 30px;
        float: left;
        width: calc(100% - 66px);
        vertical-align: top;
        white-space: nowrap;
        overflow-x: scroll;
        margin-right: 10px;
        padding-left: 7px;
      }

      .arrow {
        height: 18px;
        width: auto;
        padding: 5px;
        float: right;
        transition: transform .3s;

        &.down {
          transform: rotate(180deg);
        }
      }
    }
    .odds {
      &-Content {
        height: 0;
        overflow: hidden;
        transition: all .4s ease-in-out;

        &.open {
          height: 75px;
        }
      }

      &-Time {
        float: left;
        width: 10%;
        color: red;
        text-align: center;
        line-height: 27px;
        font-size: 14px;
        white-space:nowrap;
        margin: 10px 0 0 6px;

        .game-time-info {
          font-size: 12px;
          white-space: nowrap;
          overflow-x: scroll;
        }
      }

      &-Teams {
        height: 60px;
        width: 88%;
        float: right;
        margin-top: 10px;
        .teams {
          float: left;
          width: 60%;

          .oddsL {
            float: left;
            padding-left: 5px;
            width: calc(100% - 53px);

            .mar10 {
              font-size: 14px;
              padding-right: 5px;
              color: #4c4c4c;
              white-space: nowrap;
              overflow: scroll;
              line-height: 28px;
            }
          }

          .oddsR {
            font-size: 14px;
            float: left;
            border-right: 1px solid #e6e9ec;
            width: 53px;

            .fL {
              padding: 2px 3px;
              float: left;
              .score {
                text-align: center;
                margin-bottom: 10px;
                color: red;
              }
            }
            .fR {
              span {
                text-align: center;
                color: blue;
              }
              float: right;
              padding: 20px 3px;
            }
          }
        }

        .race {
          float: right;
          width: 40%;
          padding: 4px 0;
          font-size: 14px;
          .colgray {
            color: #c6c9cb;
          }
          .homeodds {
            float: left;
            width: 50%;
            .htxt {
              text-align: center;
              margin-bottom: 10px;
            }
          }
          .awayodds {
            float: right;
            width: 48%;
            border-left: 1px solid #e6e9ec;
            .atxt {
              text-align: center;
              margin-bottom: 10px;
            }
          }
        }
      }
    }
  }
}

.empty-reminder-box {
  height: 200PX;
  margin: 0 auto;
  padding-top: 35px;
  text-align: center;
}
.empty-reminder {
  width: 250px;
  height: 165px;
  margin: 0 auto;
  background: url('~@/assets/betball/empty-reminder.png') no-repeat center/contain;
}
</style>
