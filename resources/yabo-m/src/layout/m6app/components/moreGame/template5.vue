<template>
  <div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="play-type-outer"
    >
      <!-- test5{{ collisionLen }} -->
      <div class="clearfix">
        <div class="title">{{ teamName[0] }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => homePlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="playType"
          :class="{
            active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId),
          }"
        >
          <div
            class="odds-wrap"
            @click="clickOdds(playType.BetTypeId, wager.SelectionId, playType.MarketlineId, playType, wager)"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
        </div>
      </div>
      <div class="clearfix">
        <div class="title">{{ $t('S_DRAW') }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => drawPlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="draw-playType"
          :class="{
            active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId),
          }"
        >
          <div
            class="odds-wrap"
            @click="clickOdds(playType.BetTypeId, wager.SelectionId, playType.MarketlineId, playType, wager)"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
        </div>
      </div>
      <div class="clearfix">
        <div class="title">{{ teamName[1] }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => awayPlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="playType"
          :class="{
            active: curBetSlip.some(item => item.odds_id === wager.WagerSelectionId),
          }"
        >
          <div
            class="odds-wrap"
            @click="clickOdds(playType.BetTypeId, wager.SelectionId, playType.MarketlineId, playType, wager)"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
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
    betFunction: {
      type: Function,
      default: () => ({})
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
      homePlayType: [227, 229, 231, 233, 235],
      awayPlayType: [228, 230, 232, 234, 236],
      drawPlayType: [237],
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
      this.finalInfo = []
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
      this.setBet({
        bet: this.getBetTypeData(this.games.MarketLines, playId, betId, marketlineid),
        playType: this.getMarketLineId(this.games.MarketLines, playId, betId, marketlineid),
        games: this.games,
        sportId: Number(this.sportId),
        combo: this.iscombo
      })
      if (this.iscombo) {
        // console.log('hahahahah')
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
        // console.log(work.WagerSelections.filter(val => val.SelectionId === selectionId)[0], 'ioio')
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType, selectionId, marketlineid) {
      // console.log(data, 'data', betType, 'betType', selectionId, 'selectionId', marketlineid, 'marketlineid')
      // console.log(data.filter(val => val.BetTypeId === betType && val.MarketlineId === marketlineid)[0], '11123123')
      return data.filter(val => val.BetTypeId === betType && val.MarketlineId === marketlineid)[0]
    },
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => a.SelectionId - b.SelectionId)
    },
    openBet() {
      // const routeName = this.$route.name
      this.$router.push('/comboBet')
    }
  }
}
</script>

<style lang="scss" scoped>
.play-type-outer {
  padding: 10px;
  font-size: 14px;
  .title {
    margin: 5px 10px;
    text-align: center;
  }
  .playType {
    background-color: #FCFCFC;
    float: left;
    width: 48%;
    margin-left: 2%;
    padding: 12px 0;
    .odds-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 10px;
      .selection-name {
        color: #A7A7A7;
      }
    }
    &.active {
      background-color: #ffcb46;
    }
  }
  .draw-playType {
    background-color: #FCFCFC;
    margin-left: 2%;
    padding: 12px 0;
    .odds-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 10px;
      .selection-name {
        color: #A7A7A7;
      }
    }
    &.active {
      background-color: #ffcb46;
    }
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
