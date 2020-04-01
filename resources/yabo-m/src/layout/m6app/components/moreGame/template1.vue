<template>
  <div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="clearfix"
    >
      <!-- test1{{ collisionLen }} -->
      <div
        v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections)"
        :key="wagerKey"
        class="odds-wrap clearfix"
        :class="{
          active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId),
        }"
        @click="clickOdds(playType.BetTypeId, wager.SelectionId, playType.MarketlineId, playType, wager)"
      >
        <div v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</div>
        <div
          class="odds-outer"
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
    })
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
    this.finalInfo = []
    this.comboStatus = 0
    this.collisionLen = this.curBetSlip.length
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
      // console.log(playType, 'playType')
      // console.log(wager, 'wager')
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
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => a.SelectionId - b.SelectionId)
    },
    getBetTypeData(data, betType, selectionId, marketlineid) {
      const work = data.filter(
        val => val.BetTypeId === betType && val.MarketlineId === marketlineid
      )[0]
      if (work) {
        // console.log(work.WagerSelections.filter(val => val.SelectionId === selectionId)[0], 'ioio')
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType, selectionId, marketlineid) {
      return data.filter(val => val.BetTypeId === betType && val.MarketlineId === marketlineid)[0]
    },
    showTeamName(val) {
      if (this.betTypeId === 1 || this.betTypeId === 3 || this.betTypeId === 4) {
        if (val.SelectionId === 1 || val.SelectionId === 5 || val.SelectionId === 8) {
          return this.teamName[0]
        }
        if (val.SelectionId === 2 || val.SelectionId === 6 || val.SelectionId === 9) {
          return this.teamName[1]
        }
        return this.$t('S_DRAW')
      }
      if (val.BetTypeId === 81) {
        const score = val.Specifiers.split('=')[1]
        return `${val.SelectionName}先得${score}分`
      }
      const specifiers = val.Specifiers
      if (!specifiers || specifiers === '') {
        return val.SelectionName
      }
      let selectionName = val.SelectionName
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
.odds-wrap {
  background-color: #FCFCFC;
  height: 38px;
  line-height: 38px;
  margin: 10px 2% 0 2%;
  font-size: 14px;
  .selection-name {
    float: left;
    padding-left: 10px;
    color: #A7A7A7;
  }
  .odds-outer {
    float: right;
    padding-right: 10px;
  }
}
.active {
  background-color: #ffcb46;
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
