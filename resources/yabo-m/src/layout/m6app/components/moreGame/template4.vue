<template>
  <div>
    <div class="header clearfix">
      <div class="title">{{ teamName[0] }}</div>
      <div class="title">{{ $t('S_DRAW') }}</div>
      <div class="title">{{ teamName[1] }}</div>
    </div>
    <!-- test4{{ collisionLen }} -->
    <div class="odds-table-wrap clearfix">
      <div class="odds-table odds-table-left">
        <div
          v-if="formatOddsData['17']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['17'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['17'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['17'])"
        >
          <!-- ['17'] {{ oddsData[0] }} -->
          <!-- ['17'] {{ formatOddsData['17'] }} -->
          <div class="score">
            {{ formatOddsData['17'] && formatOddsData['17'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['17'])">
            <odds :odds="formatOddsData['17']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['22']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['22'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['22'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['22'])"
        >
          <div class="score">
            {{ formatOddsData['22'] && formatOddsData['22'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['22'])">
            <odds :odds="formatOddsData['22']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['23']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['23'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['23'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['23'])"
        >
          <div class="score">
            {{ formatOddsData['23'] && formatOddsData['23'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['23'])">
            <odds :odds="formatOddsData['23']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['27']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['27'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['27'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['27'])"
        >
          <div class="score">
            {{ formatOddsData['27'] && formatOddsData['27'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['27'])">
            <odds :odds="formatOddsData['27']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['28']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['28'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['28'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['28'])"
        >
          <div class="score">
            {{ formatOddsData['28'] && formatOddsData['28'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['28'])">
            <odds :odds="formatOddsData['28']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['29']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['29'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['29'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['29'])"
        >
          <div class="score">
            {{ formatOddsData['29'] && formatOddsData['29'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['29'])">
            <odds :odds="formatOddsData['29']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['32']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['32'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['32'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['32'])"
        >
          <div class="score">
            {{ formatOddsData['32'] && formatOddsData['32'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['32'])">
            <odds :odds="formatOddsData['32']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['33']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['33'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['33'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['33'])"
        >
          <div class="score">
            {{ formatOddsData['33'] && formatOddsData['33'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['33'])">
            <odds :odds="formatOddsData['33']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['34']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['34'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['34'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['34'])"
        >
          <div class="score">
            {{ formatOddsData['34'] && formatOddsData['34'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['34'])">
            <odds :odds="formatOddsData['34']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['35']"
          class="odds-wrap last-odds clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['35'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['35'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['35'])"
        >
          <div class="score">
            {{ formatOddsData['35'] && formatOddsData['35'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['35'])">
            <odds :odds="formatOddsData['35']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
      </div>
      <div class="odds-table odds-table-center">
        <div
          v-if="formatOddsData['12']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['12'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['12'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['12'])"
        >
          <div class="score">
            {{ formatOddsData['12'] && formatOddsData['12'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['12'])">
            <odds :odds="formatOddsData['12']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['18']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['18'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['18'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['18'])"
        >
          <div class="score">
            {{ formatOddsData['18'] && formatOddsData['18'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['18'])">
            <odds :odds="formatOddsData['18']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['24']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['24'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['24'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['24'])"
        >
          <div class="score">
            {{ formatOddsData['24'] && formatOddsData['24'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['24'])">
            <odds :odds="formatOddsData['24']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['30']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['30'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['30'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['30'])"
        >
          <div class="score">
            {{ formatOddsData['30'] && formatOddsData['30'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['30'])">
            <odds :odds="formatOddsData['30']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['36']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['36'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['36'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['36'])"
        >
          <div class="score">
            {{ formatOddsData['36'] && formatOddsData['36'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['36'])">
            <odds :odds="formatOddsData['36']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div class="other-score">
          <div
            v-if="formatOddsData['439']"
            class="odds-wrap clearfix"
            :class="{
              active: curBetSlip.some(item => item.odds_id === formatOddsData['439'].WagerSelectionId),
            }"
            @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['439'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['439'])"
          >
            <div class="score">
              {{ formatOddsData['439'] && formatOddsData['439'].SelectionName }}
            </div>
            <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['439'])">
              <odds :odds="formatOddsData['439']" :marketline-status-id="getMarketlineStatusId" />
            </div>
          </div>
        </div>
      </div>
      <div class="odds-table odds-table-right">
        <div
          v-if="formatOddsData['13']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['13'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['13'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['13'])"
        >
          <div class="score">
            {{ formatOddsData['13'] && formatOddsData['13'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['13'])">
            <odds :odds="formatOddsData['13']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['14']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['14'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['14'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['14'])"
        >
          <div class="score">
            {{ formatOddsData['14'] && formatOddsData['14'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['14'])">
            <odds :odds="formatOddsData['14']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['19']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['19'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['19'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['19'])"
        >
          <div class="score">
            {{ formatOddsData['19'] && formatOddsData['19'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['19'])">
            <odds :odds="formatOddsData['19']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['15']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['15'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['15'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['15'])"
        >
          <div class="score">
            {{ formatOddsData['15'] && formatOddsData['15'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['15'])">
            <odds :odds="formatOddsData['15']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['20']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['20'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['20'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['20'])"
        >
          <div class="score">
            {{ formatOddsData['20'] && formatOddsData['20'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['20'])">
            <odds :odds="formatOddsData['20']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['25']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['25'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['25'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['25'])"
        >
          <div class="score">
            {{ formatOddsData['25'] && formatOddsData['25'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['25'])">
            <odds :odds="formatOddsData['25']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['16']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['16'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['16'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['16'])"
        >
          <div class="score">
            {{ formatOddsData['16'] && formatOddsData['16'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['16'])">
            <odds :odds="formatOddsData['16']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['21']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['21'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['21'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['21'])"
        >
          <div class="score">
            {{ formatOddsData['21'] && formatOddsData['21'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['21'])">
            <odds :odds="formatOddsData['21']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['26']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['26'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['26'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['26'])"
        >
          <div class="score">
            {{ formatOddsData['26'] && formatOddsData['26'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['26'])">
            <odds :odds="formatOddsData['26']" :marketline-status-id="getMarketlineStatusId" />
          </div>
        </div>
        <div
          v-if="formatOddsData['31']"
          class="odds-wrap clearfix"
          :class="{
            active: curBetSlip.some(item => item.odds_id === formatOddsData['31'].WagerSelectionId),
          }"
          @click="clickOdds(oddsData[0].BetTypeId, formatOddsData['31'].SelectionId, oddsData[0].MarketlineId, oddsData[0], formatOddsData['31'])"
        >
          <div class="score">
            {{ formatOddsData['31'] && formatOddsData['31'].SelectionName }}
          </div>
          <div class="odds-outer" @click="betFunction(getPlayTypeData,formatOddsData['31'])">
            <odds :odds="formatOddsData['31']" :marketline-status-id="getMarketlineStatusId" />
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
    formatOddsData() {
      const odds = {}
      this.oddsData.forEach(item => {
        Object.values(item['WagerSelections']).forEach((oddsKey) => {
          odds[oddsKey.SelectionId] = oddsKey
        })
      })
      return odds
    },
    getMarketlineStatusId() {
      return this.oddsData.map(item => item.MarketlineStatusId)[0]
    },
    getPlayTypeData() {
      let playType = ''
      this.oddsData.forEach((item) => {
        playType = item
      })
      return playType
    }
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
    openBet() {
      // const routeName = this.$route.name
      this.$router.push('/comboBet')
    }
  }
}
</script>

<style lang="scss" scoped>

.header {
  color: #A7A7A7;
  font-size: 14px;
  .title {
    float: left;
    width: 33.33%;
    height: 50px;
    line-height: 50px;
    text-align: center;
    overflow:hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
}
.odds-table {
  float: left;
  width: 30%;
  margin-left: 3%;
  .odds-wrap {
    background-color: #FCFCFC;
    line-height: 36px;
    padding: 5px 10px 5px 0;
    text-align: center;
    font-size: 14px;
    .score {
      float: left;
      width: 55%;
      color: #A7A7A7;
    }
    .odds {
      float: left;
      width: 45%;
      text-align: center;
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
