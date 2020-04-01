<template>
  <div>
    <div v-if="filterPlayType" class="team-name clearfix">
      <div class="team">{{ teamName[0] }}</div>
      <div class="team">{{ teamName[1] }}</div>
    </div>
    <!-- test2{{ oddsData }} -->
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="playType clearfix"
    >
      <div
        v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections)"
        :key="wagerKey"
        class="odds-wrap"
        :class="{
          active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId),
        }"
        @click="clickOdds(playType.BetTypeId, wager.SelectionId, playType.MarketlineId, playType, wager)"
      >
        <!-- active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId) && playType.MarketlineStatusId !== 2 && wager.Odds !== 0 && playType.IsLocked !== true, -->
        <template v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked">
          <div v-if="wager.Handicap || wager.Handicap === 0" class="cap">{{ formatCap(wager) }}</div>
          <div v-if="!filterPlayType" class="selection-name">{{ wager.SelectionNames }}</div>
        </template>
        <div
          class="odds-outer"
          :class="{
            'no-cap': !wager.Handicap && wager.Handicap !== 0 && filterPlayType
          }"
        >
          <odds
            :marketline="playType"
            :odds="wager"
            :teams="teamName"
          />
        </div>
      </div>
    </div>
    <div v-if="collisionCheck.length > 0 && iscombo===true" class="combo" @click="openBet()">
      串
      <div class="tips">{{ collisionLen }}</div>
    </div>
    <transition v-if="!iscombo">
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
import odds from '../odds/moreGameOdds'
import funcAll from '@/layout/m6app/components/funcAll'

export default {
  components: {
    odds,
    funcAll
  },
  props: {
    games: {
      type: Object,
      default: () => ({})
    },
    oddsData: {
      type: Array,
      default: () => []
    },
    teamName: {
      type: Array,
      default: () => []
    },
    betTypeId: {
      type: Number,
      default: 0
    },
    competition: {
      type: Array,
      default: () => []
    },
    betFunc: {
      type: Function,
      default: () => {}
    },
    sportId: {
      type: String,
      default: () => ''
    },
    iscombo: {
      type: Boolean,
      default: () => false
    }
  },
  data() {
    return {
      // comboStatus 0初始 1取資訊 2可下過關（可能騙你）3準備過關 4以過關
      comboStatus: 0,
      errorMsg: '',
      collisionCheck: [],
      isShowFuncAll: false,
      finalInfo: [],
      collisionLen: 0
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    }),
    filterPlayType() {
      if (this.betTypeId === 7 || this.betTypeId === 14 || this.betTypeId === 24 || this.betTypeId === 25 || this.betTypeId === 31 || this.betTypeId === 32) {
        return false
      }
      return true
    }
  },
  watch: {
    curBetSlip(val) {
      this.collisionLen = val.length
      // console.log(val,'val')
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
    this.collisionLen = this.curBetSlip.length
    // console.log(this.games.MarketLines, 'games')
    this.finalInfo = []
    this.comboStatus = 0
    this.curBetSlip.forEach((data) => {
      if (this.comboStatus === 0 || this.comboStatus === 1) {
        if (data.betinfo.apiInput.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
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
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet',
      setFuncState: 'm6app/actonSetFuncBetOpen'
    }),
    clickOdds(playId, betId, marketlineid, playType, wager) {
      // console.log(playId, 'playId', betId, 'betId', marketlineid, 'marketlineid')
      // console.log(playType, 'playType', wager, 'wager')
      // console.log('MarketlineStatusId', playType.MarketlineStatusId, 'IsLocked', playType.IsLocked, 'Odds', wager.Odds)
      this.setBet({
        bet: this.getBetTypeData(this.games.MarketLines, playId, betId, marketlineid),
        playType: this.getMarketLineId(this.games.MarketLines, playId, betId, marketlineid),
        games: this.games,
        sportId: Number(this.sportId),
        combo: this.iscombo
      })
      if (this.iscombo) {
        this.isShowFuncAll = false
        this.setFuncState(false)
      } else {
        if (playType.MarketlineStatusId === 2 || wager.Odds === 0 || playType.IsLocked === true) {
          this.isShowFuncAll = false
          this.setFuncState(false)
        } else {
          this.isShowFuncAll = !this.isShowFuncAll
          this.setFuncState(true)
        }
        // this.isShowFuncAll = !this.isShowFuncAll
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
    },
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => a.SelectionId - b.SelectionId)
    },
    openBet() {
      // const routeName = this.$route.name
      this.$router.push('/comboBet')
    },
    formatCap(val) {
      if (val.Handicap === 0) {
        return 0
      }
      if (!val.Handicap && val.Handicap !== 0) {
        return
      }
      const mod = val.Handicap % 0.5
      const selectionId = +(val.SelectionId)
      if (mod === 0) {
        if (this.betTypeId === 1 && selectionId === 1) {
          if (val.Handicap > 0) {
            return `-${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : +Math.abs(val.Handicap)}`
        }
        if (this.betTypeId === 1 && selectionId === 2) {
          if (val.Handicap > 0) {
            return `+${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : -Math.abs(val.Handicap)}`
        }
        return val.Handicap
      }
      if (this.betTypeId === 1 && selectionId === 1) {
        if (val.Handicap > 0) {
          return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (this.betTypeId === 1 && selectionId === 2) {
        if (val.Handicap > 0) {
          return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (this.betTypeId === 2) {
        return `${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      return val.Handicap
    }
  }
}
</script>

<style lang="scss" scoped>
.team-name {
  color: #b1b1b1;
  padding: 10px 0;
  font-size: 14px;
  .team {
    float: left;
    width: 50%;
    text-align: center;
  }
}
.playType {
  margin-bottom: 5px;
  .odds-wrap {
    background-color: #FCFCFC;
    float: left;
    width: 47%;
    padding: 10px 0;
    margin-left: 2%;
    text-align: center;
    font-size: 13px;
    .cap {
      float: left;
      width: 50%;
      color: #A7A7A7;
      text-align: right;
    }
    .odds-outer {
      float: left;
      width: 45%;
      margin-left: 5%;
      text-align: left;
      &.no-cap {
        width: 100%;
        text-align: center;
        margin-left: 0;
      }
    }
    .selection-name {
      float: left;
      width: 50%;
      padding-left: 10px;
      color: #A7A7A7;
      text-align: left;
    }
    .cap,
    .odds-outer,
    .selection-name {
      height: 20px;
      line-height: 20px;
    }
  }
  .active {
    background-color: #ffcb46;
  }
}
.combo {
  position: fixed;
  right: 0;
  top: 70vh;
  background-color: #000;
  color: #fff;
  padding: 10px 20px 10px 10px;
  border-radius: 10px 0px 0px 10px;
  .tips {
    background-color: red;
    position: fixed;
    right: 5px;
    top: 70vh;
    border-radius: 50px;
    padding: 2px;
  }
}
</style>
