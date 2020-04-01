<template>
  <div class="clearfix">
    <!-- <div class="header clearfix">
      <div class="title">{{ $t('S_HOME_WIN') }}</div>
      <div class="title">{{ $t('S_DRAW') }}</div>
      <div class="title">{{ $t('S_AWAY_WIN') }}</div>
    </div> -->
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
    >
      <div class="header clear">
        <div class="title">{{ $t('S_HOME_WIN') }}</div>
      </div>
      <!-- {{ playType.WagerSelections }} -->
      <div
        v-for="(odds, oddsKey) in sortPlayType(playType.WagerSelections.filter(val => homePlayType.includes(val.SelectionId)))"
        :key="`odds-table1-${oddsKey}`"
        class="table clearfix"
      >
        <div class="team-name">{{ showTeamName(odds) }}</div>
        <div class="odds-wrap">
          <span class="cap">{{ formatCap(odds) }}</span>
          <div class="odds-outer" @click="betFunction(playType, odds)">
            <odds
              :marketline-status-id="playType.MarketlineStatusId"
              :odds="odds"
            />
          </div>
        </div>
      </div>
      <div class="header clear">
        <div class="title">{{ $t('S_DRAW') }}</div>
      </div>
      <div
        v-for="(odds, oddsKey) in sortPlayType(playType.WagerSelections.filter(val => drawPlayType.includes(val.SelectionId)))"
        :key="`odds-table3-${oddsKey}`"
        class="table-full clearfix"
      >
        <div class="team-name">{{ showTeamName(odds) }}</div>
        <div class="odds-wrap">
          <span class="cap">{{ formatCap(odds) }}</span>
          <div class="odds-outer" @click="betFunction(playType, odds)">
            <odds
              :marketline-status-id="playType.MarketlineStatusId"
              :odds="odds"
            />
          </div>
        </div>
      </div>
      <div class="header clear">
        <div class="title">{{ $t('S_AWAY_WIN') }}</div>
      </div>
      <div
        v-for="(odds, oddsKey) in sortPlayType(playType.WagerSelections.filter(val => awayPlayType.includes(val.SelectionId)))"
        :key="`odds-table2-${oddsKey}`"
        class="table clearfix"
      >
        <div class="team-name">{{ showTeamName(odds) }}</div>
        <div class="odds-wrap">
          <span class="cap">{{ formatCap(odds) }}</span>
          <div class="odds-outer" @click="betFunction(playType, odds)">
            <odds
              :marketline-status-id="playType.MarketlineStatusId"
              :odds="odds"
            />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import odds from '../odds/moreGameOdds'

export default {
  components: {
    odds
  },
  props: {
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
    }
  },
  data() {
    return {
      homePlayType: [227, 229, 231, 233, 235],
      awayPlayType: [228, 230, 232, 234, 236],
      drawPlayType: [237]
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
    // homeFilter() {
    //   return this.playType.WagerSelections.filter(val => this.homePlayType.includes(val))
    // },
    // awayFilter() {
    //   return this.playType.WagerSelections.filter(val => this.awayPlayType.includes(val))
    // },
    // drawFilter() {
    //   return this.playType.WagerSelections.filter(val => this.drawPlayType.includes(val))
    // }
  },
  methods: {
    showTeamName(val) {
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
    sortPlayType(val) {
      const temp = [...val]
      temp.sort((a, b) => {
        return a.SelectionId - b.SelectionId
      })
      return temp
    },
    formatCap(val) {
      if (!val.Handicap && val.Handicap !== 0) {
        return
      }

      const mod = val.Handicap % 0.5
      const selectionId = +(val.BetTypeSelectionId)

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

.header {
  background: #E6E6E6;
  color: #848484;
  font-size: 15px;
  .title {
    // float: left;
    // width: 100%;
    height: 34px;
    line-height: 34px;
    text-align: center;
  }
}

.table {
  float: left;
  width: 50%;
  border-bottom: 1px solid #C9C6CD;
  border-right: 1px solid #C9C6CD;
  padding: 6px 4px 6px 10px;
  font-size: 15px;
  display: flex;
  align-items: center;
  justify-content: space-around;
  .team-name {
    float: left;
    width: 65%;
    //padding-top: 12px;
    color: #003075;
  }
  .odds-wrap {
    float: right;
    text-align: right;
    font-size: 15px;
    .cap {
      color: #8c5320;
    }
    .odds-outer {
      display: inline-block;
    }
  }
}
.table-full {
  float: left;
  width: 100%;
  border-bottom: 1px solid #C9C6CD;
  border-right: 1px solid #C9C6CD;
  padding: 6px 10px;
  font-size: 15px;
  align-items: center;
  justify-content: space-around;
  .team-name {
    float: left;
    width: 65%;
    padding-top: 12px;
    color: #003075;
  }
  .odds-wrap {
    float: right;
    text-align: right;
    font-size: 15px;
    .cap {
      color: #8c5320;
    }
    .odds-outer {
      display: inline-block;
    }
  }
}
.odds {
  background-color: var(--content_odds_bg);
  display: inline-block;
  width: 50px;
  padding: 10px 0px 9px;
  border-radius: 3px;
  border: 1px solid #C9C6CD;
  font-weight: bold;
  text-align: center;
}
.clear {
  clear: both;
}
</style>
