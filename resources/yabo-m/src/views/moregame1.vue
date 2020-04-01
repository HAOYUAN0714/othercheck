<template>
  <div>
    <div class="more-game-wrap">
      <div
        v-for="(item, index) in moreGameData"
        :key="`more-game-${index}`"
      >
        <div class="more-game-header clearfix">
          <div class="go-back" @click="toLastPage">
            <i class="el-icon-arrow-left" />
          </div>
          <div class="sport-title">
            <div class="sport-name">{{ item.SportName }}</div>
            <div class="league-name">{{ item.Events[0].Competition.CompetitionName }}</div>
          </div>
        </div>
        <div
          v-for="(detail, detailKey) in item.Events"
          :key="detailKey"
          class="game-detail-wrap"
        >
          <div class="game-detail">
            <div class="team-name">
              <div class="name">
                {{ detail.HomeTeam }}
                <span class="vs">v</span>
                {{ detail.AwayTeam }}
              </div>
            </div>
            <div v-if="detail.Market === 3" class="match-time">
              <div v-show="detail.HomeScore != null && detail.AwayScore != null"> (&nbsp;{{ detail.HomeScore }}&nbsp;-&nbsp;{{ detail.AwayScore }}&nbsp;)</div>
              <real-time :time="detail.RBTime" :sport-id="item.SportId" :time-status="detail.RBTimeStatus" />
            </div>
            <div v-else class="match-time">{{ formatDate(detail.EventDate) }}</div>
          </div>
          <!--玩法-->
          <div
            v-for="(moreGame, moreGameIndex) in formatPlayType(detail.MarketLines)"
            :key="moreGameIndex"
            class="more-game-detail"
          >
            <div
              v-for="(playType, playTypeIndex) in moreGame"
              :key="playTypeIndex"
            >
              <div class="play-type-wrap" @click="toggleOddsTable(playType)">
                <i
                  :class="[
                    hidePlayTypeData[`${playType[0].BetTypeId}_${playType[0].PeriodId}`] ? 'el-icon-arrow-up' : 'el-icon-arrow-down'
                  ]"
                />
                <span class="play-type">{{ formatBetType(playType[0]) }}-<span class="court">{{ playType[0].PeriodName }}</span></span>
              </div>
              <Component
                :is="getTableId(playType[0].BetTypeId)"
                v-if="!hidePlayTypeData[`${playType[0].BetTypeId}_${playType[0].PeriodId}`]"
                :odds-data="playType"
                :team-name="getTeamName(detail.HomeTeam, detail.AwayTeam)"
                :bet-type-id="playType[0].BetTypeId"
                :bet-function="clickOdds"
                :games="detail"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <loading v-show="loadingStatus && !timeout" />
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import { getSelectedEventInfo } from '@/utils/getBallData'
import moment from 'moment'
import template1 from '@/layout/components/moreMarket/template1'
import template2 from '@/layout/components/moreMarket/template2'
import template3 from '@/layout/components/moreMarket/template3'
import template4 from '@/layout/components/moreMarket/template4'
import template5 from '@/layout/components/moreMarket/template5'
import loading from '@/layout/components/loading'
import realTime from '@/layout/components/realTimeClock'

