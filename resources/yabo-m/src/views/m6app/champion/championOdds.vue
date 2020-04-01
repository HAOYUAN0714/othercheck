<template>
  <div>
    <div
      v-for="item in oddsData"
      :key="item.CompetitionId"
      class="container"
    >
      <div
        v-for="betType in item.MarketLines"
        :key="betType.MarketlineId"
        class="bet-type"
      >
        <div class="title">{{ item.OutrightEventName }}</div>
        <div
          v-for="odds in betType.WagerSelections"
          :key="odds.WagerSelectionId"
          class="odds-wrap clearfix"
        >
          <div class="team-name">{{ odds.SelectionName }}</div>
          <div v-if="!odds || +(betType.MarketlineStatusId) === 2 || +(odds.Odds) === 0 || betType.IsLocked" class="lock">
            <i class="fas fa-lock" />
          </div>
          <div v-else class="odds" @click="clickOdds(betType.BetTypeId, odds.SelectionId, betType.MarketlineId, item.MarketLines, item, odds)">{{ odds.Odds }}
          </div>
        </div>
      </div>
    </div>
    <transition>
      <funcAll
        v-if="isShowFuncAll"
        :open-bet-slip="isShowFuncAll"
        @closeFuncAll="closeFuncAll"
      />
    </transition>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { getOutRightLeagueEvent } from '@/api/m6'
import funcAll from '@/layout/m6app/components/funcAll'

export default {
  components: {
    funcAll
  },
  data() {
    return {
      oddsData: '',
      // comboStatus 0初始 1取資訊 2可下過關（可能騙你）3準備過關 4以過關
      comboStatus: 0,
      errorMsg: '',
      collisionCheck: [],
      isShowFuncAll: false,
      sportId: '',
      iscombo: false,
      finalInfo: []
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  watch: {
    curBetSlip(val) {
      this.finalInfo = []
      // const collision = {}
      this.comboStatus = 0
      this.errorMsg = ''
      val.forEach((data) => {
        const base = data.betinfo.apiInput
        // 計算串了幾場
        if (this.comboStatus === 0 || this.comboStatus === 1) {
          if (base.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
            this.collisionCheck.push(data.collisionCheck)
            this.collisionCheck = this.collisionCheck.filter((element, index, arr) => { return arr.indexOf(element) === index })
            // this.comboInfo = '串關處理中'
          } else {
            // this.comboInfo = '含有非歐洲盤賠率 無法串關'
            this.comboStatus = -1
            this.finalInfo = []
          }
        }
      })
    }
  },
  mounted() {
    this.getChampionApi()
    this.sportId = this.$router.currentRoute.params.sportId
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
    getChampionApi() {
      const leagueId = this.$route.params.leagueId
      getOutRightLeagueEvent(1, leagueId).then((res) => {
        this.oddsData = res.Events
      })
    },
    clickOdds(playId, betId, marketlineid, betType, games, odds) {
      this.setBet({
        bet: this.getBetTypeData(betType, playId, betId, marketlineid),
        playType: this.getMarketLineId(betType, playId, betId, marketlineid),
        games: games,
        sportId: Number(this.sportId),
        combo: this.iscombo
      })
      // console.log(betType, 'betType', marketlineid, 'marketlineid', odds, 'odds')
      if (this.iscombo || betType[0].MarketlineStatusId === 2 || odds.Odds === 0 || betType[0].IsLocked === true) {
        this.isShowFuncAll = false
      } else {
        this.isShowFuncAll = !this.isShowFuncAll
      }
    },
    closeFuncAll(val) {
      this.isShowFuncAll = val
    },
    getBetTypeData(data, betType, selectionId, marketlineid) {
      const work = data.filter(
        val => val.BetTypeId === betType && val.MarketlineId === marketlineid
      )[0]
      if (work) {
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType, selectionId, marketlineid) {
      return data.filter(val => val.BetTypeId === betType && val.MarketlineId === marketlineid)[0]
    }

  }
}
</script>

<style lang="scss" scoped>
.container {
  background-color: #EFEDED;
  padding: 20px;
  .bet-type {
    background-color: #FFF;
    .title {
      padding: 10px 0 0 10px;
      font-weight: bold;
    }
    .odds-wrap {
      padding: 10px;
      color: #000;
      .team-name {
        float: left;
        color: #A7A7A7;
      }
      .odds,
      .lock {
        float: right;
        padding-top: 2px;
      }
    }
    .active {
      background-color: #ffcb46;
    }
  }
}
</style>
