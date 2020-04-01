<template>
  <div>
    <div class="header clearfix">
      <div class="title">{{ teamName[0] }}</div>
      <div class="title">{{ $t('S_DRAW') }}</div>
      <div class="title">{{ teamName[1] }}</div>
    </div>
    <div class="odds-table-wrap clearfix">
      <div class="odds-table odds-table-left">
        <div
          v-if="formatOddsData['17']"
          class="odds-wrap clearfix"
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
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import odds from '../odds/moreGameOdds'

export default {
  components: {
    odds
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
    return {}
  },
  computed: {
    ...mapGetters({}),
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
  methods: {
    ...mapActions({})
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
}
</style>