export default {
  components: {
    template1,
    template2,
    template3,
    template4,
    template5,
    loading,
    realTime
  },
  data() {
    return {
      moreGameData: [],
      hidePlayTypeData: {},
      gameId: '',
      sportId: '',
      loadingStatus: false,
      timeout: '',
      initTimer: false,
      comboNew: 'all'
    }
  },
  created() {
    this.comboNew = this.$route.params.isComboNew
    this.gameId = this.$route.params.gameid
    this.sportId = this.$route.params.sportid
    this.getMoreGameApi()
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
    clickOdds(playType, oddsObj) {
      const sportId = this.$route.params.sportid
      const games = this.moreGameData[0].Events[0]
      // console.log('playType:', playType, 'oddsObj:', oddsObj)
      this.setBet({
        bet: oddsObj,
        playType: playType,
        games,
        sportId: +(sportId)
      })
    },
    toLastPage() {
      this.$router.go(-1)
    },
    getMoreGameApi() {
      const comboParam = (this.comboNew === 'combo')
      this.loadingStatus = true
      getSelectedEventInfo(this.gameId, this.sportId, comboParam).then((res) => {
        this.moreGameData = res
        this.loadingStatus = false
        const curMarketId = this.moreGameData[0].Events[0].Market
        if (!this.initTimer) {
          if (+(curMarketId) === 3) {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getMoreGameApi()
            }, 5000)
          } else {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getMoreGameApi()
            }, 30000)
          }
        }
      }).catch((res) => {
        this.loadingStatus = false
      })
    },
    formatDate(val) {
      return moment(val).utcOffset(-4).format('MM/DD HH:mm')
    },
    formatPlayType(val) {
      const final = {}
      val.forEach((item) => {
        if (!final[item.BetTypeId]) {
          final[item.BetTypeId] = {}
        }
        if (!final[item.BetTypeId][item.PeriodId]) {
          final[item.BetTypeId][item.PeriodId] = []
        }
        final[item.BetTypeId][item.PeriodId].push(item)
      })
      return final
    },
    getTableId(val) {
      switch (+(val)) {
        case 2:
        case 54:
        case 93:
        case 94:
          return 'template2'
        case 35:
        case 86:
          return 'template3'
        case 6:
          return 'template4'
        case 62:
          return 'template5'
        default:
          return 'template1'
      }
    },
    getTeamName(homeTeam, awayTeam) {
      const team = []
      team.push(homeTeam, awayTeam)
      return team
    },
    toggleOddsTable(val) {
      const key = `${val[0].BetTypeId}_${val[0].PeriodId}`
      if (Object.keys(this.hidePlayTypeData).includes(key)) {
        delete this.hidePlayTypeData[key]
        this.$forceUpdate()
        return
      }
      this.hidePlayTypeData = {
        ...this.hidePlayTypeData,
        [key]: true
      }
    },
    formatBetType(val) {
      if (val.BetTypeId === 81) {
        return `${val.BetTypeName}`.replace('{pointnr}', '几')
      }
      const specifiers = val.WagerSelections[0].Specifiers
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
    }
  }
}
</script>

<style lang="scss" scoped>

.more-game-wrap {
  background: #502a07;
  border-bottom: 1px solid #ebebeb;
}
.more-game-header {
  // background: linear-gradient(to right, #955d2c, #f3d382 70%, #f3d382 80%, #955d2c 100%);
  background-color: var(--title1_bg);
  padding: 7px 10px 6px;
  border-top: 1px solid #282828;
  .go-back {
    float: left;
    margin-right: 10px;
    padding: 10px 0;
    font-size: 22px;
    color: var(--title1_text);
  }
  .sport-title {
    float: left;
    width: 86%;
    padding: 6px 0 7px;
    color: var(--title1_text);
    .sport-name {
      font-weight: bold;
    }
    .league-name {
      color: var(--title1_sub_text);
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
  }
}
.game-detail-wrap {
  .game-detail {
    background-color: var(--title2_bg);
    padding: 10px;
    color: var(--title2_text);
    font-weight: bold;
    .team-name {
      font-size: 15px;
      .vs {
        margin: 0 10px;
        color: var(--more_game_vs_text);
      }
    }
    .match-time {
      margin-top: 6px;
      font-size: 12px;
    }
  }
  .more-game-detail {
    background-color: #fbfffb;
    .play-type-wrap {
      background-color: var(--more_game_header_bg);
      border-top: 1px solid #333;
      padding: 11px 10px;
      color: var(--more_game_header_text);
      font-size: 15px;
      .court {
        color: var(--more_game_header_sub_text);
      }
    }
  }
}
.table {
  border-bottom: 1px solid #C9C6CD;
  padding: 6px 10px;
  font-size: 15px;
  .team-name {
    float: left;
    width: 50%;
    padding-top: 12px;
    color: #003075;
  }
  .odds-wrap {
    float: left;
    width: 50%;
    text-align: right;
    font-size: 15px;
    .cap {
      margin-right: 6px;
      color: #8c5320;
    }
    .odds {
      background-color: var(--content_odds_bg);
      display: inline-block;
      width: 30%;
      padding: 10px 6px 9px;
      border-radius: 3px;
      border: 1px solid #C9C6CD;
      font-weight: bold;
      text-align: center;
    }
  }
}
</style>
