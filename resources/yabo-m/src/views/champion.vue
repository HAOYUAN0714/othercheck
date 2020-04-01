<template>
  <div class="dashboard-container">
    <loading v-show="isLoadingStatus && !initTimer" />
    <div class="dashboard-scoreListArea">
      <div class="scoreList">
        <div class="item">
          <div class="logo" @click="back()">
            <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
          </div>
          <div class="title">
            <span>{{ SportKind[sportId] ? SportKind[sportId].sportName :'' }}</span>
            <span style="color:#fff;">{{ $t('S_OUTRIGHTS') }}</span>
          </div>
        </div>
        <div id="champion">
          <template>
            <div v-if="championEventData">
              <div v-for="(championEvent,index) in championEventData.Events" :key="index">
                <div class="item">
                  <div class="item-title">{{ championEvent.OutrightEventName }}</div>
                  <div class="item-title">{{ championEvent.EventDate.substring(0,10) }}
                    <span class="sub-text" style="margin-left:3px;">{{ championEvent.EventDate.substring(11,16) }}</span>
                  </div>
                </div>
                <div v-for="(championMarket,market) in championEvent.MarketLines" :key="market">
                  <!-- championMarket: {{ championMarket }} -->
                  <div class="title">{{ championMarket.BetTypeName }}</div>
                  <div v-for="(Wager,Selection) in championMarket.WagerSelections" :key="Selection">
                    <div v-show="Wager.Odds != 0" class="list">
                      <div class="left">{{ Wager.SelectionName }}</div>
                      <div
                        :class="{
                          active: curBetSlip.some(item => item.odds_id === Wager.WagerSelectionId),
                          disable: +(championMarket.marketlineStatusId) === 2 || +(Wager.Odds) === 0 || championMarket.IsLocked,
                          up: oddsStatus === 'up',
                          down: oddsStatus === 'down',
                          change: oddsStatus === 'up' || oddsStatus === 'down'
                        }"
                        class="right"
                        @click="clickOdds(championMarket.BetTypeId, Wager.SelectionId, championEvent, sportId)"
                      >
                        <i v-if="+(championMarket.marketlineStatusId) === 2 || +(Wager.Odds) === 0 || championMarket.IsLocked" class="fas fa-lock" />
                        <span v-else>{{ parseOdds(Wager) }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div>
                <div class="score-item">
                  <div class="score-title">{{ $t('S_SORRY') }}</div>
                </div>
              </div>
            </div>
          </template>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { getOutRightLeagueEvent, getSportKind } from '@/utils/getBallData'
import loading from '@/layout/components/loading'

export default {
  name: 'Dashboard',
  components: {
    loading
  },
  data() {
    return {
      championEventData: {},
      SportKind: {},
      sportId: '',
      oddsStatus: '',
      timeout: '',
      dataTimeout: '',
      isLoadingStatus: false,
      initTimer: false
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  watch: {
    Wager(newOdds, oldOdds) {
      if (newOdds.Odds > oldOdds.Odds) {
        this.oddsStatus = 'up'
      }
      if (newOdds.Odds < oldOdds.Odds) {
        this.oddsStatus = 'down'
      }

      this.timeout = setTimeout(() => {
        this.oddsStatus = ''
      }, 5000)
    }
  },
  beforeDestroy() {
    clearTimeout(this.timeout)
    clearInterval(this.dataTimeout)
  },
  mounted() {
    this.sportId = this.$route.params.sportid
    this.championid = this.$route.params.championid
    this.pass = this.$route.params.pass
    this.championidEventData(this.sportId, this.championid, this.pass)

    this.dataTimeout = setInterval(() => {
      this.championidEventData(this.sportId, this.championid, this.pass)
    }, 30000)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
    // market: 1=早盤 ２=今日
    championidEventData(SportId, CompetitionId, pass) {
      this.isLoadingStatus = true
      if (pass === 'pass') {
        getOutRightLeagueEvent(SportId, CompetitionId, true).then((res) => {
          this.championEventData = res
          this.isLoadingStatus = false
          this.initTimer = true
        })
      } else {
        getOutRightLeagueEvent(SportId, CompetitionId, false).then((res) => {
          this.championEventData = res
          this.isLoadingStatus = false
          this.initTimer = true
        })
      }
      getSportKind().then((res) => {
        this.SportKind = res
      })
    },
    getBetTypeData(data, betType, selectionId) {
      const work = data.filter(
        val => val.BetTypeId === betType
      )[0]
      if (work) {
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType) {
      return data.filter(val => val.BetTypeId === betType)[0]
    },
    clickOdds(playId, betId, games, sportId) {
      this.setBet({
        bet: this.getBetTypeData(games.MarketLines, playId, betId),
        playType: this.getMarketLineId(games.MarketLines, playId),
        games,
        sportId: +(sportId)
      })
    },
    select(number) {
      this.chanal = number
    },
    selectScore(nu) {
      this.ScoreChannel = nu
    },
    show(id) {
      if (!this.showcode[id]) {
        this.showcode[id] = true
      } else {
        delete this.showcode[id]
      }
      this.$forceUpdate()
    },
    change(path) {
      this.$router.push(path)
    },
    disply(way) {
      this.showWay = way
    },
    routerPush(navItem) {
      this.$router.push({ path: navItem })
    },
    passRouterPUsh() {
      this.$router.push({ path: 'score' })
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
<style lang="scss" scoped>

  #champion {
    & .item {
    display: block;
    color: var(--title2_text);
    padding: 10px;
    background:  var(--title2_bg);
    min-height: 57px;
    // line-height: 36px;
    font-size: 15px;
    &-title {
      width: 100%;
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 20px;
    }
    }
    & .title {
      font-size: 12px;
      background: var(--content_gameline_bg);
      color: var(--content_gameline_text);
      min-height: 35px;
      display: -webkit-flex;
      padding: 0 8px;
      display: flex;
      -webkit-flex-direction: column;
      flex-direction: column;
      -webkit-justify-content: center;
      justify-content: center;
      -webkit-align-items: baseline;
      align-items: baseline;
    }
    & .list {
      padding: 10px;
      display: -webkit-flex;
      -webkit-flex-direction: row;
      flex-direction: row;
      -webkit-box-orient: horizontal;
      -webkit-justify-content: left;
      justify-content: left;
      -webkit-align-items: center;
      align-items: center;
      -webkit-flex-align: left;
      -webkit-align-items: center;
      -webkit-box-pack: left;
      -webkit-box-align: center;
      -webkit-justify-content: left;
      display: -webkit-box;
      display: -moz-box;
      display: -ms-flexbox;
      display: -webkit-flex;
      display: flex;
      font-size: 15px;
      color: #707070;
      border-bottom: 1px solid #c9c6cd;
      & .left {
        -webkit-box-flex: 4;
        -prefix-box-flex: 4;
        -webkit-flex: 4;
        flex: 4;
        box-flex: 4;
        width: 0%;
        display: block;
        justify-content: flex-start ;
      }
      & .right {
        color: #0a2900;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        transition: none;
        background-color: #FFF;
        border: 1px solid #c9c6cd;
        justify-content: flex-end;
        padding: 14px 0;
        padding-bottom: 13px;
        display: inline-block;
        font-size: 15px;
        font-weight: bold;
        text-align: center;
        width: 63px;
      }
      & .active {
        background-color: #ffcb46;
        color: #222;
        border-color: #502a08;
      }
      & .disable {
        background-color: #FFF;
        color: #BBBABA;
      }
      & .up {
        background-color: #44C38A;
        color: #FFF;
        border-color: #44C38A;
        transition: background-color .3s linear;
      }
      & .down {
        background-color: #E54242;
        color: #FFF;
        border-color: #E54242;
        transition: background-color .3s linear;
      }
    }
}
</style>
